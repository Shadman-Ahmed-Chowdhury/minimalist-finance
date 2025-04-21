<?php

use function Livewire\Volt\{computed, uses, on, state, updated};

use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;
use App\Models\Account;

on(['accountAdded' => '$refresh', 'accountRemoved' => '$refresh']);

uses(WithPagination::class);

state([
    'filterSearch' => '',
]);

updated([
    'filterSearch' => function () {
        $this->resetPage();
    },
]);

$accounts = computed(function () {
    return Account::where('user_id', auth()->user()->id)
        ->latest()
        ->when($this->filterSearch, function ($query) {
            return $query->where('name', 'like', '%' . $this->filterSearch . '%');
        })
        ->paginate(10);
});

$deleteAccount = function ($id) {
    $account = Account::where('user_id', auth()->user()->id)
        ->where('id', $id)
        ->first();

    if (!$account) {
        Toaster::error('Account not found');
        return;
    }

    $account->delete();
    Toaster::success('Account deleted successfully');
    $this->dispatch('accountRemoved');
};

?>
<main class="flex-1 p-8">
    <div class="mmx-auto space-y-6">
        <!-- Header Section -->
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-semibold tracking-tight">Account</h1>

            <livewire:components.accounts.add-account
                class="bg-primary-500 hover:bg-primary-600 px-5 py-2 rounded text-white hover:text-white" />

        </div>

        <!-- Filters Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex space-x-4">
                <div class="flex-1">
                    <label for="search" class="block mb-2 text-sm font-medium text-gray-900">Account Name</label>
                    <input type="text" wire:model.live.debounce.500ms="filterSearch"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                        placeholder="Search by account name">
                </div>
            </div>
        </div>

        <!-- Account Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table wire:loading.class="opacity-50 cursor-wait" class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">Account Name</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">Balance</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">Action</th>
                        </tr>
                    </thead>
                    <tbody id="accountTableBody">
                        @foreach ($this->accounts as $account)
                            <tr key="{{ $account->id }}">
                                <td class="px-4 py-3">
                                    {{ $account->name }}
                                </td>

                                <td class="px-4 py-3">${{ number_format($account->balance, 2) }}</td>
                                <td class="px-4 py-3">

                                    <button title="Delete" wire:confirm="Are you sure to delete it?"
                                        wire:click="deleteAccount({{ $account->id }})" class="px-1 py-1 text-red-500">
                                        <i class="ri-delete-bin-6-line"></i>Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4">
                {{ $this->accounts->links() }}
            </div>
        </div>
    </div>
</main>
