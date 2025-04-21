<?php

namespace App\Http\Livewire\Expenses;

use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use Livewire\Attributes\Computed;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public $filterCategory = '';
    public $filterAccount = '';
    public $filterSearch = '';

    public $listeners = [
        'expenseAdded' => '$refresh',
    ];

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
            ->latest()
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
        $this->filterCategory = '';
        $this->filterAccount = '';
        $this->filterSearch = '';
    }

    public function edit($id)
    {
        $this->dispatch('open-edit-modal', expenseId: $id);
    }


}
?>


<main class="flex-1 p-8">
    <div class="mx-auto space-y-6">
        <!-- Header Section -->
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-semibold tracking-tight">Expenses</h1>
            <livewire:expenses.add />
        </div>

        <!-- Filters Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex space-x-4">
                <div class="flex-1">
                    <label for="category" class="block mb-2 text-sm font-medium text-gray-900">Category</label>
                    <select wire:model.live="filterCategory" id="category"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                        <option value="">All</option>

                        @foreach ($this->categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                       @endforeach


                    </select>
                </div>
                <div class="flex-1">
                    <label for="account" class="block mb-2 text-sm font-medium text-gray-900">Accounts</label>
                    <select wire:model.live="filterAccount" id="account"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                        <option value="">All</option>
                        @foreach ($this->accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1">
                    <label for="search" class="block mb-2 text-sm font-medium text-gray-900">Search</label>
                    <input type="text" wire:model.live.debounce.500ms="filterSearch"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                        placeholder="Search by note or amount">
                </div>
            </div>
        </div>


        <!-- Expense Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table wire:loading.class="opacity-50 cursor-wait" class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">Date</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">Category</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">Amount</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">Account</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">Note</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                        @if ($this->expenses->isEmpty())
                            <tr>
                                <td colspan="6" class="text-center px-4 py-3 text-gray-500">
                                    No expenses found.
                                </td>
                            </tr>
                        @endif

                        @foreach ($this->expenses as $expense)
                            <tr class="border-b border-gray-200">
                                <td class="px-4 py-3 text-gray-700">{{ $expense->date->format('Y-m-d') }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $expense->category?->name }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ number_format($expense->amount, 2) }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $expense->fromAccount?->name }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $expense->note }}</td>
                                <td class="px-4 py-3 text-gray-700">
                                    <button wire:click="edit({{ $expense->id }})" class="text-blue-500 hover:text-blue-700">
                                        Edit
                                    </button>

                                    <button wire:click="delete({{ $expense->id }})" class="text-red-500 hover:text-red-700 ml-2">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach


                    </tbody>
                </table>
            </div>
            <div class="p-4">
                {{ $this->expenses->links() }}
            </div>
        </div>

        <livewire:expenses.edit />
    </div>

</main>
