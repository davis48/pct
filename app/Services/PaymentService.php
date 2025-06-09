<?php

namespace App\Services;

use App\Models\CitizenRequest;
use App\Models\Payment;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    /**
     * Initialise un nouveau paiement pour une demande
     *
     * @param CitizenRequest $citizenRequest
     * @param string $paymentMethod
     * @param array $options
     * @return Payment
     */
    public function initializePayment(CitizenRequest $citizenRequest, string $paymentMethod, array $options = [])
    {
        // Déterminer le montant à facturer selon le type de document
        $amount = $this->calculateAmount($citizenRequest);

        // Créer un nouveau paiement
        $payment = new Payment([
            'citizen_request_id' => $citizenRequest->id,
            'amount' => $amount,
            'reference' => Payment::generateReference(),
            'status' => Payment::STATUS_PENDING,
            'payment_method' => $paymentMethod,
            'phone_number' => $options['phone_number'] ?? null,
            'provider' => $options['provider'] ?? null,
            'notes' => $options['notes'] ?? 'Paiement pour ' . $citizenRequest->type,
        ]);

        $payment->save();

        // Mettre à jour le statut de la demande
        $citizenRequest->update(['payment_status' => 'pending']);

        return $payment;
    }

    /**
     * Simuler un paiement mobile money
     *
     * @param Payment $payment
     * @param array $data
     * @return array
     */
    public function simulateMobileMoneyPayment(Payment $payment, array $data)
    {
        Log::info('Simulation de paiement mobile money', [
            'payment_id' => $payment->id,
            'reference' => $payment->reference,
            'amount' => $payment->amount,
            'phone' => $data['phone_number'],
            'provider' => $data['provider']
        ]);

        $response = [
            'success' => true,
            'transaction_id' => 'SIM-' . strtoupper(uniqid()),
            'message' => 'Paiement simulé avec succès',
            'provider_reference' => $data['provider'] . '-' . rand(100000, 999999),
            'timestamp' => now()->toIso8601String(),
        ];

        // En mode DEMO, tous les paiements réussissent
        $isSuccessful = true;

        if (!$isSuccessful) {
            $response['success'] = false;
            $response['message'] = 'Échec de la simulation de paiement';
            $response['error_code'] = 'PAYMENT_FAILED';

            $payment->update([
                'status' => Payment::STATUS_FAILED,
                'transaction_id' => $response['transaction_id'],
                'callback_data' => $response,
            ]);
            return $response;
        }

        // Mettre à jour le paiement
        $payment->update([
            'status' => Payment::STATUS_COMPLETED,
            'transaction_id' => $response['transaction_id'],
            'callback_data' => $response,
            'paid_at' => now(),
        ]);

        // Mettre à jour la demande
        $payment->citizenRequest->update([
            'payment_status' => \App\Models\CitizenRequest::PAYMENT_STATUS_PAID,
            'status' => \App\Models\CitizenRequest::STATUS_PENDING // Maintenant STATUS_PENDING = 'en_attente'
        ]);

        Log::info('✅ Statuts mis à jour dans le service de paiement', [
            'request_id' => $payment->citizenRequest->id,
            'payment_status' => \App\Models\CitizenRequest::PAYMENT_STATUS_PAID,
            'request_status' => \App\Models\CitizenRequest::STATUS_PENDING
        ]);

        // Créer une notification de paiement effectué
        $this->createPaymentSuccessNotification($payment->citizenRequest);

        return $response;
    }

    /**
     * Calculer le montant à facturer selon le type de document
     *
     * @param CitizenRequest $request
     * @return float
     */
    public function calculateAmount(CitizenRequest $citizenRequest)
    {
        // Sécurité: gérer les types null ou vides
        $documentType = $citizenRequest->type ?? 'legalisation';
        if (empty($documentType)) {
            $documentType = 'legalisation';
        }
        return $this->getPriceForDocumentType($documentType);
    }

    /**
     * Récupère le prix pour un type de document
     *
     * @param string $documentType
     * @return float
     */
    public static function getPriceForDocumentType(string $documentType)
    {
        // Tarifs selon le type de document (à personnaliser)
        $prices = [
            'timbre' => 500, // Prix fixe pour les timbres
            'acte_de_naissance' => 2500,
            'certificat_de_nationalite' => 5000,
            'carte_nationale_identite' => 5000,
            'extrait_de_casier_judiciaire' => 3000,
            'passeport' => 40000,
            'certificat_de_residence' => 1500,
            'acte_de_mariage' => 3500,
            'acte_de_deces' => 500,
            // Tarif par défaut
            'default' => 500,
        ];

        return $prices[$documentType] ?? $prices['default'];
    }

    /**
     * Vérifier le statut d'un paiement externe
     *
     * @param Payment $payment
     * @return array
     */
    public function checkPaymentStatus(Payment $payment)
    {
        // Dans un environnement réel, nous ferions un appel API au fournisseur de paiement
        // Pour la simulation, nous retournons simplement le statut actuel

        return [
            'success' => true,
            'payment_id' => $payment->id,
            'reference' => $payment->reference,
            'status' => $payment->status,
            'transaction_id' => $payment->transaction_id,
            'paid_at' => $payment->paid_at ? $payment->paid_at->toIso8601String() : null,
        ];
    }

    /**
     * Annuler un paiement
     *
     * @param Payment $payment
     * @return bool
     */
    public function cancelPayment(Payment $payment)
    {
        if (!$payment->isPending()) {
            return false;
        }

        $payment->update([
            'status' => Payment::STATUS_CANCELLED,
        ]);

        // Mettre à jour la demande
        $payment->citizenRequest->update([
            'payment_status' => 'cancelled',
        ]);        return true;
    }

    /**
     * Créer une notification de paiement effectué
     *
     * @param CitizenRequest $citizenRequest
     * @return void
     */    private function createPaymentSuccessNotification(CitizenRequest $citizenRequest)
    {
        // Récupérer le paiement pour avoir le montant
        $payment = $citizenRequest->payments()->where('status', Payment::STATUS_COMPLETED)->latest()->first();
        $amount = $payment ? $payment->amount : $this->getPriceForDocumentType($citizenRequest->type);

        Notification::create([
            'user_id' => $citizenRequest->user_id,
            'title' => '✅ Paiement effectué avec succès',
            'message' => "Félicitations ! Votre paiement de " . number_format($amount, 0, ',', ' ') . " FCFA pour la demande de {$citizenRequest->type} (Référence: {$citizenRequest->reference_number}) a été effectué avec succès. Votre demande est maintenant soumise et en cours de traitement par nos services.",
            'type' => 'success',
            'data' => [
                'request_id' => $citizenRequest->id,
                'reference_number' => $citizenRequest->reference_number,
                'payment_status' => 'paid',
                'request_status' => 'pending',
                'payment_notification' => true,
                'amount' => $amount,
                'document_type' => $citizenRequest->type,
                'action_url' => route('requests.show', $citizenRequest->id),
                'action_text' => 'Voir ma demande'
            ],
            'is_read' => false,
        ]);

        Log::info("Notification de paiement créée pour l'utilisateur {$citizenRequest->user_id}, demande {$citizenRequest->id}, montant: {$amount} FCFA");
    }
}
