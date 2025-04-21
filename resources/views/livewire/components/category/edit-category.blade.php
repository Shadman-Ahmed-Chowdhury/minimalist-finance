<?php

use function Livewire\Volt\{state, form, mount};
use App\Livewire\Forms\CategoryForm;
use Masmerise\Toaster\Toaster;

state(['buttonIcon' => 'ri-edit-2-line', 'buttonText' => 'Edit', 'showModal' => false, 'category']);
form(CategoryForm::class);

mount(function ($category) {
    $this->form->setCategory($category);
    $this->category = $category;
});

$save = function () {
    $this->form->update();
    $this->showModal = false;
    Toaster::success('Category updated successfully');
    $this->dispatch('categoryUpdated' . $this->category->id);
};

?>




<x-dialog wire:model="showModal">

    <x-dialog.open>
        <button type="button" class="text-sm text-primary-600 hover:text-primary-700 font-medium flex items-center">
            <i class="{{ $buttonIcon }}"></i>{{ $buttonText }}
        </button>
    </x-dialog.open>

    <x-dialog.panel>

        <h3 class="text-xl font-semibold text-gray-900 ">
            Update Category
        </h3>

        <hr>

        <!-- Modal body -->
        <div class="space-y-4 mt-5">
            <form wire:submit.prevent="save" class="mx-auto">
                <div class="mb-5">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Category Name</label>
                    <input wire:model="form.name" type="text" id="name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                        placeholder="Food" />
                    @error('form.name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Category Type</label>
                    <select wire:model="form.type" type="text" id="type"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                        placeholder="Bkash">
                        <option value="expense">Expense</option>
                        <option value="income">Income</option>
                    </select>
                    @error('form.type')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                <button type="submit"
                    class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Submit</button>
            </form>


        </div>

    </x-dialog.panel>

</x-dialog>
