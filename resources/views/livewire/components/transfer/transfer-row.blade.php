<?php

use function Livewire\Volt\{state, on, computed, mount};

state(['transfer', 'accounts']);

on([
    'transferUpdated{transfer.id}' => function () {
        $this->transfer->refresh();
    },
]);

?>


<tr key="{{ $transfer->id }}">
    <td class="px-4 py-3">
        {{ $transfer->date->format('d M Y') }}
    </td>
    <td class="px-4 py-3">
        {{ $transfer->fromAccount?->name }}
    </td>
    <td class="px-4 py-3">
        {{ $transfer->toAccount?->name }}
    </td>
    <td class="px-4 py-3">${{ number_format($transfer->amount, 2) }}</td>
    <td class="px-4 py-3">

        {{ strlen($transfer->note) > 40 ? substr($transfer->note, 0, 40) . '...' : $transfer->note }}
    </td>
    <td class="px-4 py-3">

        <livewire:components.transfer.edit-transfer :transfer="$transfer" :accounts="$accounts" />

        <button title="Delete" wire:confirm="Are you sure to delete it?"
            wire:click="$parent.deleteIncome({{ $transfer->id }})" class="px-1 py-1 text-red-500">
            <i class="ri-delete-bin-6-line"></i>Delete
        </button>
    </td>
</tr>
