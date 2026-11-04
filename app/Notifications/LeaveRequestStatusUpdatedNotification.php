<?php

namespace App\Notifications;

use App\Models\LeaveRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaveRequestStatusUpdatedNotification extends Notification implements ShouldQueue
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
        $status = strtolower($this->leaveRequest->status);
        $statusLabel = ucfirst($status);
        $processedBy = $status === 'approved'
            ? ($this->leaveRequest->approvedBy?->name ?? 'HR / Admin')
            : ($this->leaveRequest->rejectedBy?->name ?? 'HR / Admin');

        return (new MailMessage)
            ->subject("Leave Request {$statusLabel}")
            ->greeting("Hello, {$notifiable->name}!")
            ->line("Your leave request (#{$this->leaveRequest->id}) has been **{$statusLabel}**.")
            ->line("**Dates:** {$this->leaveRequest->start_date} to {$this->leaveRequest->end_date}")
            ->line("**Reason:** {$this->leaveRequest->reason}")
            ->line("**Processed By:** {$processedBy}")
            ->action('View Leave Requests', route('leave-requests.index'))
            ->line('Thank you for using the HRIS Portal.');
    }
}