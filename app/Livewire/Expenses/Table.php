<?php

namespace App\Http\Livewire\Expenses;

use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{
    use WithPagination;

    public $filterCategory = '';
    public $filterAccount = '';
    public $filterSearch = '';

    public function updatingFilterCategory()
    {
        $this->resetPage();
    }

    public function updatingFilterAccount()
    {
        $this->resetPage();
    }

    public function updatingFilterSearch()
    {
        $this->resetPage();
    }

    public function deleteExpense($id)
    {
        Transaction::findOrFail($id)->delete();
        session()->flash('message', 'Expense deleted successfully.');
    }

    #[Computed]
    public function expenses()
    {
        return Transaction::with(['category', 'fromAccount'])
            ->where('type', 'expense') // Filter only expense transactions
            ->when($this->filterCategory, function ($query) {
                $query->where('category_id', $this->filterCategory);
            })
            ->when($this->filterAccount, function ($query) {
                $query->where('from_account_id', $this->filterAccount);
            })
            ->when($this->filterSearch, function ($query) {
                $query->where('note', 'like', '%' . $this->filterSearch . '%')
                    ->orWhere('amount', 'like', '%' . $this->filterSearch . '%');
            })
            ->orderBy('date', 'desc')
            ->paginate(10);
    }

    #[Computed]
    public function categories()
    {
        return Category::where('user_id', auth()->id())->where('type', 'expense')->get();
    }

    #[Computed]
    public function accounts()
    {
        return Account::where('user_id', auth()->id())->get();
    }

    public function mount()
    {

        dd($this->categories);
        $this->filterCategory = '';
        $this->filterAccount = '';
        $this->filterSearch = '';
    }

    public function render()
    {
        dd($this->categories);
        return view('livewire.expenses.table');
    }


}
