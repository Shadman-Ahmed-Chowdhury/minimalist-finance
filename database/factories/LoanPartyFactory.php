<?php

namespace Database\Factories;

use App\Models\LoanParty;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LoanPartyFactory extends Factory
{
    protected $model = LoanParty::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->name,
            'type' => $this->faker->randomElement(['borrower', 'lender']),
            'email' => $this->faker->email,
            'due_date' => $this->faker->date,
        ];
    }
}

