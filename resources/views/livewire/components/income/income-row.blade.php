<?php

use function Livewire\Volt\{state, on};

state(['income']);

on([
    'incomeUpdated{income.id}' => function () {
        $this->income->refresh();
    },
]);

?>


<tr key="{{ $income->id }}">
    <td class="px-4 py-3">
        {{ $income->date->format('d M Y') }}
    </td>
    <td class="px-4 py-3">
        {{ $income->category?->name }}
    </td>
    <td class="px-4 py-3">${{ number_format($income->amount, 2) }}</td>
    <td class="px-4 py-3">
        {{ $income->toAccount?->name }}
    </td>
    <td class="px-4 py-3">
        {{ strlen($income->note) > 40 ? substr($income->note, 0, 40) . '...' : $income->note }}
    </td>
    <td class="px-4 py-3">

        <livewire:components.income.edit-income :income="$income" />

        <button title="Delete" wire:confirm="Are you sure to delete it?"
            wire:click="$parent.deleteIncome({{ $income->id }})" class="px-1 py-1 text-red-500">
            <i class="ri-delete-bin-6-line"></i>Delete
        </button>
    </td>
</tr>
