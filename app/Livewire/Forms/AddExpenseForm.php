<?php

namespace App\Livewire\Forms;

use App\Models\Transaction;
use Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Form;
use App\Models\Account;

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
            'amount' => ['required', 'numeric',function($attr,$value, $fail) {
                $account = Account::find($this->accountName)->where('user_id',Auth::user()->id)->first();
                $currentBalance = $account?->balance??0;

                if($currentBalance < $value){
                    $fail('Insufficient balance in the selected account.');

                }
            }],
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
                'from_account_id' => $this->accountName,
                'category_id' => $this->expenseCategory,
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
