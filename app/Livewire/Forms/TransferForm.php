<?php

namespace App\Livewire\Forms;

use App\Models\Account;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Form;

class TransferForm extends Form
{

    public ?Transaction $transaction;

    #[Validate(['required', 'numeric'])]
    public float $amount = 0;


    #[Validate(['required', 'numeric'])]
    public ?int $from_account_id;


    #[Validate(['required', 'numeric'])]
    public ?int $to_account_id;


    #[Validate(['nullable', 'string'])]
    public ?string $note = "";


    public function setTransfer(Transaction $transaction)
    {
        $this->from_account_id = $transaction->from_account_id;
        $this->to_account_id = $transaction->to_account_id;
        $this->note = $transaction->note;
        $this->amount = $transaction->amount;

        $this->transaction = $transaction;
    }


    public function save()
    {
        $this->validate();



        if ($this->from_account_id === $this->to_account_id) {
            $this->addError("from_account_id", "Both are same account");
            return false;
        }

        //amount
        $balance = Account::where("id", $this->from_account_id)
            ->where("user_id", Auth::user()->id)
            ->first()?->balance ?? 0;


        if ($balance < $this->amount) {
            $this->addError("amount", "Insufficient balance");
            return false;
        }


        try {
            DB::transaction(function () {
                //save to database
                Account::where("id", $this->from_account_id)
                    ->where("user_id", Auth::user()->id)
                    ->decrement("balance", $this->amount);
                Account::where("id", $this->to_account_id)
                    ->where("user_id", Auth::user()->id)
                    ->increment("balance", $this->amount);

                Transaction::create([
                    'amount' => $this->amount,
                    "date" => Carbon::now()->format("Y-m-d"),
                    'type' => "transfer",
                    'from_account_id' => $this->from_account_id,
                    'to_account_id' => $this->to_account_id,
                    'user_id' => Auth::user()->id,
                    "note" => $this->note
                ]);
            });
        } catch (\Throwable $e) {
            throw $e;
        }

        $this->reset([
            'amount',
            'form_account_id',
            'to_account_id',
            'note'
        ]);

        return true;
    }

    public function update()
    {

        $this->validate();

        if ($this->from_account_id === $this->to_account_id) {
            $this->addError("from_account_id", "Both are same account");
            return false;
        }
        $balance = 0;
        if ($this->from_account_id == $this->transaction->from_account_id) {
            $balance = Account::where("id", $this->from_account_id)
                ->where("user_id", Auth::user()->id)
                ->first()?->balance ?? 0;

            $balance += $this->transaction->amount;
        } else {
            $balance = Account::where("id", $this->from_account_id)
                ->where("user_id", Auth::user()->id)
                ->first()?->balance ?? 0;
        }

        if ($balance < $this->amount) {
            $this->addError("amount", "Insufficient balance");
            return false;
        }

        try {
            DB::transaction(function () {
                //save to database
                Account::where("id", $this->transaction->from_account_id)
                    ->where("user_id", Auth::user()->id)
                    ->increment("balance", $this->transaction->amount);

                Account::where("id", $this->transaction->to_account_id)
                    ->where("user_id", Auth::user()->id)
                    ->decrement("balance", $this->transaction->amount);

                Account::where("id", $this->from_account_id)
                    ->where("user_id", Auth::user()->id)
                    ->decrement("balance", $this->amount);

                Account::where("id", $this->to_account_id)
                    ->where("user_id", Auth::user()->id)
                    ->increment("balance", $this->amount);

                $this->transaction->update([
                    'amount' => $this->amount,
                    "date" => Carbon::now()->format("Y-m-d"),
                    'type' => "transfer",
                    'to_account_id' => $this->to_account_id,
                    'from_account_id' => $this->from_account_id,
                    'user_id' => Auth::user()->id,
                    "note" => $this->note
                ]);
            });
        } catch (\Throwable $e) {
            throw $e;
        }

        return true;
    }
}
