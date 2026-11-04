<?php

namespace App\Notifications;

use App\Models\LeaveRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaveRequestSubmittedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public LeaveRequest $leaveRequest)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $employeeName = $this->leaveRequest->employee?->fullname ?? 'Employee';

        return (new MailMessage)
            ->subject("New Leave Request Submitted - {$employeeName}")
            ->greeting("Hello, {$notifiable->name}!")
            ->line("{$employeeName} has submitted a new leave request that requires your review.")
            ->line("**Dates:** {$this->leaveRequest->start_date} to {$this->leaveRequest->end_date}")
            ->line("**Reason:** {$this->leaveRequest->reason}")
            ->action('Review Leave Requests', route('leave-requests.index'))
            ->line('Thank you for using the HRIS Portal.');
    }
}