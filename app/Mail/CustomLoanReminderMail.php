<?php

namespace App\Mail;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomLoanReminderMail extends Mailable
{
    use Queueable, SerializesModels;


    public function __construct(
        public string $recipientName,
        public string $customMessage,
        public Transaction $transaction,
        public string $role = 'manual'
    ) {
    }

    public function build()
    {
        return $this->subject('Loan Reminder Notification')
        ->view('emails.custom-loan-reminder');
    }
}
