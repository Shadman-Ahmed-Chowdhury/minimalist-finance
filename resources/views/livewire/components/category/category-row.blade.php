<?php
use function Livewire\Volt\{state, on};

state(['category']);

on(function () {
    return ['categoryUpdated' . $this->category->id => $this->category->refresh()];
});

?>



<tr key="{{ $category->id }}">
    <td class="px-4 py-3">
        {{ $category->name }}
    </td>

    <td class="px-4 py-3">{{ strtoupper($category->type) }}</td>
    <td class="px-4 py-3">
        <livewire:components.category.edit-category :key="'edit-category-' . $category->id" :category="$category" />
        <button title="Delete" wire:confirm="Are you sure to delete it?"
            wire:click="$parent.deleteCategory({{ $category->id }})" class="px-1 py-1 text-red-500">
            <i class="ri-delete-bin-6-line"></i>Delete
        </button>
    </td>
</tr>
