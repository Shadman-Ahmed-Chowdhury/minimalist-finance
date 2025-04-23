<?php

use function Livewire\Volt\{state, form, computed};
use App\Livewire\Forms\TransferForm;
use Masmerise\Toaster\Toaster;

state(['buttonIcon' => 'ri-add-line', 'buttonText' => 'Transfer Amount', 'showModal' => false, 'accounts']);

form(TransferForm::class);

$save = function () {
    // dd($this->form->all());
    try {
        $validationreturn = $this->form->save();
        if ($validationreturn) {
            $this->showModal = false;
            Toaster::success('Transfered successfully');
            $this->dispatch('transferAdded');
        }
    } catch (\Exception $th) {
        Toaster::error($th->getMessage());
    }
};

?>

<x-dialog wire:model="showModal">
    <x-dialog.open>
        <button type="button"
            class="text-sm bg-primary-500 px-5 py-2 hover:bg-primary-700 text-white rounded font-medium flex items-center">
            <i class="{{ $buttonIcon }}"></i>{{ $buttonText }}
        </button>
    </x-dialog.open>
    <x-dialog.panel>
        <h3 class="text-xl font-semibold text-gray-900 ">
            New Transfer
        </h3>

        <hr>

        <!-- Modal body -->
        <div class="space-y-4 mt-5">
            <form wire:submit.prevent="save" class="mx-auto">

                <div class="mb-5">
                    <label for="init_balance" class="block mb-2 text-sm font-medium text-gray-900">Amount</label>
                    <input wire:model="form.amount" type="number" id="amount"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 "
                        placeholder="0" />
                    @error('form.amount')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                <div class="mb-5">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900">From Account</label>
                    <select wire:model="form.from_account_id" id="name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 ">

                        <option value="">Select Account</option>

                        @foreach ($this->accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->name }}-{{ $account->balance }}</option>
                        @endforeach

                    </select>
                    @error('form.from_account_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-5">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900">To Account</label>
                    <select wire:model="form.to_account_id" id="name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 ">

                        <option value="">Select Account</option>

                        @foreach ($this->accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->name }}-{{ $account->balance }}</option>
                        @endforeach

                    </select>
                    @error('form.to_account_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="desc" class="block mb-2 text-sm font-medium text-gray-900">Note</label>
                    <textarea wire:model="form.note" id="desc"
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
