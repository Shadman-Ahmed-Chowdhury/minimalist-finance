<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;
use App\Models\Transaction;
use App\Models\LoanParty;
use Illuminate\Support\Facades\DB;
use App\Models\Account;


class AddLoanForm extends Form
{
    public $amount = '';
    public $accountName = '';
    public $name = '';
    public $type = '';
    public $email = '';
    public $dueDate = '';

    private function determineLoanType(): string
    {
        return $this->type === 'borrower' ? 'given' : 'taken';
    }


    private function determineAccountField(string $loanType): string
    {
        return $loanType === 'given' ? 'from_account_id' : 'to_account_id';
    }

    private function createLoanParty(): LoanParty
    {
        return LoanParty::create([
            'user_id' => auth()->user()->id,
            'name' => $this->name,
            'email' => $this->email,
            'type' => $this->type,
            'due_date' => $this->dueDate,
        ]);
    }

    private function createTransaction(string $loanType, string $fromOrTo, int $loanPartyId): Transaction
    {
        return Transaction::create([
            'user_id' => auth()->user()->id,
            'amount' => $this->amount,
            'date' => now(),
            'type' => 'loan',
            'loan_type' => $loanType,
            'loan_party_id' => $loanPartyId,
            $fromOrTo => $this->accountName,
        ]);
    }

    public function rules()
    {
        return [
            'amount' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) {
                    // Only apply this validation if the type is 'borrower'
                    if ($this->type === 'borrower') {
                        $account = Account::find($this->accountName);

                        if (!$account) {
                            return $fail('The selected account does not exist.');
                        }

                        if ($account->balance < $value) {
                            return $fail('Insufficient balance in the selected account.');
                        }
                    }
                },
            ],
            'accountName' => ['required', 'string'],
            'name' => ['required', 'string'],
            'type' => ['required', 'string'],
            'email' => ['required', 'email'],
            'dueDate' => ['required', 'date'],
        ];
    }


    private function updateAccountBalance(string $loanType): void
    {
        $account = Account::findOrFail($this->accountName);

        if ($loanType === 'given') {
            $account->balance -= $this->amount;
        } elseif ($loanType === 'taken') {
            $account->balance += $this->amount;
        }

        $account->save();
    }



    public function save()
    {
        $this->validate();

        $loanType = $this->determineLoanType();
        $fromOrTo = $this->determineAccountField($loanType);

        try {
            DB::beginTransaction();

            // Create the loan party
            $loanParty = $this->createLoanParty();

            // Create the transaction
            $transaction = $this->createTransaction($loanType, $fromOrTo, $loanParty->id);

            // Update the account balance
            $this->updateAccountBalance($loanType);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
