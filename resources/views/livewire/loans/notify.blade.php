<?php

use App\Mail\CustomLoanReminderMail;
use Livewire\Volt\Component;
use App\Models\Transaction;
use App\Models\LoanParty;
use Illuminate\Support\Facades\Mail;
use Masmerise\Toaster\Toaster;

new class extends Component {
    public $transactionId;
    public $loanPartyEmail = '';
    public $message = '';
    public $showModal = false;

    public function mount($transactionId = null)
    {
        $this->transactionId = $transactionId;
        if ($transactionId) {
            $transaction = Transaction::with('loanParty')->find($transactionId);
            if (!$transaction) {
                Toaster::error('Transaction not found.');
                return;
            }
            if (!$transaction->loanParty) {
                Toaster::error('Loan party not associated with this transaction.');
                $this->loanPartyEmail = '';
                return;
            }
            $this->loanPartyEmail = $transaction->loanParty->email ?? '';
            if (empty($this->loanPartyEmail)) {
                Toaster::warning('No email found for the loan party.');
            }
        }
    }

    public function openModal()
    {
        if (empty($this->loanPartyEmail)) {
            Toaster::error('Cannot open modal: No valid email address available.');
            return;
        }
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['message']);
    }

    public function sendNotification()
    {
        $this->validate([
            'loanPartyEmail' => 'required|email',
            'message' => 'required|string|min:10',
        ]);

        try {
            $transaction = Transaction::with(['loanParty', 'toAccount', 'fromAccount'])->findOrFail($this->transactionId);

            // Queue the email
            Mail::to($this->loanPartyEmail)->queue(new CustomLoanReminderMail(
                $transaction->loanParty->name,
                $this->message,
                $transaction
            ));

            Toaster::success('Notification email queued successfully.');
            $this->closeModal();
        } catch (\Exception $e) {
            Toaster::error('Failed to queue notification email: ' . $e->getMessage());
            throw $e;
        }
    }
};
?>

<div>
    <button wire:click="openModal" class="text-blue-600 hover:text-blue-800 mr-2">
        <i class="ri-mail-send-line"></i> Notify
    </button>

    <!-- Modal -->
    <div x-cloak x-show="$wire.showModal"
         class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500 bg-opacity-10">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <h2 class="text-lg font-semibold mb-4">Send Notification</h2>

            <div class="space-y-4">
                <div>
                    <label for="loanPartyEmail" class="block mb-2 text-sm font-medium text-gray-900">To</label>
                    <input type="email" wire:model.live="loanPartyEmail" id="loanPartyEmail"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                           placeholder="Recipient email" readonly/>
                    @error('loanPartyEmail') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="message" class="block mb-2 text-sm font-medium text-gray-900">Message</label>
                    <textarea wire:model.debounce.500ms="message" id="message" rows="4"
                              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                              placeholder="Write your message here"></textarea>
                    @error('message') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button wire:click="closeModal"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                    Cancel
                </button>
                <button wire:click="sendNotification"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    Send
                </button>
            </div>
        </div>
    </div>
</div>
