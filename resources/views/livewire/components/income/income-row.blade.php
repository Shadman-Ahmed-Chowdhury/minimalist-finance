<?php

use function Livewire\Volt\{state, on, computed, mount};

state(['income']);

on(function () {
    return ['incomeUpdated' . $this->income->id => $this->income->refresh()];
});

?>


<tr key="{{ $income->id }}">
    <td class="px-4 py-3">
        {{ $income->date->format('Y-m-d') }}
    </td>
    <td class="px-4 py-3">
        {{ $income->category?->name }}
    </td>
    <td class="px-4 py-3">${{ number_format($income->amount, 2) }}</td>
    <td class="px-4 py-3">
        {{ $income->toAccount?->name }}
    </td>
    <td class="px-4 py-3">
        {{ $income->note }}
    </td>
    <td class="px-4 py-3">

        <livewire:components.income.edit-income :income="$income" />

        <button title="Delete" wire:confirm="Are you sure to delete it?"
            wire:click="$parent.deleteIncome({{ $income->id }})" class="px-1 py-1 text-red-500">
            <i class="ri-delete-bin-6-line"></i>Delete
        </button>
    </td>
</tr>
