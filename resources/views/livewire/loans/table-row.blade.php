<?php

use function Livewire\Volt\{state, on};

state(['transaction', 'accounts']);

on([
    'loan-paid{transaction.id}' => function(){
        $this->transaction->refresh();
    }])


?>

<tr key='tr-{{ $transaction->id }}' class="border-b border-gray-200 hover:bg-gray-50">
    <td class="px-4 py-3">
        {{ $transaction->date->format('d M Y') }}
    </td>
    <td class="px-4 py-3">${{ number_format($transaction->amount, 2) }}</td>
    <td class="px-4 py-3">
        ${{ number_format($transaction->loanParty->remaining_amount, 2) }}
    </td>
    <td class="px-4 py-3">{{ $transaction->loanParty?->name ?? 'N/A' }}</td>
    <td class="px-4 py-3">{{ ucfirst($transaction->loan_type) }}</td>
    <td class="px-4 py-3">
        {{ $transaction->loan_type === 'taken'
            ? $transaction->toAccount?->name ?? 'N/A'
            : $transaction->fromAccount?->name ?? 'N/A' }}
    </td>
    <td class="px-4 py-3">
        {{ $transaction->loanParty?->due_date->format('d M Y') }}
    </td>


    <td class="px-4 py-3">
        @if($transaction->loanParty?->remaining_amount > 0)
            @livewire('loans.pay', ['transactionId' => $transaction->id], 'pay'.$transaction->id)
            @livewire('loans.notify', ['transaction' => $transaction], 'id' . $transaction->id)
        @endif
        @if($transaction->loanParty?->remaining_amount == 0)
            {{-- a paid status --}}
            <span class="text-green-600 font-bold">Paid</span>
        @endif
        {{-- <button wire:click="$parent.deleteTransaction({{ $transaction->id }})"
            wire:confirm="Are you sure you want to delete this loan transaction?"
            class="text-red-600 hover:text-red-800"><i class="ri-delete-bin-6-line"></i>
            Delete
        </button> --}}
    </td>

</tr>
