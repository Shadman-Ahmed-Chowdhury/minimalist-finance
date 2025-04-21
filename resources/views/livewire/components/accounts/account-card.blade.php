<?php

use function Livewire\Volt\{state};

state('account');

?>

<div
    class="p-4 rounded-lg border border-gray-200 hover:border-blue-200 hover:bg-blue-50 transition-colors cursor-pointer">
    <div class="flex items-center justify-between">
        <span class="font-medium text-gray-700">{{ $account->name }}</span>
        <i class="ri-arrow-right-s-line text-gray-400"></i>
    </div>
    <p class="text-sm text-gray-500 mt-1">${{ $account->balance }}</p>
</div>
