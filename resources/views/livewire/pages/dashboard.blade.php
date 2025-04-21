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
        <livewire:components.accounts.stats-account />
    </main>
