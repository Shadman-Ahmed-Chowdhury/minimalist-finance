<?php

use Livewire\Volt\Component;
use App\Models\Transaction;
use App\Models\Account;
use Illuminate\Support\Facades\DB;
use function Livewire\Volt\{state, on};

state([
    'expenseId' => null,
    'isOpen' => false,
]);

// Listen for the open-delete-modal event
on([
    'open-delete-modal' => function ($expenseId) {
        $this->expenseId = $expenseId;
        $this->isOpen = true;
    },
]);

// Delete the expense
$delete = function () {
    $expense = Transaction::findOrFail($this->expenseId);

    try {
        DB::beginTransaction();

        // Update account balance
        $account = Account::findOrFail($expense->from_account_id);
        $account->balance += $expense->amount; // Add the expense amount back to the account
        $account->save();

        // Delete the expense
        $expense->delete();

        DB::commit();
        session()->flash('message', 'Expense deleted successfully.');
        $this->isOpen = false;
        $this->reset(['expenseId']);
        $this->dispatch('expense-deleted');
    } catch (\Exception $e) {
        DB::rollBack();
        session()->flash('error', 'Failed to delete expense: ' . $e->getMessage());
    }
};

// Close the modal
$close = function () {
    $this->isOpen = false;
    $this->reset(['expenseId']);
};

?>

<div>
    @if ($isOpen)
        <div class="fixed inset-0 bg-black/25 bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
                <h2 class="text-lg font-semibold mb-4">Delete Expense</h2>
                <p class="text-gray-600 mb-6">Are you sure you want to delete this expense? This action cannot be undone.
                </p>
                <div class="flex justify-end space-x-2">
                    <button type="button" wire:click="close"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Cancel</button>
                    <button type="button" wire:click="delete"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">Delete</button>
                </div>
            </div>
        </div>
    @endif
</div>
