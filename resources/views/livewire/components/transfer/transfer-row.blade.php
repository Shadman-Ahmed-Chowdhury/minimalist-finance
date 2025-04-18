<?php

use function Livewire\Volt\{state, on, computed, mount};

state(['transfer']);

on(function () {
    return ['transferUpdated' . $this->transfer->id => $this->transfer->refresh()];
});

?>


<tr key="{{ $transfer->id }}">
    <td class="px-4 py-3">
        {{ $transfer->date->format('Y-m-d') }}
    </td>
    <td class="px-4 py-3">
        {{ $transfer->fromAccount?->name }}
    </td>
    <td class="px-4 py-3">
        {{ $transfer->toAccount?->name }}
    </td>
    <td class="px-4 py-3">${{ number_format($transfer->amount, 2) }}</td>
    <td class="px-4 py-3">
        {{ $transfer->note }}
    </td>
    <td class="px-4 py-3">

        <livewire:components.transfer.edit-transfer :transfer="$transfer" />

        <button title="Delete" wire:confirm="Are you sure to delete it?"
            wire:click="$parent.deleteIncome({{ $transfer->id }})" class="px-1 py-1 text-red-500">
            <i class="ri-delete-bin-6-line"></i>Delete
        </button>
    </td>
</tr>
