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
     * @param CitizenRequest $request
     * @param string $paymentMethod
     * @param array $options
     * @return Payment
     */
    public function initializePayment(CitizenRequest $request, string $paymentMethod, array $options = [])
    {
        // Déterminer le montant à facturer selon le type de document
        $amount = $this->calculateAmount($request);

        // Créer un nouveau paiement
        $payment = new Payment([
            'citizen_request_id' => $request->id,
            'amount' => $amount,
            'reference' => Payment::generateReference(),
            'status' => Payment::STATUS_PENDING,
            'payment_method' => $paymentMethod,
            'phone_number' => $options['phone_number'] ?? null,
            'provider' => $options['provider'] ?? null,
            'notes' => $options['notes'] ?? 'Paiement pour ' . $request->type,
        ]);

        $payment->save();
        
        // Mettre à jour le statut de la demande
        $request->update(['payment_status' => 'pending']);

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
        // Loguer les informations de simulation
        Log::info('Simulation de paiement mobile money', [
            'payment_id' => $payment->id,
            'reference' => $payment->reference,
            'amount' => $payment->amount,
            'phone' => $data['phone_number'],
            'provider' => $data['provider']
        ]);

        // Simulation de l'API de paiement
        $response = [
            'success' => true,
            'transaction_id' => 'SIM-' . strtoupper(uniqid()),
            'message' => 'Paiement simulé avec succès',
            'provider_reference' => $data['provider'] . '-' . rand(100000, 999999),
            'timestamp' => now()->toIso8601String(),
        ];        // En mode simulation, on considère que 99% des paiements réussissent
        $isSuccessful = rand(1, 100) <= 99;

        if (!$isSuccessful) {
            $response['success'] = false;
            $response['message'] = 'Échec de la simulation de paiement';
            $response['error_code'] = 'PAYMENT_FAILED';
            
            // Mettre à jour le paiement
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
            'payment_status' => 'paid',
            'status' => 'pending', // La demande passe en attente de traitement
        ]);

        // Créer une notification de paiement effectué
        $this->createPaymentSuccessNotification($payment->citizenRequest);        return $response;
    }

    /**
     * Calculer le montant à facturer selon le type de document
     *
     * @param CitizenRequest $request
     * @return float
     */
    public function calculateAmount(CitizenRequest $request)
    {
        return $this->getPriceForDocumentType($request->type);
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
            'acte_de_naissance' => 2500,
            'certificat_de_nationalite' => 5000,
            'carte_nationale_identite' => 5000,
            'extrait_de_casier_judiciaire' => 3000,
            'passeport' => 40000,
            'certificat_de_residence' => 1500,
            'acte_de_mariage' => 3500,
            'acte_de_deces' => 2000,
            // Tarif par défaut
            'default' => 2000,
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
     * @param CitizenRequest $request
     * @return void
     */    private function createPaymentSuccessNotification(CitizenRequest $request)
    {
        Notification::create([
            'user_id' => $request->user_id,
            'title' => 'Paiement effectué avec succès',
            'message' => "Votre paiement pour la demande de {$request->type} (Référence: {$request->reference_number}) a été effectué avec succès. Votre demande est maintenant soumise et en cours de traitement.",
            'type' => 'success',
            'data' => [
                'request_id' => $request->id,
                'reference_number' => $request->reference_number,
                'payment_status' => 'paid',
                'request_status' => 'pending',
                'payment_notification' => true
            ],
            'is_read' => false,
        ]);

        Log::info("Notification de paiement créée pour l'utilisateur {$request->user_id}, demande {$request->id}");
    }
}
