<!DOCTYPE html>
   <html>
   <head>
       <meta charset="UTF-8">
       <title>Monthly Financial Reports</title>
   </head>
   <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
       <h2>Monthly Financial Reports - {{ $month->format('F Y') }}</h2>
       <p>Dear {{ $user->name }},</p>
       <p>Attached are your financial reports for {{ $month->format('F Y') }}:</p>
       <ul>
           <li>Loan Report: Details of all loan transactions.</li>
           <li>Expense Report: Summary of all expenses, including categories.</li>
           <li>Income Report: Summary of all income, including categories.</li>
       </ul>
       <p>Thank you for using Minimalist Finance!</p>
       <p>Best regards,<br>Minimalist Finance Team</p>
   </body>
   </html>
