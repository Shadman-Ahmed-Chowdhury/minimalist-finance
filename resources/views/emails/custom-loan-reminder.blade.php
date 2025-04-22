<!DOCTYPE html>
<html>
<head>
    <title>Loan Reminder Notification</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
<div style="max-width: 600px; margin: 0 auto; padding: 20px;">
    <h2>Loan Reminder</h2>
    <p>Dear {{ $recipientName }},</p>
    <p>{{ $customMessage }}</p>
    <h3>Loan Details</h3>
    <ul>
        <li><strong>Amount:</strong> ${{ number_format($transaction->amount, 2) }}</li>
        <li><strong>Type:</strong> {{ ucfirst($transaction->loan_type) }}</li>
        <li><strong>Date:</strong> {{ $transaction->date->format('d M Y') }}</li>
        <li><strong>Due Date:</strong> {{ $transaction->loanParty ? $transaction->loanParty->due_date->format('d M Y') : 'N/A' }}</li>
        <li><strong>Account:</strong> {{ $transaction->loan_type === 'taken' ? ($transaction->toAccount->name ?? 'N/A') : ($transaction->fromAccount->name ?? 'N/A') }}</li>
    </ul>
    <p>Thank you,</p>
    <p>Your Finance Team</p>
</div>
</body>
</html>
