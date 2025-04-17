<x-app-layout>
    <main class="flex-1 p-8">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
            <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-sm font-medium text-gray-500">Total Money</h3>
                <p class="text-2xl font-semibold mt-2">$10,000</p>
            </div>
            <div class="p-6 bg-red-50 rounded-xl shadow-sm border border-red-100">
                <h3 class="text-sm font-medium text-red-600">Monthly Expense</h3>
                <p class="text-2xl font-semibold text-red-700 mt-2">$1,000</p>
            </div>
            <div class="p-6 bg-green-50 rounded-xl shadow-sm border border-green-100">
                <h3 class="text-sm font-medium text-green-600">Monthly Income</h3>
                <p class="text-2xl font-semibold text-green-700 mt-2">$2,000</p>
            </div>
            <div class="p-6 bg-pink-50 rounded-xl shadow-sm border border-pink-100">
                <h3 class="text-sm font-medium text-pink-600">Total Loan Taken</h3>
                <p class="text-2xl font-semibold text-pink-700 mt-2">$500</p>
            </div>
            <div class="p-6 bg-emerald-50 rounded-xl shadow-sm border border-emerald-100">
                <h3 class="text-sm font-medium text-emerald-600">Total Loan Given</h3>
                <p class="text-2xl font-semibold text-emerald-700 mt-2">$1,500</p>
            </div>
        </div>

        <!-- Accounts Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Accounts</h2>
                <button class="text-sm text-blue-600 hover:text-blue-700 font-medium flex items-center">
                    <i class="ri-add-line mr-1"></i> Add Account
                </button>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <div
                    class="p-4 rounded-lg border border-gray-200 hover:border-blue-200 hover:bg-blue-50 transition-colors cursor-pointer">
                    <div class="flex items-center justify-between">
                        <span class="font-medium text-gray-700">Cash</span>
                        <i class="ri-arrow-right-s-line text-gray-400"></i>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">$0.00</p>
                </div>
                <div
                    class="p-4 rounded-lg border border-gray-200 hover:border-blue-200 hover:bg-blue-50 transition-colors cursor-pointer">
                    <div class="flex items-center justify-between">
                        <span class="font-medium text-gray-700">Bkash</span>
                        <i class="ri-arrow-right-s-line text-gray-400"></i>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">$0.00</p>
                </div>
                <div
                    class="p-4 rounded-lg border border-gray-200 hover:border-blue-200 hover:bg-blue-50 transition-colors cursor-pointer">
                    <div class="flex items-center justify-between">
                        <span class="font-medium text-gray-700">Nagad</span>
                        <i class="ri-arrow-right-s-line text-gray-400"></i>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">$0.00</p>
                </div>
                <div
                    class="p-4 rounded-lg border border-gray-200 hover:border-blue-200 hover:bg-blue-50 transition-colors cursor-pointer">
                    <div class="flex items-center justify-between">
                        <span class="font-medium text-gray-700">Rocket</span>
                        <i class="ri-arrow-right-s-line text-gray-400"></i>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">$0.00</p>
                </div>
                <div
                    class="p-4 rounded-lg border border-gray-200 hover:border-blue-200 hover:bg-blue-50 transition-colors cursor-pointer">
                    <div class="flex items-center justify-between">
                        <span class="font-medium text-gray-700">City Bank</span>
                        <i class="ri-arrow-right-s-line text-gray-400"></i>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">$0.00</p>
                </div>
                <div
                    class="p-4 rounded-lg border border-gray-200 hover:border-blue-200 hover:bg-blue-50 transition-colors cursor-pointer">
                    <div class="flex items-center justify-between">
                        <span class="font-medium text-gray-700">EBL</span>
                        <i class="ri-arrow-right-s-line text-gray-400"></i>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">$0.00</p>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>
