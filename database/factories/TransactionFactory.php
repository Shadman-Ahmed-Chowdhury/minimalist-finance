<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Account;
use App\Models\Category;
use App\Models\LoanParty;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'type' => $this->faker->randomElement(['income', 'expense', 'loan', 'transfer']),
            'loan_type' => $this->faker->randomElement(['taken', 'given']),
            'loan_party_id' => LoanParty::factory(),
            'category_id' => Category::factory(),
            'amount' => $this->faker->randomFloat(2, 1, 1000),
            'from_account_id' => Account::factory(),
            'to_account_id' => Account::factory(),
            'note' => $this->faker->sentence,
            'date' => $this->faker->date,
        ];
    }
}

