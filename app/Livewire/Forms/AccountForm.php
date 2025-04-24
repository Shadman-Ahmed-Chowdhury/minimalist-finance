<?php

namespace App\Livewire\Forms;

use App\Models\Account;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class AccountForm extends Form
{
    public ?string $name = null;

    public int $initial_balance = 0;

    public ?string $description = null;

    public $account;

    public function setAccount(Account $account)
    {
        $this->account = $account;

        $this->name = $account->name;
        $this->description = $account->description;
    }


    //rules
    protected function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique(Account::class, "name")->where(function ($query) {
                    return $query->where('user_id', auth()->user()->id);
                })->ignore($this->account?->id)
            ],
            'initial_balance' => ['required', 'numeric'],
            'description' => ['nullable', 'string'],
        ];
    }




    public function save(): void
    {
        $this->validate();


        try {

            DB::transaction(function () {
                $account = Account::create([
                    'name' => $this->name,
                    'balance' => $this->initial_balance,
                    'description' => $this->description,
                    'user_id' => auth()->user()->id
                ]);

                Transaction::create([
                    'user_id' => auth()->user()->id,
                    'amount' => $this->initial_balance,
                    'type' => 'initial',
                    'to_account_id' => $account->id,
                    'date' => Carbon::now()->format('Y-m-d'),
                ]);
            });

            $this->reset(['name', 'initial_balance', 'description']);
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function update()
    {
        $this->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique(Account::class, "name")->where(function ($query) {
                    return $query->where('user_id', auth()->user()->id);
                })->ignore($this->account?->id)
            ],
            'description' => ['nullable', 'string']
        ]);

        $this->account->update([
            'name' => $this->name,
            'description' => $this->description,
        ]);
    }
}
