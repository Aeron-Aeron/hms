<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentStatusChanged extends Notification
{
    use Queueable;

    private $appointment;

    public function __construct($appointment)
    {
        $this->appointment = $appointment;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Appointment Status Updated')
            ->line('Your appointment status has been updated to: ' . ucfirst($this->appointment->status))
            ->line('Appointment Details:')
            ->line('Date: ' . $this->appointment->appointment_date->format('M d, Y h:i A'))
            ->line('Doctor: Dr. ' . $this->appointment->doctor->name)
            ->action('View Appointment', url('/appointments/' . $this->appointment->id));
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
