<?php

use function Livewire\Volt\{action};

use App\Livewire\Actions\Logout;

$logout = action(function (Logout $logout) {
    $logout();

    $this->redirect('/', navigate: true);
});

?>

<button wire:click="logout" class="flex items-center px-4 py-2 w-full text-red-500 hover:bg-gray-100">
    <i class="ri-logout-box-r-line mr-2"></i>
    <span>Logout</span>
</button>
