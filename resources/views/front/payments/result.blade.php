@extends('layouts.front.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="text-center mb-6">
                @if(session('payment_success'))
                    <div class="text-green-500 text-5xl mb-4">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Paiement réussi</h2>
                    <p class="text-gray-600">{{ session('payment_success') }}</p>
                @elseif(session('payment_failed'))
                    <div class="text-red-500 text-5xl mb-4">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Paiement échoué</h2>
                    <p class="text-gray-600">{{ session('payment_failed') }}</p>
                @endif
            </div>

            @if(isset($payment) && $payment->status === 'completed')
                <div class="bg-green-50 border border-green-200 rounded-md p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800">Statut de la demande</h3>
                            <div class="mt-2 text-sm text-green-700">
                                <p>Votre demande est maintenant en attente de traitement par un agent.</p>
                                <p class="mt-1">Vous recevrez une notification dès qu'un agent prendra en charge votre demande.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-6">
                    <dl class="divide-y divide-gray-200">
                        <div class="py-4 flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Référence de paiement</dt>
                            <dd class="text-sm text-gray-900">{{ $payment->reference }}</dd>
                        </div>
                        <div class="py-4 flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Montant payé</dt>
                            <dd class="text-sm text-gray-900">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</dd>
                        </div>
                        <div class="py-4 flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Date du paiement</dt>
                            <dd class="text-sm text-gray-900">{{ $payment->paid_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            @endif

            <div class="mt-8 flex justify-center space-x-4">
                <a href="{{ route('citizen.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-home mr-2"></i>
                    Retour au tableau de bord
                </a>
                @if(isset($payment) && $payment->status !== 'completed')
                    <a href="{{ route('payments.retry', $payment) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <i class="fas fa-redo mr-2"></i>
                        Réessayer le paiement
                    </a>
                @endif
            </div>

            @if(config('app.debug'))
                <div class="mt-8 p-4 bg-gray-100 rounded-md">
                    <h3 class="text-sm font-medium text-gray-900 mb-2">Informations de débogage</h3>
                    <dl class="text-xs text-gray-500">
                        <div class="grid grid-cols-2 gap-2">
                            <dt>Payment ID:</dt>
                            <dd>{{ $payment->id ?? 'N/A' }}</dd>
                            <dt>Payment Status:</dt>
                            <dd>{{ $payment->status ?? 'N/A' }}</dd>
                            <dt>Request ID:</dt>
                            <dd>{{ $payment->citizenRequest->id ?? 'N/A' }}</dd>
                            <dt>Request Status:</dt>
                            <dd>{{ $payment->citizenRequest->status ?? 'N/A' }}</dd>
                            <dt>Payment Required:</dt>
                            <dd>{{ $payment->citizenRequest->payment_required ? 'Yes' : 'No' }}</dd>
                        </div>
                    </dl>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
