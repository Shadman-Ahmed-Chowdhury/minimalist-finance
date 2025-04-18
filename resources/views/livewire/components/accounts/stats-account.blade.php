<?php

use function Livewire\Volt\{computed, on};

on(['accountAdded' => '$refresh']);

$accounts = computed(function () {
    return \App\Models\Account::where('user_id', auth()->user()->id)
        ->orderBy('balance', 'desc')
        ->get();
});

?>




<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-semibold text-gray-900">Accounts</h2>

        <livewire-components.accounts.add-account buttonIcon="ri-add-line mr-1" buttonText="Add Account" />

    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @foreach ($this->accounts as $account)
            <livewire-components.accounts.account-card key="{{ $account->id }}" :account="$account" />
        @endforeach
    </div>
</div>
