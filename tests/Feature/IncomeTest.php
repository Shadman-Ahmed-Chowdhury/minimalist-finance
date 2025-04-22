<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Volt\Volt;
use Tests\TestCase;

class IncomeTest extends TestCase
{
    public function test_can_be_accessible_by_authenticated_user(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get('/income');

        $response->assertStatus(200);
    }

    public function test_can_not_be_accessible_by_unauthenticated_user(): void
    {

        $response = $this->get('/income');

        $response->assertRedirect('/login');
    }

    public function test_add_income(): void
    {
        $user = User::factory()->create();

        $account = Account::factory()->create([
            'user_id' => $user->id
        ]);

        $category = Category::factory()->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user);


        $component = Volt::test('components.income.add-income');


        $component->call('save');
    }
}
