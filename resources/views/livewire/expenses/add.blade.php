<x-dialog wire:model="showModal">

    <x-dialog.open>
        <div class="p-4">
            <button type="button"
                class="flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-primary-600 rounded-lg shadow-sm transition-colors duration-200">
                <i class="ri-add-line text-lg"></i>
                Add Expenses
            </button>
        </div>

    </x-dialog.open>

    <x-dialog.panel>

        <h3 class="text-xl font-semibold text-gray-900 ">
            Add Expense
        </h3>

        <hr>

        <!-- Modal body -->
        <div class="space-y-4 mt-5">
            <form wire:submit.prevent="save" class="mx-auto">
                <div class="mb-5">
                    <label for="accountName" class="block mb-2 text-sm font-medium text-gray-900">Account Name</label>
                    <select wire:model="form.accountName" id="accountName"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="" disabled selected>Select an account</option>

                        @if ($accounts->isEmpty())
                            <option value="" disabled>No accounts available</option>
                        @endif

                        @foreach ($accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->name }}</option>
                        @endforeach

                    </select>
                    @error('form.accountName')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>


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
                    <label for="expenseDate" class="block mb-2 text-sm font-medium text-gray-900">
                        Date
                    </label>
                    <input wire:model="form.date" type="date" id="expenseDate"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                        readonly />
                    @error('form.date')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="category" class="block mb-2 text-sm font-medium text-gray-900">
                        Category
                    </label>
                    <select wire:model="form.expenseCategory" id="category"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="" disabled selected>Select a category</option>


                        @foreach ($this->categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach

                    </select>
                    @error('form.expenseCategory')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="desc" class="block mb-2 text-sm font-medium text-gray-900">Note</label>
                    <textarea wire:model="form.note" id="note"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "></textarea>
                    @error('form.note')
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
