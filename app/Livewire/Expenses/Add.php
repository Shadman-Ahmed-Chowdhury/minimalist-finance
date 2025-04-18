<?php

namespace App\Livewire\Expenses;

use App\Models\Account;
use App\Models\Category;
use Livewire\Component;
use App\Livewire\Forms;
use App\Livewire\Forms\AddExpenseForm;
use Masmerise\Toaster\Toast;
use Masmerise\Toaster\Toaster;

class Add extends Component
{


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

        Toaster::success('Expense added successfully');

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
