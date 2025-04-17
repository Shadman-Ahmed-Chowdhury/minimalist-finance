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
                        <a href="{{route('dashboard')}}"
                            class="flex items-center gap-3 px-3 py-2 bg-primary-500 text-white rounded-md">
                            <i class="ri-dashboard-line text-lg"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a id="expense" href="{{route('expenses')}}"
                            class="flex items-center gap-3 px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">
                            <i class="ri-bill-line text-lg"></i>
                            <span>Expense</span>
                        </a>
                    </li>
                    <li>
                        <a href="#"
                            class="flex items-center gap-3 px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">
                            <i class="ri-refund-2-line text-lg"></i>
                            <span>Income</span>
                        </a>
                    </li>
                    <li>
                        <a href="#"
                            class="flex items-center gap-3 px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">
                            <i class="ri-exchange-dollar-line text-lg"></i>
                            <span>Transfer</span>
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
                        <p class="text-sm font-medium">John Doe</p>
                        <p class="text-xs text-gray-500">johndoe@example.com</p>
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


        <!-- Add Expense Modal -->
        <div id="expense-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-white rounded-lg max-w-md w-full p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Add New Expense</h3>
                    <button id="close-modal" class="text-gray-400 hover:text-gray-600">
                        <i class="ri-close-line text-xl"></i>
                    </button>
                </div>
                <form id="expense-form">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
                            <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md"
                                placeholder="Enter amount" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                <option>Food</option>
                                <option>Transportation</option>
                                <option>Entertainment</option>
                                <option>Utilities</option>
                                <option>Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                            <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Account</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                <option>Cash</option>
                                <option>Bkash</option>
                                <option>Nagad</option>
                                <option>Rocket</option>
                                <option>City Bank</option>
                                <option>EBL</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Note</label>
                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md" rows="3" placeholder="Add a note (optional)"></textarea>
                        </div>
                        <div class="pt-4">
                            <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md">
                                Save Expense
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>



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

        // Expense modal functionality
        const addExpenseBtn = document.querySelector('#expense');
        const expenseModal = document.getElementById('expense-modal');
        const closeModalBtn = document.getElementById('close-modal');
        const expenseForm = document.getElementById('expense-form');

        // This would be linked to the actual Expense button in a real implementation
        addExpenseBtn.addEventListener('click', (e) => {
            e.preventDefault();
            expenseModal.classList.remove('hidden');
        });

        closeModalBtn.addEventListener('click', () => {
            expenseModal.classList.add('hidden');
        });

        // Close modal when clicking outside
        expenseModal.addEventListener('click', (event) => {
            if (event.target === expenseModal) {
                expenseModal.classList.add('hidden');
            }
        });

        // Handle form submission
        expenseForm.addEventListener('submit', (e) => {
            e.preventDefault();
            // Logic for saving expense would go here
            alert('Expense saved!');
            expenseModal.classList.add('hidden');
        });
    </script>

    @if ($scripts)
        {{ $scripts }}
    @endif

</body>

</html>
