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

    public $showModal = false;
    public AddExpenseForm $form;

    public  $accounts;
    public  $categories;


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

        $this->dispatch('expenseAdded'); // Dispatch an event to notify other components

    }

    public function mount($accounts, $categories)
    {
        $this->form->date = now()->format('Y-m-d'); // Set today's date as default

        $this->accounts = $accounts;
        $this->categories = $categories;
    }

    public function render()
    {
        return view('livewire.expenses.add');
    }
}
