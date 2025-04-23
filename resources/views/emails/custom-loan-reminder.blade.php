<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Reminder Notification</title>
    <style>
        body { margin: 0; padding: 0; background-color: #f4f4f9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; }
        a { color: #3b82f6; text-decoration: none; }
        a:hover { text-decoration: underline; }
        @media only screen and (max-width: 600px) {
            .container { width: 100% !important; max-width: 100% !important; }
            .header, .content, .footer { padding: 15px !important; }
        }
    </style>
</head>
<body style="background-color: #f4f4f9; margin: 0; padding: 0;">
<table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
    <tr>
        <td align="center" style="padding: 20px 0;">
            <table role="presentation" class="container" cellpadding="0" cellspacing="0" width="600" style="max-width: 600px; width: 100%; background-color: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
                <tr>
                    <td class="header" style="background: linear-gradient(90deg, #3b82f6, #60a5fa); padding: 20px; text-align: center; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                        <img src="https://via.placeholder.com/150x50?text=Your+Logo" alt="Company Logo" style="max-width: 150px; height: auto; display: block; margin: 0 auto 10px;">
                        <h1 style="font-size: 24px; font-weight: 700; color: #ffffff; margin: 0;">Loan Reminder</h1>
                    </td>
                </tr>
                <tr>
                    <td class="content" style="padding: 30px;">
                        <p style="font-size: 16px; color: #1f2937; margin: 0 0 16px;">Dear {{ $recipientName }},</p>
                        <p style="font-size: 16px; color: #1f2937; line-height: 1.6; margin: 0 0 20px;">
                            @if ($role === 'borrower')
                                This is a reminder to repay your loan due in two days.
                            @elseif ($role === 'user')
                                This is a reminder to repay your loan to {{ $transaction->loanParty?->name ?? 'N/A' }} due in two days.
                            @elseif ($role === 'lender')
                                This is a reminder that {{ $transaction->user?->name ?? 'the borrower' }}’s loan repayment is due in two days.
                            @else
                                {{ $customMessage }}
                            @endif
                        </p>
                        <h2 style="font-size: 20px; font-weight: 600; color: #3b82f6; margin: 0 0 16px;">Loan Details</h2>
                        <table role="presentation" cellpadding="0" cellspacing="0" style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="padding: 8px 0; font-size: 15px; color: #1f2937;"><strong>Amount:</strong></td>
                                <td style="padding: 8px 0; font-size: 15px; color: #1f2937;">${{ number_format($transaction->amount, 2) }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; font-size: 15px; color: #1f2937;"><strong>Due Date:</strong></td>
                                <td style="padding: 8px 0; font-size: 15px; color: #1f2937;">{{ $transaction->loanParty?->due_date->format('d M Y') ?? 'N/A' }}</td>
                            </tr>
                        </table>
                        <p style="font-size: 16px; color: #1f2937; margin: 20px 0 0;">Thank you,</p>
                        <p style="font-size: 16px; color: #1f2937; margin: 4px 0;">Your Finance Team</p>
                    </td>
                </tr>
                <tr>
                    <td class="footer" style="background-color: #f3f4f6; padding: 20px; text-align: center; border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">
                        <p style="font-size: 14px; color: #6b7280; margin: 0 0 10px;">© {{ date('Y') }} Your Company. All rights reserved.</p>
                        <p style="font-size: 12px; color: #6b7280; margin: 10px 0 0;">
                            <a href="#" style="color: #6b7280;">Unsubscribe</a> | <a href="#" style="color: #6b7280;">Contact Us</a>
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
