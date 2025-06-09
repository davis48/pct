<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\CitizenRequest;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Notification priority levels
     */
    const PRIORITY_HIGH = 'high';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_LOW = 'low';
    
    /**
     * Important status transitions that should always notify
     */
    const IMPORTANT_TRANSITIONS = [
        'pending' => ['approved', 'rejected', 'completed'],
        'in_progress' => ['approved', 'rejected', 'completed'],
        'approved' => ['completed'],
    ];
      /**
     * Send notification when request status changes.
     */
    public function sendStatusChangeNotification(CitizenRequest $request, $oldStatus, $newStatus)
    {
        $user = $request->user;
        
        if (!$user) {
            return;
        }

        // Check if this transition is important enough to notify
        if (!$this->shouldNotifyForStatusChange($oldStatus, $newStatus, $user)) {
            return;
        }        // Check for recent similar notifications to avoid spam
        if ($this->hasRecentSimilarStatusNotification($user, $request->id, $newStatus)) {
            return;
        }

        // Messages détaillés selon le nouveau statut
        $statusData = $this->getDetailedStatusData($newStatus, $request);
        
        // Créer la notification en base avec plus de détails
        $notification = Notification::create([
            'user_id' => $user->id,
            'title' => $statusData['title'],
            'message' => $statusData['message'],
            'type' => $statusData['type'],
            'data' => json_encode([
                'type' => 'status_change',
                'request_id' => $request->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'document_name' => $request->document->title ?? 'Document non spécifié',
                'document_type' => $request->document->type ?? 'Document administratif',
                'status_changed_at' => now()->toISOString(),
                'processed_by' => $request->processedBy->name ?? null,
                'agent_assigned' => $request->assignedAgent->name ?? null,
                'amount' => $request->amount ?? 0,
                'reference' => $request->reference ?? $request->id,
                'next_step' => $statusData['next_step'],
                'estimated_completion' => $statusData['estimated_completion'],
                'details' => $statusData['details'],
                'actions_required' => $statusData['actions_required'] ?? null,
                'priority' => $this->getStatusPriority($newStatus)
            ]),
            'is_read' => false,
        ]);

        // Envoyer via les canaux activés seulement pour les notifications importantes
        if ($this->getStatusPriority($newStatus) !== self::PRIORITY_LOW) {
            $this->sendNotificationChannels($user, $statusData['title'], $statusData['message'], $request);
        }

        return $notification;
    }

    /**
     * Get detailed status data for notifications.
     */
    private function getDetailedStatusData($status, CitizenRequest $request)
    {
        $documentName = $request->document->title ?? 'document';
        $amount = $request->amount ? number_format($request->amount, 0, ',', ' ') . ' FCFA' : '';
        
        switch ($status) {
            case 'pending':
                return [
                    'title' => 'Demande reçue et en attente',
                    'message' => "Votre demande de {$documentName} a été reçue avec succès et est maintenant en file d'attente de traitement.",
                    'type' => 'info',
                    'next_step' => 'Attribution à un agent disponible',
                    'estimated_completion' => now()->addDays(5)->format('d/m/Y'),
                    'details' => "Référence: #{$request->id}. Montant: {$amount}. Votre demande sera assignée à un agent dans les plus brefs délais."
                ];
                
            case 'in_progress':
                $agentName = $request->assignedAgent->name ?? 'un agent';
                return [
                    'title' => 'Traitement en cours par ' . $agentName,
                    'message' => "Votre demande de {$documentName} est maintenant en cours de traitement par {$agentName}.",
                    'type' => 'info',
                    'next_step' => 'Vérification des documents et informations',
                    'estimated_completion' => now()->addDays(3)->format('d/m/Y'),
                    'details' => "L'agent vérifie actuellement vos documents et informations. Le processus de validation est en cours."
                ];
                
            case 'approved':
                return [
                    'title' => 'Demande approuvée avec succès !',
                    'message' => "Excellente nouvelle ! Votre demande de {$documentName} a été approuvée et validée.",
                    'type' => 'success',
                    'next_step' => 'Préparation du document final',
                    'estimated_completion' => now()->addDays(2)->format('d/m/Y'),
                    'details' => "Votre demande a été approuvée après vérification complète. Le document sera bientôt prêt pour retrait."
                ];
                
            case 'rejected':
                $reason = $request->rejection_reason ?? 'Informations incomplètes ou incorrectes';
                return [
                    'title' => 'Demande rejetée - Action requise',
                    'message' => "Votre demande de {$documentName} a été rejetée. Motif: {$reason}",
                    'type' => 'error',
                    'next_step' => 'Correction des informations et nouvelle soumission',
                    'estimated_completion' => null,
                    'details' => "Motif du rejet: {$reason}. Vous pouvez soumettre une nouvelle demande en corrigeant les points mentionnés.",
                    'actions_required' => 'Corriger les informations et soumettre une nouvelle demande'
                ];
                
            case 'completed':
                return [
                    'title' => 'Document prêt pour retrait !',
                    'message' => "Votre {$documentName} est maintenant prêt ! Vous pouvez venir le retirer à nos bureaux.",
                    'type' => 'success',
                    'next_step' => 'Retrait du document au bureau',
                    'estimated_completion' => now()->format('d/m/Y'),
                    'details' => "Votre document est prêt. Bureau: PCT-UVCI Principal. Horaires: Lun-Ven 8h-17h. Apportez votre pièce d'identité."
                ];
                
            default:
                return [
                    'title' => 'Mise à jour de votre demande',
                    'message' => "Le statut de votre demande de {$documentName} a été mis à jour.",
                    'type' => 'info',
                    'next_step' => 'Suivi en cours',
                    'estimated_completion' => null,
                    'details' => "Une mise à jour a été effectuée sur votre demande."
                ];
        }
    }

    /**
     * Get priority level for status.
     */
    private function getStatusPriority($status)
    {
        $priorities = [
            'rejected' => 'high',
            'completed' => 'high',
            'approved' => 'medium',
            'in_progress' => 'medium',
            'pending' => 'low'
        ];
        
        return $priorities[$status] ?? 'low';
    }    /**
     * Send notification when request is assigned to an agent.
     */
    public function sendAssignmentNotification(CitizenRequest $request, User $agent)
    {
        $user = $request->user;
        
        if (!$user) {
            return;
        }

        // Check user preferences for assignment notifications
        $preferences = $user->notification_preferences ?? [];
        if (!($preferences['assignment_notifications'] ?? true)) {
            return;
        }

        $title = 'Agent assigné à votre demande';
        $message = "Votre demande a été assignée à l'agent {$agent->name}. Le traitement va commencer prochainement.";
        
        return $this->sendSmartNotification($user, $title, $message, 'info', [
            'type' => 'assignment',
            'request_id' => $request->id,
            'agent_id' => $agent->id,
            'agent_name' => $agent->name,
            'document_name' => $request->document->title ?? 'Document non spécifié',
            'priority' => self::PRIORITY_MEDIUM,
        ]);
    }

    /**
     * Send welcome notification to new citizens.
     */
    public function sendWelcomeNotification(User $user)
    {
        $title = 'Bienvenue sur la plateforme PCT-UVCI';
        $message = 'Votre compte a été créé avec succès. Vous pouvez maintenant soumettre vos demandes de documents administratifs.';

        $notification = Notification::create([
            'user_id' => $user->id,
            'title' => $title,
            'message' => $message,
            'type' => 'success',
            'data' => json_encode([
                'welcome' => true,
                'created_at' => now()->toISOString(),
            ]),
            'is_read' => false,
        ]);

        return $notification;
    }

    /**
     * Get notification type based on status.
     */
    private function getNotificationType($status)
    {
        $types = [
            'pending' => 'info',
            'in_progress' => 'info',
            'approved' => 'success',
            'rejected' => 'error',
        ];

        return $types[$status] ?? 'info';
    }    /**
     * Send SMS notification using Twilio API
     */
    private function sendSMSNotification($phone, $title, $message)
    {
        // Vérifier si le numéro est au format international
        if (!str_starts_with($phone, '+')) {
            // Ajouter l'indicatif de la Côte d'Ivoire si nécessaire
            if (str_starts_with($phone, '0')) {
                $phone = '+225' . substr($phone, 1);
            } else {
                $phone = '+225' . $phone;
            }
        }
        
        try {
            // Configuration Twilio - à mettre en place dans les variables d'environnement
            $sid = config('services.twilio.sid');
            $token = config('services.twilio.token');
            $from = config('services.twilio.from');
            
            // Vérifier si la configuration est disponible
            if (!$sid || !$token || !$from) {
                // Mode de développement - simuler l'envoi de SMS
                Log::info("SMS Notification to {$phone}: {$title} - {$message}");
                return true;
            }
            
            // Si Twilio SDK est installé
            if (class_exists('\\Twilio\\Rest\\Client')) {
                // Intégration avec Twilio
                $client = new \Twilio\Rest\Client($sid, $token);
                $result = $client->messages->create(
                    $phone,
                    [
                        'from' => $from,
                        'body' => "[PCT-UVCI] {$title}: {$message}"
                    ]
                );
                
                Log::info("SMS envoyé à {$phone}: {$result->sid}");
            } else {
                // Fallback si Twilio n'est pas disponible
                Log::info("SMS Notification to {$phone}: {$title} - {$message} (Twilio non installé)");
            }
            
            return true;
        } catch (\Exception $e) {
            Log::error("Échec de l'envoi du SMS: " . $e->getMessage());
            return false;
        }
    }    /**
     * Send email notification.
     */
    private function sendEmailNotification(User $user, $title, $message, CitizenRequest $request = null)
    {
        try {
            // Si l'email n'est pas configuré ou en mode test
            $mailerEnabled = config('mail.default') && config('mail.mailers.smtp.host');
            
            if (!$mailerEnabled) {
                // Mode de développement - simuler l'envoi d'email
                Log::info("Email Notification to {$user->email}: {$title} - {$message}");
                return true;
            }
            
            // Préparer les données pour le template d'email
            $emailData = [
                'user' => $user,
                'title' => $title,
                'message' => $message,
                'request' => $request,
                'actionUrl' => $request ? route('citizen.request.show', $request->id) : route('citizen.dashboard'),
                'appName' => config('app.name', 'PCT-UVCI'),
                'date' => now()->format('d/m/Y H:i')
            ];
              // Envoyer l'email avec le template approprié
            try {
                Mail::send('emails.notification', $emailData, function ($mail) use ($user, $title) {
                    $mail->from(config('mail.from.address', 'noreply@pct-uvci.gov.ci'), config('mail.from.name', 'PCT-UVCI'));
                    $mail->to($user->email, $user->nom . ' ' . $user->prenoms);
                    $mail->subject("[PCT-UVCI] {$title}");
                });
                Log::info("Email envoyé à {$user->email}");
            } catch (\Exception $emailError) {
                Log::error("Erreur lors de l'envoi de l'email: " . $emailError->getMessage());
                // Continue sans faire planter l'application
            }
            return true;
        } catch (\Exception $e) {
            Log::error("Échec de l'envoi de l'email: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get unread notifications count for a user.
     */
    public function getUnreadCount(User $user)
    {
        return Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();
    }

    /**
     * Get recent notifications for a user.
     */
    public function getRecentNotifications(User $user, $limit = 10)
    {
        return Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }    /**
     * Send notification when a new request is submitted.
     */
    public function sendRequestSubmittedNotification(CitizenRequest $request)
    {
        $user = $request->user;
        
        if (!$user) {
            return;
        }

        // Only send if user wants submission confirmations
        $preferences = $user->notification_preferences ?? [];
        if (!($preferences['submission_confirmations'] ?? true)) {
            return;
        }

        $title = 'Demande soumise avec succès';
        $documentTitle = $request->document ? $request->document->title : ucfirst(str_replace('_', ' ', $request->type));
        $message = "Votre demande de {$documentTitle} a été soumise avec succès. Numéro de référence : #{$request->id}";

        return $this->sendSmartNotification($user, $title, $message, 'success', [
            'type' => 'request_submitted',
            'request_id' => $request->id,
            'document_name' => $request->document->title ?? 'Document non spécifié',
            'submitted_at' => now()->toISOString(),
            'amount' => $request->amount ?? 0,
            'status' => $request->status,
            'reference' => $request->id,
            'document_type' => $request->document->title ?? 'Document',
            'details' => "Votre demande a été enregistrée dans notre système et sera traitée dans les plus brefs délais.",
            'priority' => self::PRIORITY_MEDIUM,
        ]);
    }    /**
     * Send notification when request payment is completed.
     */
    public function sendPaymentCompletedNotification(CitizenRequest $request)
    {
        $user = $request->user;
        
        if (!$user) {
            return;
        }

        // Payment notifications are always important
        $preferences = $user->notification_preferences ?? [];
        if (!($preferences['payment_notifications'] ?? true)) {
            return;
        }

        // Récupérer le montant du paiement le plus récent
        $payment = $request->payments()->where('status', 'completed')->latest()->first();
        $amount = $payment ? number_format($payment->amount, 0, ',', ' ') : '500';

        $title = 'Paiement confirmé';
        $message = "Le paiement de {$amount} FCFA pour votre demande a été confirmé. Votre demande est maintenant en file d'attente.";

        return $this->sendSmartNotification($user, $title, $message, 'success', [
            'type' => 'payment_completed',
            'request_id' => $request->id,
            'document_name' => $request->document?->title ?? 'Document non spécifié',
            'amount' => $payment ? $payment->amount : null,
            'payment_method' => $payment?->payment_method ?? 'Non spécifié',
            'payment_reference' => $payment?->reference ?? null,
            'paid_at' => now()->toISOString(),
            'next_step' => 'Votre demande va être assignée à un agent pour traitement.',
            'details' => "Paiement de {$amount} FCFA confirmé. Votre demande est maintenant dans la file d'attente de traitement.",
            'priority' => self::PRIORITY_HIGH,
        ]);
    }    /**
     * Send notification when request is being processed.
     */
    public function sendProcessingStartedNotification(CitizenRequest $request, User $agent)
    {
        $user = $request->user;
        
        if (!$user) {
            return;
        }

        // Processing start notifications are optional (medium priority)
        $preferences = $user->notification_preferences ?? [];
        if (!($preferences['processing_notifications'] ?? false)) {
            // Only notify if user explicitly enabled intermediate notifications
            return;
        }

        $title = 'Traitement de votre demande commencé';
        $message = "L'agent {$agent->name} a commencé le traitement de votre demande de {$request->document->title}.";

        return $this->sendSmartNotification($user, $title, $message, 'info', [
            'type' => 'processing_started',
            'request_id' => $request->id,
            'document_name' => $request->document->title ?? 'Document non spécifié',
            'agent_id' => $agent->id,
            'agent_name' => $agent->name,
            'started_at' => now()->toISOString(),
            'estimated_completion' => now()->addDays(3)->toISOString(),
            'current_step' => 'Vérification des documents',
            'details' => "Votre demande est maintenant en cours de traitement. L'agent vérifie vos documents et informations.",
            'priority' => self::PRIORITY_LOW, // Low priority - only in app, no SMS/email
        ]);
    }

    /**
     * Send notification when request is approved.
     */
    public function sendRequestApprovedNotification(CitizenRequest $request)
    {
        $user = $request->user;
        
        if (!$user) {
            return;
        }

        $title = 'Demande approuvée !';
        $message = "Excellente nouvelle ! Votre demande de {$request->document->title} a été approuvée. Votre document est en cours de préparation.";

        $notification = Notification::create([
            'user_id' => $user->id,
            'title' => $title,
            'message' => $message,
            'type' => 'success',
            'data' => json_encode([
                'type' => 'request_approved',
                'request_id' => $request->id,
                'document_name' => $request->document->title ?? 'Document non spécifié',
                'approved_at' => now()->toISOString(),
                'approved_by' => $request->processedBy->name ?? 'Agent',
                'next_step' => 'Préparation du document',
                'estimated_ready' => now()->addDays(2)->format('d/m/Y'),
                'pickup_info' => 'Vous serez notifié dès que votre document sera prêt pour retrait.',
                'details' => "Votre demande a été approuvée après vérification. Le document est maintenant en cours de préparation."
            ]),
            'is_read' => false,
        ]);

        $this->sendNotificationChannels($user, $title, $message, $request);
        return $notification;
    }

    /**
     * Send notification when request is rejected.
     */
    public function sendRequestRejectedNotification(CitizenRequest $request, $reason = null)
    {
        $user = $request->user;
        
        if (!$user) {
            return;
        }

        $title = 'Demande rejetée';
        $message = "Votre demande de {$request->document->title} a été rejetée.";
        if ($reason) {
            $message .= " Motif : {$reason}";
        }

        $notification = Notification::create([
            'user_id' => $user->id,
            'title' => $title,
            'message' => $message,
            'type' => 'error',
            'data' => json_encode([
                'type' => 'request_rejected',
                'request_id' => $request->id,
                'document_name' => $request->document->title ?? 'Document non spécifié',
                'rejected_at' => now()->toISOString(),
                'rejected_by' => $request->processedBy->name ?? 'Agent',
                'reason' => $reason ?? 'Motif non spécifié',
                'next_step' => 'Vous pouvez soumettre une nouvelle demande en corrigeant les points mentionnés.',
                'support_info' => 'Contactez le support si vous avez des questions : support@pct-uvci.gov.ci',
                'details' => $reason ? "Motif de rejet : {$reason}" : "Votre demande ne répond pas aux critères requis."
            ]),
            'is_read' => false,
        ]);

        $this->sendNotificationChannels($user, $title, $message, $request);
        return $notification;
    }

    /**
     * Send notification when document is ready for pickup.
     */
    public function sendDocumentReadyNotification(CitizenRequest $request)
    {
        $user = $request->user;
        
        if (!$user) {
            return;
        }

        $title = 'Document prêt pour retrait';
        $message = "Votre {$request->document->title} est prêt ! Vous pouvez venir le retirer à nos bureaux.";

        $notification = Notification::create([
            'user_id' => $user->id,
            'title' => $title,
            'message' => $message,
            'type' => 'success',
            'data' => json_encode([
                'type' => 'document_ready',
                'request_id' => $request->id,
                'document_name' => $request->document->title ?? 'Document non spécifié',
                'ready_at' => now()->toISOString(),
                'pickup_location' => 'Bureau Principal PCT-UVCI',
                'pickup_hours' => 'Lundi-Vendredi: 8h-17h, Samedi: 8h-12h',
                'required_documents' => 'Pièce d\'identité et reçu de paiement',
                'validity_period' => '30 jours',
                'details' => "Votre document est prêt pour retrait. N'oubliez pas d'apporter votre pièce d'identité et votre reçu de paiement."
            ]),
            'is_read' => false,
        ]);

        $this->sendNotificationChannels($user, $title, $message, $request);
        return $notification;
    }

    /**
     * Check if we should notify for a status change based on importance and user preferences
     */
    private function shouldNotifyForStatusChange($oldStatus, $newStatus, User $user)
    {
        // Always notify for important final states
        if (in_array($newStatus, ['approved', 'rejected', 'completed'])) {
            return true;
        }
        
        // Check if it's an important transition
        if (isset(self::IMPORTANT_TRANSITIONS[$oldStatus]) && 
            in_array($newStatus, self::IMPORTANT_TRANSITIONS[$oldStatus])) {
            return true;
        }
        
        // Check user preferences for intermediate notifications
        $preferences = $user->notification_preferences ?? [];
        $intermediateNotifications = $preferences['intermediate_notifications'] ?? false;
        
        // Only notify for intermediate steps if user explicitly wants them
        if (!$intermediateNotifications && in_array($newStatus, ['pending', 'in_progress'])) {
            return false;
        }
        
        return true;
    }
      /**
     * Check if user has received a similar notification recently to avoid spam
     */
    private function hasRecentSimilarNotification(User $user, $type, $requestId, $withinMinutes = 30)
    {
        return Notification::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subMinutes($withinMinutes))
            ->whereJsonContains('data->type', $type)
            ->whereJsonContains('data->request_id', $requestId)
            ->exists();
    }

    /**
     * Check for recent similar status notifications to prevent spam
     */
    private function hasRecentSimilarStatusNotification(User $user, $requestId, $newStatus, $withinMinutes = 15)
    {
        return Notification::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subMinutes($withinMinutes))
            ->whereJsonContains('data->type', 'status_change')
            ->whereJsonContains('data->request_id', $requestId)
            ->whereJsonContains('data->new_status', $newStatus)
            ->exists();
    }
    
    /**
     * Group similar notifications to reduce clutter
     */
    public function groupSimilarNotifications(User $user)
    {
        // Find notifications that can be grouped (same type, same request, within last hour)
        $notifications = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->where('created_at', '>=', now()->subHour())
            ->get()
            ->groupBy(function ($notification) {
                $data = json_decode($notification->data, true);
                return $data['type'] . '_' . ($data['request_id'] ?? 'general');
            });
            
        // For each group with multiple notifications, keep only the latest and mark others as read
        foreach ($notifications as $group) {
            if ($group->count() > 1) {
                $latest = $group->sortByDesc('created_at')->first();
                $group->except($latest->id)->each(function ($notification) {
                    $notification->update(['is_read' => true]);
                });
            }
        }
    }
    
    /**
     * Send smart notifications that respect user preferences and avoid spam
     */
    public function sendSmartNotification(User $user, $title, $message, $type, $data = [])
    {
        // Get user preferences
        $preferences = $user->notification_preferences ?? [];
        
        // Check if user wants this type of notification
        $notificationType = $data['type'] ?? 'general';
        if (!$this->shouldSendNotificationType($notificationType, $preferences)) {
            return null;
        }
        
        // Create notification
        $notification = Notification::create([
            'user_id' => $user->id,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'data' => json_encode($data),
            'is_read' => false,
        ]);
        
        // Send via external channels only for high priority notifications
        $priority = $data['priority'] ?? self::PRIORITY_MEDIUM;
        if ($priority === self::PRIORITY_HIGH) {
            $this->sendNotificationChannels($user, $title, $message);
        }
        
        return $notification;
    }
    
    /**
     * Check if user wants a specific type of notification
     */
    private function shouldSendNotificationType($type, $preferences)
    {
        $typeSettings = [
            'status_change' => $preferences['status_notifications'] ?? true,
            'assignment' => $preferences['assignment_notifications'] ?? true,
            'payment' => $preferences['payment_notifications'] ?? true,
            'welcome' => $preferences['welcome_notifications'] ?? true,
            'reminder' => $preferences['reminder_notifications'] ?? false,
        ];
        
        return $typeSettings[$type] ?? true;
    }

    /**
     * Helper method to send notifications through all enabled channels.
     */
    private function sendNotificationChannels(User $user, $title, $message, CitizenRequest $request = null)
    {
        $preferences = $user->notification_preferences ?? [];
        
        // Envoyer SMS si activé
        if ($user->phone && ($preferences['sms_enabled'] ?? true)) {
            $this->sendSMSNotification($user->phone, $title, $message);
        }

        // Envoyer email si activé
        if ($user->email && ($preferences['email_enabled'] ?? true)) {
            $this->sendEmailNotification($user, $title, $message, $request);
        }
    }
}
