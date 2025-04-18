<?php

namespace App\Livewire\Forms;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Form;

class AddExpenseForm extends Form
{

    public $accountName='';
    public $amount='';
    public $date='';
    public $note='';
    public $expenseCategory='';

    public function rules()
    {
        return [
            'accountName' => ['required', 'string', 'max:50'],
            'amount' => ['required', 'numeric'],
            'date' => ['required', 'date'],
            'note' => ['nullable', 'string'],
            'expenseCategory' => ['required', 'string'],
        ];
    }

    public function save()
    {
        $this->validate();


        try{
            // Start a transaction
            DB::beginTransaction();

            // Create the transaction
            $transaction = Transaction::create([
                'user_id' => auth()->user()->id,
                'amount' => $this->amount,
                'date' => $this->date,
                'note' => $this->note,
                'category' => $this->expenseCategory,
                'type' => 'expense'
            ]);

            // Commit the transaction
            DB::commit();
        } catch (\Exception $e) {
            // Rollback the transaction if something goes wrong
            DB::rollBack();
            throw $e;
        }



    }
}
