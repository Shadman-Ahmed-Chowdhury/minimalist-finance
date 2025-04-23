<?php

namespace App\View\Components;

use App\Models\Account;
use App\Models\Transaction;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DashboardStats extends Component
{
    public $stats;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $loan = Transaction::where("user_id", auth()->user()->id)
            ->loan()->get();
        $this->stats = [
            "totalIncome" => Account::where("user_id", auth()->user()->id)->sum("balance"),
            "monthlyExpense" => Transaction::where("user_id", auth()->user()->id)
                ->expense()
                ->monthly()
                ->sum("amount"),
            "monthlyIncome" => Transaction::where("user_id", auth()->user()->id)
                ->income()
                ->monthly()
                ->sum("amount"),
            "totalLoanTaken" => $loan
                ->where('loan_type', 'taken')
                ->sum("amount"),
            "totalLoanGiven" => $loan
                ->where('loan_type', 'given')
                ->sum("amount"),
        ];
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.dashboard-stats');
    }
}
