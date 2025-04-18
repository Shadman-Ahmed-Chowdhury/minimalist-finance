<?php

namespace App\Livewire\Forms;

use App\Models\Account;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Form;

class IncomeForm extends Form
{

    public ?Transaction $transaction;

    #[Validate(['required', 'numeric'])]
    public float $amount = 0;


    #[Validate(['required', 'numeric'])]
    public ?int $account_id;

    #[Validate(['required', 'numeric'])]
    public ?int $category_id;


    #[Validate(['nullable', 'string'])]
    public ?string $note = "";


    public function setIncome(Transaction $transaction)
    {
        $this->account_id = $transaction->to_account_id;
        $this->category_id = $transaction->category_id;
        $this->note = $transaction->note;
        $this->amount = $transaction->amount;

        $this->transaction = $transaction;
    }


    public function save()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                //save to database
                Account::where("id", $this->account_id)
                    ->where("user_id", Auth::user()->id)
                    ->increment("balance", $this->amount);

                Transaction::create([
                    'amount' => $this->amount,
                    "date" => Carbon::now()->format("Y-m-d"),
                    'type' => "income",
                    'to_account_id' => $this->account_id,
                    'category_id' => $this->category_id,
                    'user_id' => Auth::user()->id,
                    "note" => $this->note
                ]);
            });
        } catch (\Throwable $e) {
            throw $e;
        }

        $this->reset([
            'amount',
            'account_id',
            'category_id',
            'note'
        ]);
    }

    public function update()
    {

        $this->validate();

        try {
            DB::transaction(function () {
                //save to database
                Account::where("id", $this->account_id)
                    ->where("user_id", Auth::user()->id)
                    ->increment("balance", $this->amount - $this->transaction->amount);

                $this->transaction->update([
                    'amount' => $this->amount,
                    "date" => Carbon::now()->format("Y-m-d"),
                    'type' => "income",
                    'to_account_id' => $this->account_id,
                    'category_id' => $this->category_id,
                    'user_id' => Auth::user()->id,
                    "note" => $this->note
                ]);
            });
        } catch (\Throwable $e) {
            throw $e;
        }
    }
}
