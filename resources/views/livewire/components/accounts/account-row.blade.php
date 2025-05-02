<?php

use function Livewire\Volt\{state, on};

state(['account']);

on([
    'accountUpdated{account.id}' => function () {
        $this->account = $this->account->fresh();
    },
]);

?>


<tr key="{{ $account->id }}">
    <td class="px-4 py-3">
        {{ $account->name }}
    </td>

    <td class="px-4 py-3">${{ number_format($account->balance, 2) }}</td>
    <td class="px-4 py-3">
        <livewire:components.accounts.edit-account :account="$account" />
        <button title="Delete" wire:confirm="Are you sure to delete it?"
            wire:click="$parent.deleteAccount({{ $account->id }})" class="px-1 py-1 text-red-500">
            <i class="ri-delete-bin-6-line"></i>Delete
        </button>
    </td>
</tr>
