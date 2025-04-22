<?php

use function Livewire\Volt\{computed, uses, on, state, updated};

use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;
use App\Models\Category;

on(['categoryAdded' => '$refresh', 'categoryRemoved' => '$refresh']);

uses(WithPagination::class);

state([
    'filterSearch' => '',
    'filterType' => '',
]);

updated([
    'filterSearch' => function () {
        $this->resetPage();
    },
    'filterType' => function () {
        $this->resetPage();
    },
]);

$categories = computed(function () {
    return Category::where('user_id', auth()->user()->id)
        ->latest()
        ->when($this->filterSearch, function ($query) {
            return $query->where('name', 'like', '%' . $this->filterSearch . '%');
        })
        ->when($this->filterType, function ($query) {
            return $query->where('type', $this->filterType);
        })
        ->paginate(10);
});

$deleteCategory = function ($id) {
    $category = Category::where('user_id', auth()->user()->id)
        ->where('id', $id)
        ->first();

    if (!$category) {
        Toaster::error('Category not found');
        return;
    }

    $category->delete();
    Toaster::success('Category deleted successfully');
    $this->dispatch('categoryRemoved');
};

?>
<main class="flex-1 p-8">
    <div class="mmx-auto space-y-6">
        <!-- Header Section -->
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-semibold tracking-tight">Category</h1>

            <livewire:components.category.add-category
                class="bg-primary-500 hover:bg-primary-600 px-5 py-2 rounded text-white hover:text-white" />

        </div>

        <!-- Filters Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex space-x-4">
                <div class="flex-1">
                    <label for="search" class="block mb-2 text-sm font-medium text-gray-900">Category Name</label>
                    <input type="text" wire:model.live.debounce.500ms="filterSearch"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                        placeholder="Search by category name">
                </div>

                <div class="flex-1">
                    <label for="search" class="block mb-2 text-sm font-medium text-gray-900">Type</label>
                    <select wire:model.live="filterType" id="type"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                        <option value="">All</option>
                        <option value="expense">Expense</option>
                        <option value="income">Income</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Category Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table wire:loading.class="opacity-50 cursor-wait" class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">Category Name</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">Type</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">Action</th>
                        </tr>
                    </thead>
                    <tbody id="categoryTableBody">
                        @foreach ($this->categories as $category)
                            <livewire:components.category.category-row key="{{ $category->id }}" :category="$category" />
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4">
                {{ $this->categories->links() }}
            </div>
        </div>
    </div>
</main>
