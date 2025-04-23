<?php

namespace App\Console\Commands;

use App\Mail\CustomLoanReminderMail;
use App\Models\Transaction;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class SendLoanReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loans:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send automated loan reminder emails two days before due date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $targetDate = Carbon::today()->addDays(2)->toDateString();

        $transactions = Transaction::with(['loanParty', 'user'])
            ->whereHas('loanParty', fn ($query) => $query->whereDate('due_date', $targetDate))
            ->whereIn('loan_type', ['given', 'taken'])
            ->get();

        foreach ($transactions as $transaction) {
            if ($transaction->loan_type === 'given' && $transaction->loanParty?->email) {
                Mail::to($transaction->loanParty->email)->queue(new CustomLoanReminderMail(
                    recipientName: $transaction->loanParty->name,
                    customMessage: "Please repay your loan of $" . number_format($transaction->amount, 2) . " due on " . $transaction->loanParty->due_date->format('d M Y') . ".",
                    transaction: $transaction,
                    role: 'borrower'
                ));
                $this->info("Queued reminder for borrower: {$transaction->loanParty->email}");
            } elseif ($transaction->loan_type === 'taken') {
                if ($transaction->user?->email) {
                    Mail::to($transaction->user->email)->queue(new CustomLoanReminderMail(
                        recipientName: $transaction->user->name,
                        customMessage: "Please repay your loan of $" . number_format($transaction->amount, 2) . " to {$transaction->loanParty->name} due on " . $transaction->loanParty->due_date->format('d M Y') . ".",
                        transaction: $transaction,
                        role: 'user'
                    ));
                    $this->info("Queued reminder for user: {$transaction->user->email}");
                }
                if ($transaction->loanParty?->email) {
                    Mail::to($transaction->loanParty->email)->queue(new CustomLoanReminderMail(
                        recipientName: $transaction->loanParty->name,
                        customMessage: "Reminder: {$transaction->user->name}’s loan repayment of $" . number_format($transaction->amount, 2) . " is due on " . $transaction->loanParty->due_date->format('d M Y') . ".",
                        transaction: $transaction,
                        role: 'lender'
                    ));
                    $this->info("Queued reminder for lender: {$transaction->loanParty->email}");
                }
            }
        }

        $this->info('Loan reminders processed successfully.');
    }
}
