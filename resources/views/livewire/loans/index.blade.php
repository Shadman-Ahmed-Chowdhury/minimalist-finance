<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\Transaction;
use App\Models\Account;
use App\Models\LoanParty;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Masmerise\Toaster\Toaster;

new class extends Component {
    use WithPagination;

    public $filterType = '';
    public $filterAccount = '';
    public $filterSearch = '';
    public $filterDueDate = '';
    public $filterFromDate = '';
    public $filterToDate = '';

    public $listeners = [
        'loanAdded' => '$refresh',
        'loanDeleted' => '$refresh',
    ];

    public function getTransactionsProperty()
    {
        return Transaction::query()
            ->where('type', 'loan')
            ->when($this->filterType, fn($query) => $query->where('loan_type', $this->filterType))
            ->when(
                $this->filterAccount,
                fn($query) => $query->where(function ($q) {
                    $q->where('from_account_id', $this->filterAccount)->orWhere('to_account_id', $this->filterAccount);
                }),
            )
            ->when(
                $this->filterSearch,
                fn($query) => $query->where(function ($q) {
                    $q->whereHas('loanParty', fn($q2) => $q2->where('name', 'like', '%' . $this->filterSearch . '%'))
                        ->orWhere('amount', 'like', '%' . $this->filterSearch . '%')
                        ->orWhere('note', 'like', '%' . $this->filterSearch . '%');
                }),
            )
            ->when($this->filterFromDate, fn($query) => $query->whereHas('loanParty', fn($q) => $q->whereDate('due_date', '>=', $this->filterFromDate)))
            ->when($this->filterToDate, fn($query) => $query->whereHas('loanParty', fn($q) => $q->whereDate('due_date', '>=', $this->filterToDate)))
            ->with(['loanParty', 'fromAccount', 'toAccount'])
            ->latest()
            ->paginate(10);
    }

    public function deleteTransaction($transactionId)
    {
        $transaction = Transaction::findOrFail($transactionId);

        try {
            DB::beginTransaction();

            // Adjust the account balance based on the loan type
            if ($transaction->loan_type === 'given') {
                // If the loan type is "given", add the amount back to the `from_account`
                $account = Account::findOrFail($transaction->from_account_id);
                $account->balance += $transaction->amount;
                $account->save();
            } elseif ($transaction->loan_type === 'taken') {
                // If the loan type is "taken", deduct the amount from the `to_account`
                $account = Account::findOrFail($transaction->to_account_id);
                $account->balance -= $transaction->amount;
                $account->save();
            }

            // Delete the transaction
            $transaction->delete();

            DB::commit();

            // Dispatch an event to notify the UI
            $this->dispatch('loanDeleted');
            Toaster::success('Loan transaction deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to delete the loan transaction.');
            throw $e;
        }
    }
};
?>

<main class="flex-1 p-8">
    <div class="mx-auto space-y-6">
        <!-- Header Section -->
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-semibold tracking-tight">Loans</h1>
            @livewire('loans.add')
        </div>

        <!-- Filters Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex space-x-4">
                <div class="flex-1">
                    <label for="type" class="block mb-2 text-sm font-medium text-gray-900">Type</label>
                    <select wire:model.live="filterType" id="type"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                        <option value="">All</option>
                        <option value="taken">Taken</option>
                        <option value="given">Given</option>
                    </select>
                </div>
                <div class="flex-1">
                    <label for="account" class="block mb-2 text-sm font-medium text-gray-900">Accounts</label>
                    <select wire:model.live="filterAccount" id="account"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                        <option value="">All</option>
                        @foreach (Account::all() as $account)
                            <option value="{{ $account->id }}">{{ $account->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1">
                    <label for="search" class="block mb-2 text-sm font-medium text-gray-900">Search</label>
                    <input type="text" wire:model.live.debounce.500ms="filterSearch"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                        placeholder="Search by party name, amount, or note">
                </div>
            </div>
            <div class="flex space-x-4">

                <div class="flex-1">
                    <label for="fromDate" class="block mb-2 text-sm font-medium text-gray-900">From DUE Date</label>
                    <input type="date" wire:model.live="filterFromDate"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5" />
                </div>

                <div class="flex-1">
                    <label for="toDate" class="block mb-2 text-sm font-medium text-gray-900">To DUE Date</label>
                    <input type="date" wire:model.live="filterToDate"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5" />
                </div>
            </div>
        </div>

        <!-- Loan Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table wire:loading.class="opacity-50 cursor-wait" class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">Date</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">Amount</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">Party</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">Type</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">Account</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">Due Date</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($this->transactions as $transaction)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    {{ $transaction->date->format('d M Y') }}
                                </td>
                                <td class="px-4 py-3">${{ number_format($transaction->amount, 2) }}</td>
                                <td class="px-4 py-3">{{ $transaction->loanParty->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3">{{ ucfirst($transaction->loan_type) }}</td>
                                <td class="px-4 py-3">
                                    {{ $transaction->loan_type === 'taken'
                                        ? $transaction->toAccount?->name ?? 'N/A'
                                        : $transaction->fromAccount?->name ?? 'N/A' }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ $transaction->loanParty?->due_date->format('d M Y') }}
                                </td>
                                <td class="px-4 py-3">
                                    <button wire:click="deleteTransaction({{ $transaction->id }})"
                                        wire:confirm="Are you sure you want to delete this loan transaction?"
                                        class="text-red-600 hover:text-red-800">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        @if ($this->transactions->isEmpty())
                            <tr>
                                <td colspan="7" class="px-4 py-3 text-center text-gray-500">
                                    No loan transactions found
                                </td>
                            </tr>
                        @endif
                    </tbody>
                    <tfoot class="bg-gray-50 border-t border-gray-200">
                        <tr>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">Total</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">
                                ${{ number_format($this->transactions->sum('amount'), 2) }}</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium" colspan="5"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="p-4">
                {{ $this->transactions->links() }}
            </div>
        </div>
    </div>
</main>
