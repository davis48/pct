<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\CitizenRequest;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    /**
     * Send notification when request status changes.
     */
    public function sendStatusChangeNotification(CitizenRequest $request, $oldStatus, $newStatus)
    {
        $user = $request->user;
        
        if (!$user) {
            return;
        }

        $statusMessages = [
            'pending' => 'Votre demande a été reçue et est en attente de traitement.',
            'in_progress' => 'Votre demande est maintenant en cours de traitement par un agent.',
            'approved' => 'Félicitations ! Votre demande a été approuvée.',
            'rejected' => 'Votre demande a été rejetée. Consultez les détails pour plus d\'informations.',
        ];

        $statusTitles = [
            'pending' => 'Demande reçue',
            'in_progress' => 'Traitement en cours',
            'approved' => 'Demande approuvée',
            'rejected' => 'Demande rejetée',
        ];

        $title = $statusTitles[$newStatus] ?? 'Mise à jour de statut';
        $message = $statusMessages[$newStatus] ?? 'Le statut de votre demande a été mis à jour.';

        // Créer la notification en base
        $notification = Notification::create([
            'user_id' => $user->id,
            'title' => $title,
            'message' => $message,
            'type' => $this->getNotificationType($newStatus),
            'data' => json_encode([
                'request_id' => $request->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'document_name' => $request->document->name ?? 'Document non spécifié',
            ]),
            'is_read' => false,
        ]);

        // Envoyer une notification SMS si le numéro de téléphone est disponible
        if ($user->phone) {
            $this->sendSMSNotification($user->phone, $title, $message);
        }

        // Envoyer un email si l'email est disponible
        if ($user->email) {
            $this->sendEmailNotification($user, $title, $message, $request);
        }

        return $notification;
    }

    /**
     * Send notification when request is assigned to an agent.
     */
    public function sendAssignmentNotification(CitizenRequest $request, User $agent)
    {
        $user = $request->user;
        
        if (!$user) {
            return;
        }

        $title = 'Agent assigné à votre demande';
        $message = "Votre demande a été assignée à l'agent {$agent->name}. Le traitement va commencer prochainement.";

        $notification = Notification::create([
            'user_id' => $user->id,
            'title' => $title,
            'message' => $message,
            'type' => 'info',
            'data' => json_encode([
                'request_id' => $request->id,
                'agent_id' => $agent->id,
                'agent_name' => $agent->name,
                'document_name' => $request->document->name ?? 'Document non spécifié',
            ]),
            'is_read' => false,
        ]);

        // Notifier par SMS et email
        if ($user->phone) {
            $this->sendSMSNotification($user->phone, $title, $message);
        }

        if ($user->email) {
            $this->sendEmailNotification($user, $title, $message, $request);
        }

        return $notification;
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
    }

    /**
     * Send SMS notification (placeholder - intégrer avec un service SMS).
     */
    private function sendSMSNotification($phone, $title, $message)
    {
        // Placeholder pour l'intégration SMS
        // Vous pouvez intégrer avec des services comme Twilio, Africa's Talking, etc.
        
        \Log::info("SMS Notification to {$phone}: {$title} - {$message}");
        
        // Exemple d'intégration avec un service SMS :
        /*
        try {
            $smsService = app('sms.service');
            $smsService->send($phone, "[PCT-UVCI] {$title}: {$message}");
        } catch (\Exception $e) {
            \Log::error("Failed to send SMS: " . $e->getMessage());
        }
        */
    }

    /**
     * Send email notification.
     */
    private function sendEmailNotification(User $user, $title, $message, CitizenRequest $request = null)
    {
        try {
            // Placeholder pour l'envoi d'emails
            // Vous pouvez utiliser les Mailable de Laravel
            
            \Log::info("Email Notification to {$user->email}: {$title} - {$message}");
            
            // Exemple d'envoi d'email :
            /*
            Mail::send('emails.notification', [
                'user' => $user,
                'title' => $title,
                'message' => $message,
                'request' => $request,
            ], function ($m) use ($user, $title) {
                $m->from('noreply@pct-uvci.gov.ci', 'PCT-UVCI');
                $m->to($user->email, $user->name);
                $m->subject($title);
            });
            */
        } catch (\Exception $e) {
            \Log::error("Failed to send email: " . $e->getMessage());
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
    }
}
