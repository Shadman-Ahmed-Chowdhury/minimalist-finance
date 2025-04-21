<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">

    <x-stats-single-card :bgColor="'bg-blue-50 border-blue-100'" :color="'text-blue-600'" title="Total Balance" value="{{ $stats['totalIncome'] }}" />
    <x-stats-single-card :bgColor="'bg-red-50 border-red-100'" :color="'text-red-600'" title="Monthly Expense"
        value="{{ $stats['monthlyExpense'] }}" />
    <x-stats-single-card :bgColor="'bg-green-50 border-green-100'" :color="'text-green-600'" title="Monthly Income"
        value="{{ $stats['monthlyIncome'] }}" />
    <x-stats-single-card :bgColor="'bg-rose-50 border-emerald-100'" :color="'text-rose-600'" title="Total Loan Taken"
        value="{{ $stats['totalIncome'] }}" />
    <x-stats-single-card :bgColor="'bg-cyan-50 border-cyan-100'" :color="'text-cyan-600'" title="Total Loan Given"
        value="{{ $stats['totalIncome'] }}" />
</div>
