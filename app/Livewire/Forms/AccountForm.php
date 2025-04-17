<?php

namespace App\Livewire\Forms;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class AccountForm extends Form
{
    public ?string $name = null;

    public int $initial_balance = 0;

    public ?string $description = null;

    protected $account;


    //rules
    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:50',
            Rule::unique(Account::class,"name")->where(function ($query) {
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


        try{

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
                'type' => 'initial'
            ]);
        });

        $this->reset(['name', 'initial_balance', 'description']);
    }catch(\Throwable $e){
        throw $e;
    }

    }
}