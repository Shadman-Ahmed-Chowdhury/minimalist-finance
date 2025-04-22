<?php

namespace App\Mail;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomLoanReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $recipientName;
    public $customMessage;
    public $transaction;

    public function __construct($recipientName, $customMessage, Transaction $transaction)
    {
        $this->recipientName = $recipientName;
        $this->customMessage = $customMessage;
        $this->transaction = $transaction;
    }

    public function build()
    {
        return $this->subject('Loan Reminder Notification')
            ->view('emails.custom-loan-reminder');
    }
}
