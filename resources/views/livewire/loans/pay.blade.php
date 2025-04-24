<?php

use Livewire\Volt\Component;
use App\Models\Transaction;
use App\Models\LoanParty;
use App\Models\Account;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Masmerise\Toaster\Toaster;

new class extends Component {
    public $showModal = false;
    public $amount;
    public $transactionId;
    public $loanPartyId;
    public $accountName;
    public $accounts;

    public function mount($transactionId): void
    {
        $this->transactionId = $transactionId;
        $transaction = Transaction::with('loanParty')->findOrFail($transactionId);
        $this->loanPartyId = $transaction->loan_party_id;
        $this->accounts = Account::where('user_id', Auth::id())->get();
    }

    private function determineAccountField(string $loanType): string
    {
        return $loanType === 'given' ? 'from_account_id' : 'to_account_id';
    }

    public function rules()
    {
        return [
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
                function ($attribute, $value, $fail) {
                    $transaction = Transaction::with('loanParty')->find($this->transactionId);
                    if ($transaction && $transaction->loanParty && $value > $transaction->loanParty->remaining_amount) {
                        $fail('The payment amount cannot exceed the remaining loan amount (' . $transaction->loanParty->remaining_amount . ').');
                    }
                },
            ],
            'accountName' => [
                'required',
                'exists:accounts,id',
            ],
        ];
    }

    public function save(): void
    {
        $transaction = Transaction::with('loanParty')->findOrFail($this->transactionId);
        $loanType = $transaction->loan_type;

        $this->validate();

        // // Validate inputs
        // $this->validate(array_merge($this->rules, [
        //     'amount' => ['max:' . $transaction->loanParty->remaining_amount],
        // ]));

        DB::transaction(function () use ($transaction, $loanType) {
            $loanParty = LoanParty::findOrFail($this->loanPartyId);
            $account = Account::findOrFail($this->accountName);

            // Update remaining balance
            $newBalance = max(0, $loanParty->remaining_amount - $this->amount);
            $loanParty->update(['remaining_amount' => $newBalance]);

            // Update account balance
            $account->balance += ($loanType === 'given' ? $this->amount : -$this->amount);
            $account->save();

            // Create loan payment transaction
            Transaction::create([
                'user_id' => Auth::id(),
                'amount' => $this->amount,
                'date' => now(),
                'type' => 'loan_payment',
                'loan_type' => $loanType,
                'loan_party_id' => $this->loanPartyId,
                $this->determineAccountField($loanType) => $this->accountName,
            ]);
        });

        // Dispatch event to refresh table
        $this->dispatch('loan-paid'.$transaction->id);

        // Reset form and close modal
        $this->reset(['amount', 'accountName', 'showModal']);
        Toaster::success('Payment successful!');
    }
};

?>

<x-dialog wire:model="showModal">
    <x-dialog.open>
        <button type="button"
                class="text-green-600 hover:text-green-800 mr-2">
            <i class="ri-add-line text-lg"></i>
            Pay
        </button>
    </x-dialog.open>

    <x-dialog.panel>
        <h3 class="text-xl font-semibold text-gray-900">
            Pay Loan
        </h3>

        <hr>

        <div class="space-y-4 mt-5">
            <form wire:submit.prevent="save" class="mx-auto">
                <div class="mb-5">
                    <label for="amount" class="block mb-2 text-sm font-medium text-gray-900">
                        Payment Amount
                    </label>
                    <input wire:model.debounce.500ms="amount" type="number" id="amount" step="0.01"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                           placeholder="0.00"/>
                    @error('amount')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="accountName" class="block mb-2 text-sm font-medium text-gray-900">
                        Account
                    </label>
                    <select wire:model="accountName" id="accountName"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option >Select an account</option>
                        @if ($accounts->isEmpty())
                            <option value="" disabled>No accounts available</option>
                        @endif
                        @foreach ($accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->name }} (Balance: ${{ number_format($account->balance, 2) }})</option>
                        @endforeach
                    </select>
                    @error('accountName')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                    Submit Payment
                </button>
            </form>
        </div>
    </x-dialog.panel>
</x-dialog>
