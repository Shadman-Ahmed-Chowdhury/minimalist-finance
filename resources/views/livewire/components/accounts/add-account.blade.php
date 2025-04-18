<?php

use function Livewire\Volt\{state, form};
use App\Livewire\Forms\AccountForm;
use Masmerise\Toaster\Toaster;

state(['buttonIcon' => 'ri-add-line', 'buttonText' => 'Add Account', 'showModal' => false]);
form(AccountForm::class);

$save = function () {
    try {
        $this->form->save();
        $this->showModal = false;
        Toaster::success('Account added successfully');
        $this->dispatch('accountAdded');
    } catch (\Exception $th) {
        Toaster::error($th->getMessage());
    }
};

?>




<x-dialog wire:model="showModal">

    <x-dialog.open>
        <button type="button" class="text-sm text-blue-600 hover:text-blue-700 font-medium flex items-center">
            <i class="{{ $buttonIcon }}"></i>{{ $buttonText }}
        </button>
    </x-dialog.open>

    <x-dialog.panel>

        <h3 class="text-xl font-semibold text-gray-900 ">
            Add Account
        </h3>

        <hr>

        <!-- Modal body -->
        <div class="space-y-4 mt-5">
            <form wire:submit.prevent="save" class="mx-auto">
                <div class="mb-5">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Account Name</label>
                    <input wire:model="form.name" type="text" id="name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                        placeholder="Bkash" />
                    @error('form.name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="init_balance" class="block mb-2 text-sm font-medium text-gray-900">Initial
                        Balance</label>
                    <input wire:model="form.initial_balance" type="number" id="init_balance"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                        placeholder="0" />
                    @error('form.initial_balance')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="desc" class="block mb-2 text-sm font-medium text-gray-900">Description</label>
                    <textarea wire:model="form.description" id="desc"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "></textarea>
                    @error('form.description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                <button type="submit"
                    class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Submit</button>
            </form>


        </div>

    </x-dialog.panel>

</x-dialog>
