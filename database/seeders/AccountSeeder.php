<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    public function run()
    {
        Account::factory(20)->create(); // Adjust the number of accounts as needed
    }
}
