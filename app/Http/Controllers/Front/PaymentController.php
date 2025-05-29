<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\CitizenRequest;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->middleware('auth');
        $this->paymentService = $paymentService;
    }

    /**
     * Affiche la page de paiement pour une demande spécifique
     */
    public function show(CitizenRequest $request)
    {
        // Vérifier que la demande appartient à l'utilisateur connecté
        if ($request->user_id !== Auth::id()) {
            return redirect()->route('requests.index')
                ->with('error', 'Vous n\'êtes pas autorisé à effectuer cette action.');
        }

        // Vérifier si un paiement est nécessaire
        if (!$request->requiresPayment()) {
            return redirect()->route('requests.show', $request)
                ->with('info', 'Aucun paiement n\'est requis pour cette demande.');
        }

        // Vérifier si la demande a déjà été payée
        if ($request->hasSuccessfulPayment()) {
            return redirect()->route('requests.show', $request)
                ->with('success', 'Cette demande a déjà été payée.');
        }

        // Récupérer le dernier paiement en attente s'il existe
        $pendingPayment = $request->payments()->pending()->latest()->first();

        return view('front.payments.show', [
            'request' => $request,
            'pendingPayment' => $pendingPayment,
            'providers' => [
                Payment::PROVIDER_CINET => 'CinetPay',
                Payment::PROVIDER_MTN => 'MTN Mobile Money',
                Payment::PROVIDER_ORANGE => 'Orange Money',
                Payment::PROVIDER_MOOV => 'Moov Money',
                Payment::PROVIDER_WAVE => 'Wave',
            ]
        ]);
    }

    /**
     * Initialise un nouveau paiement
     */
    public function initialize(Request $request, CitizenRequest $citizenRequest)
    {
        // Vérifier que la demande appartient à l'utilisateur connecté
        if ($citizenRequest->user_id !== Auth::id()) {
            return redirect()->route('requests.index')
                ->with('error', 'Vous n\'êtes pas autorisé à effectuer cette action.');
        }

        // Valider les données
        $validated = $request->validate([
            'payment_method' => 'required|in:mobile_money,card,bank_transfer',
            'provider' => 'required_if:payment_method,mobile_money|string',
            'phone_number' => 'required_if:payment_method,mobile_money|string|min:8',
        ]);

        try {
            // Annuler tout paiement en attente existant
            $pendingPayments = $citizenRequest->payments()->pending()->get();
            foreach ($pendingPayments as $pendingPayment) {
                $this->paymentService->cancelPayment($pendingPayment);
            }

            // Initialiser un nouveau paiement
            $payment = $this->paymentService->initializePayment(
                $citizenRequest,
                $validated['payment_method'],
                [
                    'provider' => $validated['provider'] ?? null,
                    'phone_number' => $validated['phone_number'] ?? null,
                ]
            );

            return redirect()->route('payments.process', $payment);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'initialisation du paiement', [
                'error' => $e->getMessage(),
                'citizen_request_id' => $citizenRequest->id,
                'user_id' => Auth::id(),
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de l\'initialisation du paiement. Veuillez réessayer.')
                ->withInput();
        }
    }

    /**
     * Traite un paiement
     */
    public function process(Payment $payment)
    {
        // Vérifier que le paiement appartient à l'utilisateur connecté
        if ($payment->citizenRequest->user_id !== Auth::id()) {
            return redirect()->route('requests.index')
                ->with('error', 'Vous n\'êtes pas autorisé à effectuer cette action.');
        }

        // Vérifier que le paiement est en attente
        if (!$payment->isPending()) {
            return redirect()->route('payments.status', $payment);
        }

        return view('front.payments.process', [
            'payment' => $payment,
            'request' => $payment->citizenRequest,
        ]);
    }

    /**
     * Simule un paiement mobile money
     */
    public function simulateMobileMoneyPayment(Request $request, Payment $payment)
    {
        // Vérifier que le paiement appartient à l'utilisateur connecté
        if ($payment->citizenRequest->user_id !== Auth::id()) {
            return redirect()->route('requests.index')
                ->with('error', 'Vous n\'êtes pas autorisé à effectuer cette action.');
        }

        // Vérifier que le paiement est en attente
        if (!$payment->isPending()) {
            return redirect()->route('payments.status', $payment);
        }

        // Vérifier que c'est un paiement mobile money
        if ($payment->payment_method !== Payment::METHOD_MOBILE_MONEY) {
            return redirect()->back()
                ->with('error', 'Ce paiement ne peut pas être traité par mobile money.');
        }

        $validated = $request->validate([
            'confirm' => 'required|in:1',
        ]);

        try {
            // Simuler le paiement
            $response = $this->paymentService->simulateMobileMoneyPayment($payment, [
                'phone_number' => $payment->phone_number,
                'provider' => $payment->provider,
            ]);

            if (!$response['success']) {
                return redirect()->route('payments.status', $payment)
                    ->with('error', 'Le paiement a échoué. ' . ($response['message'] ?? ''));
            }

            return redirect()->route('payments.status', $payment)
                ->with('success', 'Paiement effectué avec succès!');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la simulation du paiement', [
                'error' => $e->getMessage(),
                'payment_id' => $payment->id,
                'user_id' => Auth::id(),
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors du traitement du paiement. Veuillez réessayer.');
        }
    }

    /**
     * Affiche le statut d'un paiement
     */
    public function status(Payment $payment)
    {
        // Vérifier que le paiement appartient à l'utilisateur connecté
        if ($payment->citizenRequest->user_id !== Auth::id()) {
            return redirect()->route('requests.index')
                ->with('error', 'Vous n\'êtes pas autorisé à effectuer cette action.');
        }

        try {
            // Vérifier le statut du paiement
            $status = $this->paymentService->checkPaymentStatus($payment);

            return view('front.payments.status', [
                'payment' => $payment,
                'request' => $payment->citizenRequest,
                'status' => $status,
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la vérification du statut du paiement', [
                'error' => $e->getMessage(),
                'payment_id' => $payment->id,
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('requests.show', $payment->citizenRequest)
                ->with('error', 'Une erreur est survenue lors de la vérification du statut du paiement.');
        }
    }

    /**
     * Annule un paiement
     */
    public function cancel(Payment $payment)
    {
        // Vérifier que le paiement appartient à l'utilisateur connecté
        if ($payment->citizenRequest->user_id !== Auth::id()) {
            return redirect()->route('requests.index')
                ->with('error', 'Vous n\'êtes pas autorisé à effectuer cette action.');
        }

        // Vérifier que le paiement est en attente
        if (!$payment->isPending()) {
            return redirect()->route('payments.status', $payment)
                ->with('error', 'Ce paiement ne peut plus être annulé.');
        }

        try {
            // Annuler le paiement
            $this->paymentService->cancelPayment($payment);

            return redirect()->route('payments.show', $payment->citizenRequest)
                ->with('success', 'Le paiement a été annulé avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'annulation du paiement', [
                'error' => $e->getMessage(),
                'payment_id' => $payment->id,
                'user_id' => Auth::id(),
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de l\'annulation du paiement.');
        }
    }
}
