<?php

use function Livewire\Volt\{state, form, mount};
use App\Livewire\Forms\AccountForm;
use Masmerise\Toaster\Toaster;

state(['buttonIcon' => 'ri-edit-2-line', 'buttonText' => 'Edit', 'showModal' => false, 'class' => '', 'account']);
form(AccountForm::class);

mount(function ($account) {
    $this->form->setAccount($account);
});

$save = function () {
    $this->form->update();
    $this->showModal = false;
    Toaster::success('Account updated successfully');
    $this->dispatch('accountUpdated' . $this->account->id);
};

?>




<x-dialog wire:model="showModal">

    <x-dialog.open>
        <button type="button"
            class="text-sm text-primary-600 hover:text-primary-700 font-medium flex items-center {{ $class }}">
            <i class="{{ $buttonIcon }}"></i>{{ $buttonText }}
        </button>
    </x-dialog.open>

    <x-dialog.panel>

        <h3 class="text-xl font-semibold text-gray-900 ">
            Update Account
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
