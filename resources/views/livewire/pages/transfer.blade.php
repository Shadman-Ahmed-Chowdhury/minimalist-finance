<?php

use function Livewire\Volt\{computed, uses, on, state, updated};

use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;
use Carbon\Carbon;

on(['transferAdded' => '$refresh', 'transferRemoved' => '$refresh']);

uses(WithPagination::class);

state([
    'filterToAccount' => '',
    'filterFromAccount' => '',
    'filterFromDate' => Carbon::now()->subDays(30)->format('Y-m-d'),
    'filterToDate' => Carbon::now()->format('Y-m-d'),
]);

updated([
    'filterToAccount' => function () {
        $this->resetPage();
    },
    'filterFromAccount' => function () {
        $this->resetPage();
    },
]);

$transfers = computed(function () {
    return App\Models\Transaction::transfer()
        ->where('user_id', auth()->user()->id)
        ->latest()
        ->when($this->filterToAccount, function ($query) {
            return $query->where('to_account_id', $this->filterToAccount);
        })
        ->when($this->filterFromAccount, function ($query) {
            return $query->where('from_account_id', $this->filterFromAccount);
        })
        ->when($this->filterFromDate, function ($query) {
            return $query->whereDate('date', '>=', $this->filterFromDate);
        })
        ->when($this->filterToDate, function ($query) {
            return $query->whereDate('date', '<=', $this->filterToDate);
        })
        ->with(['fromAccount', 'toAccount'])
        ->paginate(10);
});

$accounts = computed(function () {
    return App\Models\Account::where('user_id', auth()->user()->id)
        ->select('id', 'name')
        ->orderBy('name', 'asc')
        ->get();
});

$deleteIncome = function ($id) {
    $transaction = App\Models\Transaction::where('user_id', auth()->user()->id)
        ->where('id', $id)
        ->first();

    if (!$transaction) {
        Toaster::error('Transaction not found');
        return;
    }

    //decrement the account balance

    $account = App\Models\Account::where('user_id', auth()->user()->id)
        ->where('id', $transaction->to_account_id)
        ->decrement('balance', $transaction->amount);

    $account = App\Models\Account::where('user_id', auth()->user()->id)
        ->where('id', $transaction->from_account_id)
        ->increment('balance', $transaction->amount);

    $transaction->delete();
    Toaster::success('Transaction deleted successfully');
    $this->dispatch('transferRemoved');
};

?>
<main class="flex-1 p-8">
    <div class="mmx-auto space-y-6">
        <!-- Header Section -->
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-semibold tracking-tight">Transfer</h1>

            <livewire:components.transfer.add-transfer />

        </div>

        <!-- Filters Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex space-x-4">
                <div class="flex-1">
                    <label for="category" class="block mb-2 text-sm font-medium text-gray-900">From Accounts</label>
                    <select wire:model.live="filterFromAccount" id="category"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                        <option value="">All</option>
                        @foreach ($this->accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1">
                    <label for="category" class="block mb-2 text-sm font-medium text-gray-900">To Accounts</label>
                    <select wire:model.live="filterToAccount" id="category"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                        <option value="">All</option>
                        @foreach ($this->accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex space-x-4">

                <div class="flex-1">
                    <label for="fromDate" class="block mb-2 text-sm font-medium text-gray-900">From Date</label>
                    <input type="date" wire:model.live="filterFromDate"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5" />
                </div>

                <div class="flex-1">
                    <label for="toDate" class="block mb-2 text-sm font-medium text-gray-900">To Date</label>
                    <input type="date" wire:model.live="filterToDate"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5" />
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
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">From Account</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">To Account</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">Amount</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">Note</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">Action</th>
                        </tr>
                    </thead>
                    <tbody id="incomeTableBody">
                        @foreach ($this->transfers as $transfer)
                            <livewire:components.transfer.transfer-row key="{{ $transfer->id }}" :transfer="$transfer" />
                        @endforeach
                        @if ($this->transfers->isEmpty())
                            <tr>
                                <td colspan="6" class="px-4 py-3 text-center text-gray-500">
                                    No loan transactions found
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="p-4">
                {{ $this->transfers->links() }}
            </div>
        </div>
    </div>
</main>
