<?php

use Livewire\Volt\Component;
use App\Models\Account;
use App\Livewire\Forms\AddLoanForm;
use Masmerise\Toaster\Toaster;

new class extends Component {

    public AddLoanForm $form;
    public $showModal = false;


    #[Computed]
    public function accounts()
    {
        return Account::where('user_id', auth()->id())->get();
    }

    public function save()
    {
        $this->form->save();

        Toaster::success('Loan added successfully');
        $this->reset();
        $this->dispatch('loanAdded');
        $this->showModal = false;

    }

};

?>


<x-dialog wire:model="showModal">

    <x-dialog.open>
        <div class="p-4">
            <button type="button" class="text-sm bg-primary-500 px-5 py-2 hover:bg-primary-700 text-white rounded font-medium flex items-center">
                <i class="ri-add-line text-lg"></i>
                Add Loan
            </button>
        </div>

    </x-dialog.open>

    <x-dialog.panel>

        <h3 class="text-xl font-semibold text-gray-900 ">
            Add Loan
        </h3>

        <hr>

        <!-- Modal body -->
        <div class="space-y-4 mt-5">
            <form wire:submit.prevent="save" class="mx-auto">

                <div class="mb-5">
                    <label for="init_balance" class="block mb-2 text-sm font-medium text-gray-900">
                        Amount
                    </label>
                    <input wire:model="form.amount" type="number" id="init_balance"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                           placeholder="0" />
                    @error('form.amount')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="accountName" class="block mb-2 text-sm font-medium text-gray-900">Account Name</label>
                    <select wire:model="form.accountName" id="accountName"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="" disabled selected>Select an account</option>

                        @if($this->accounts()->isEmpty())
                            <option value="" disabled>No accounts available</option>
                        @endif

                        @foreach($this->accounts() as $account)
                            <option value="{{ $account->id }}">{{ $account->name }}</option>
                        @endforeach

                    </select>
                    @error('form.accountName')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="init_balance" class="block mb-2 text-sm font-medium text-gray-900">
                        Full Name
                    </label>
                    <input wire:model="form.name" type="text" id="init_balance"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                           placeholder="Name of borrower/lender" />
                    @error('form.name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="type" class="block mb-2 text-sm font-medium text-gray-900">Person Type</label>
                    <select wire:model="form.type" id="typeofPerson"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="" disabled selected>Select a type</option>
                        <option value="borrower">Borrower</option>
                        <option value="lender">Lender</option>
                    </select>
                    @error('form.type')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- for email --}}
                <div class="mb-5">
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                    <input wire:model="form.email" type="email" id="email"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                           placeholder="Email of borrower/lender" />
                    @error('form.email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- for due date --}}
                <div class="mb-5">
                    <label for="due_date" class="block mb-2 text-sm font-medium text-gray-900">Due Date</label>
                    <input wire:model="form.dueDate" type="date" id="due_date"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                           min="{{ now()->toDateString() }}" placeholder="Due date of loan" />
                    @error('form.dueDate')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                        Submit
                    </button>
            </form>


        </div>

    </x-dialog.panel>

</x-dialog>
