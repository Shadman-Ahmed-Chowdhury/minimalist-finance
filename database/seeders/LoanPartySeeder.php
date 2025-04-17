<?php

namespace Database\Seeders;

use App\Models\LoanParty;
use Illuminate\Database\Seeder;

class LoanPartySeeder extends Seeder
{
    public function run()
    {
        LoanParty::factory(10)->create(); // Adjust the number of loan parties as needed
    }
}

