<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class AppointmentStatusUpdated extends Notification
{
    private $appointment;

    public function __construct($appointment)
    {
        $this->appointment = $appointment;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'appointment_id' => $this->appointment->id,
            'status' => $this->appointment->status,
            'message' => "Your appointment status has been updated to {$this->appointment->status}",
        ]);
    }

    public function toArray($notifiable)
    {
        return [
            'appointment_id' => $this->appointment->id,
            'status' => $this->appointment->status,
            'message' => "Your appointment status has been updated to {$this->appointment->status}",
        ];
    }
}
