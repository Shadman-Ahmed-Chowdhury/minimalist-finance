<?php

namespace App\Livewire\Expenses;

use App\Models\Account;
use App\Models\Category;
use Livewire\Attributes\Computed;
use Livewire\Component;
use App\Livewire\Forms;
use App\Livewire\Forms\AddExpenseForm;
use Masmerise\Toaster\Toast;
use Masmerise\Toaster\Toaster;

class Add extends Component
{

public $showModal=false;
    public AddExpenseForm $form;

    #[Computed]
    public function accounts()
    {
        return Account::where('user_id', auth()->user()->id)->get();
    }

    #[Computed]
    public function categories()
    {
        return Category::where('user_id', auth()->user()->id)->get();
    }

    public function save()
    {
        $this->form->save();

        // Find the selected account
        $account = Account::find($this->form->accountName);

        if ($account) {
        // Deduct the expense amount from the account balance
            $account->balance -= $this->form->amount;
            $account->save();
        }

        // Show success message
        Toaster::success('Expense added successfully and balance updated.');

        // Reset the form
        $this->reset();

    }

    public function mount()
    {
        $this->form->date = now()->toDateString(); // Set today's date as default
    }

    public function render()
    {
        return view('livewire.expenses.add');
    }
}
