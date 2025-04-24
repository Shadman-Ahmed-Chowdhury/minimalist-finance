<?php

use App\Models\Transaction;
use App\Models\Category;
use App\Models\Account;
use function Livewire\Volt\{state, on, rules};

state([
    'expenseId' => null,
    'isOpen' => false,
    'date' => null,
    'category_id' => null,
    'amount' => null,
    'from_account_id' => null,
    'note' => null,
    'categories',
    'accounts',
]);

// Listen for the open-edit-modal event
on([
    'open-edit-modal' => function ($expenseId) {
        $this->expenseId = $expenseId;
        $expense = Transaction::findOrFail($expenseId);
        $this->date = $expense->date->format('Y-m-d');
        $this->category_id = $expense->category_id;
        $this->amount = $expense->amount;
        $this->from_account_id = $expense->from_account_id;
        $this->note = $expense->note;
        $this->isOpen = true;
    },
]);

// Validation rules
rules([
    'date' => ['required', 'date'],
    'category_id' => ['required', 'exists:categories,id'],
    'amount' => ['required', 'numeric', 'min:0'],
    'from_account_id' => ['required', 'exists:accounts,id'],
    'note' => ['nullable', 'string', 'max:255'],
]);

// Save the edited expense
$save = function () {
    $this->validate();

    try {
        DB::beginTransaction();

        $expense = Transaction::findOrFail($this->expenseId);
        $amountBeforeUpdate = $expense->amount;
        $expense->update([
            'date' => $this->date,
            'category_id' => $this->category_id,
            'amount' => $this->amount,
            'from_account_id' => $this->from_account_id,
            'note' => $this->note,
        ]);

        $amountAfterUpdate = $this->amount;
        $amountDifference = $amountAfterUpdate - $amountBeforeUpdate;

        //check if the amount has changed
        if ($amountDifference != 0) {
            // Update the account balance
            $account = Account::findOrFail($this->from_account_id);
            $account->balance -= $amountDifference; // Deduct the amount difference from the account balance
            $account->save();
        }
        DB::commit();
    } catch (\Exception $e) {
        DB::rollBack();
        session()->flash('error', 'Failed to update expense: ' . $e->getMessage());
    }

    session()->flash('message', 'Expense updated successfully.');
    $this->isOpen = false;
    $this->reset(['expenseId', 'date', 'category_id', 'amount', 'from_account_id', 'note']);
    $this->dispatch('expense-added');
};

// Close the modal
$close = function () {
    $this->isOpen = false;
    $this->reset(['expenseId', 'date', 'category_id', 'amount', 'from_account_id', 'note']);
};

?>

<div>
    @if ($isOpen)
        <div class="fixed inset-0 bg-black/25 bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
                <h2 class="text-lg font-semibold mb-4">Edit Expense</h2>
                <form wire:submit.prevent="save">
                    <div class="space-y-4">
                        <!-- Date -->
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                            <input type="date" wire:model="date" id="date"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                readonly>
                            @error('date')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                            <select wire:model="category_id" id="category_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">

                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Amount -->
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                            <input type="text" wire:model="amount" id="amount" step='0.01'
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            @error('amount')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Account -->
                        <div>
                            <label for="from_account_id" class="block text-sm font-medium text-gray-700">Account</label>
                            <select wire:model="from_account_id" id="from_account_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">

                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                                @endforeach
                            </select>
                            @error('from_account_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Note -->
                        <div>
                            <label for="note" class="block text-sm font-medium text-gray-700">Note</label>
                            <textarea wire:model="note" id="note"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"></textarea>
                            @error('note')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-2">
                        <button type="button" wire:click="close"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Cancel</button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-md hover:bg-primary-700">Save</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
