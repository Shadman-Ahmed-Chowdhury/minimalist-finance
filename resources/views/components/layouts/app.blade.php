@props(['styles' => null, 'title' => null, 'scripts' => null])


<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @if ($title)
            {{ $title }} |
        @endif
        {{ config('app.name', 'Laravel') }}
    </title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet" />



    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @if ($styles)
        {{ $styles }}
    @endif
</head>

<body class="bg-gray-50">
    <div class="flex min-h-screen w-full">
        <!-- Sidebar -->
        <div id="sidebar" class="w-64 bg-white border-r border-gray-200 flex flex-col">
            <!-- Sidebar Header -->
            <div class="p-6">
                <div class="text-xl font-bold">{{ config('app.name') }}</div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="flex-1 p-4">
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('dashboard') }}"
                            class="flex items-center gap-3 px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md

                            @if (Route::currentRouteName() == 'dashboard') bg-primary-500 text-white hover:bg-primary-100 hover:text-gray-700 @endif
                            ">
                            <i class="ri-dashboard-line text-lg"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a id="expense" href="{{ route('expenses') }}"
                            class="flex items-center gap-3 px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">
                            <i class="ri-bill-line text-lg"></i>
                            <span>Expense</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('income') }}"
                            wire:current="bg-primary-500 text-white hover:bg-primary-100 hover:text-gray-700"
                            class="flex items-center gap-3 px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">
                            <i class="ri-refund-2-line text-lg"></i>
                            <span>Income</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('loans') }}"
                            wire:current="bg-primary-500 text-white hover:bg-primary-100 hover:text-gray-700"
                            class="flex items-center gap-3 px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">
                            <i class="ri-refund-2-line text-lg"></i>
                            <span>Loan</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('transfer') }}"
                            wire:current="bg-primary-500 text-white hover:bg-primary-100 hover:text-gray-700"
                            class="flex items-center gap-3 px-3 py-2 text-gray-700 hover:bg-gray-100  rounded-md">
                            <i class="ri-exchange-dollar-line text-lg"></i>
                            <span>Transfer</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('account') }}"
                            wire:current="bg-primary-500 text-white hover:bg-primary-100 hover:text-gray-700"
                            class="flex items-center gap-3 px-3 py-2 text-gray-700 hover:bg-gray-100  rounded-md">
                            <i class="ri-wallet-2-line text-lg"></i>
                            <span>Accounts</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('category') }}"
                            wire:current="bg-primary-500 text-white hover:bg-primary-100 hover:text-gray-700"
                            class="flex items-center gap-3 px-3 py-2 text-gray-700 hover:bg-gray-100  rounded-md">
                            <i class="ri-donut-chart-line"></i>
                            <span>Categories</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Sidebar Footer -->
            <div class="border-t border-gray-200 p-4">
                <div id="user-dropdown-trigger"
                    class="flex items-center space-x-3 cursor-pointer hover:bg-gray-100 rounded-md p-2 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                        <i class="ri-user-line text-gray-500"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>

            <!-- User Dropdown Menu -->
            <div id="user-dropdown"
                class="absolute bottom-[85px] left-[230px] bg-white rounded-lg shadow-lg border border-gray-200 w-56 hidden">
                <ul class="py-2 text-sm">
                    <li>
                        <a href="#" class="flex items-center px-4 py-2 hover:bg-gray-100">
                            <i class="ri-user-line mr-2"></i>
                            <span>Profile</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-4 py-2 hover:bg-gray-100">
                            <i class="ri-settings-3-line mr-2"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                    <li class="border-t border-gray-100 mt-2 pt-2">
                        <a href="#" class="flex items-center px-4 py-2 text-red-500 hover:bg-gray-100">
                            <i class="ri-logout-box-r-line mr-2"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        {{ $slot }}


    </div>

    <x-toaster-hub />


    <script defer src="https://unpkg.com/@alpinejs/ui@3.13.2-beta.0/dist/cdn.min.js"></script>


    <script>
        // User dropdown toggle
        const userDropdownTrigger = document.getElementById('user-dropdown-trigger');
        const userDropdown = document.getElementById('user-dropdown');

        userDropdownTrigger.addEventListener('click', () => {
            userDropdown.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (event) => {
            if (!userDropdownTrigger.contains(event.target) && !userDropdown.contains(event.target)) {
                userDropdown.classList.add('hidden');
            }
        });
    </script>

    @if ($scripts)
        {{ $scripts }}
    @endif

</body>

</html>
