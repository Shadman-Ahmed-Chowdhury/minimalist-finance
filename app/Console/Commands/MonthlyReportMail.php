<?php

namespace App\Console\Commands;

use App\Exports\ExpenseExport;
use App\Exports\IncomeExport;
use App\Exports\LoanExport;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class MonthlyReportMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:send-monthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate and email last month\'s reports for loans, expenses, and income';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
           $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

           User::all()->each(function ($user) use ($lastMonthStart, $lastMonthEnd) {
               // Temporarily set Auth user for export queries
               Auth::login($user);

               // Generate reports
               $reports = [
                   'loan' => [
                       'export' => new LoanExport(null, null, null, $lastMonthStart->toDateString(), $lastMonthEnd->toDateString(), null),
                       'fileName' => "loan_report_{$user->id}_{$lastMonthStart->format('Y_m')}.xlsx",
                   ],
                   'expense' => [
                       'export' => new ExpenseExport(null, null, null, $lastMonthStart->toDateString(), $lastMonthEnd->toDateString()),
                       'fileName' => "expense_report_{$user->id}_{$lastMonthStart->format('Y_m')}.xlsx",
                   ],
                   'income' => [
                       'export' => new IncomeExport(null, null, null, $lastMonthStart->toDateString(), $lastMonthEnd->toDateString()),
                       'fileName' => "income_report_{$user->id}_{$lastMonthStart->format('Y_m')}.xlsx",
                   ],
               ];

               $filePaths = [];
               foreach ($reports as $type => $data) {
                   $filePath = "reports/{$data['fileName']}";
                   Excel::store($data['export'], $filePath, 'local');
                   $filePaths[$type] = $filePath;
               }

               // Queue email
               Mail::to($user->email)->queue(new MonthlyReportsMail($user, $filePaths, $lastMonthStart));

               // Logout to reset Auth
               Auth::logout();

               $this->info("Queued reports for user {$user->email}");
           });
       }
    }

