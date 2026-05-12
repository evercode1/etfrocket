<?php

namespace Tests\Feature\Settings;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class MySettingsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        DB::table('users')->truncate();
    }

    protected function tearDown(): void
    {
        DB::table('users')->truncate();

        parent::tearDown();
    }

    public function test_it_returns_authenticated_user_settings()
    {
        $user = User::factory()->create([
            'name' => 'Bill Keck',
            'email' => 'bill@example.com',
        ]);

        Sanctum::actingAs($user, ['*']);

        $response = $this->getJson('/api/my-settings');

        $response->assertOk()
            ->assertJson([
                'email' => 'bill@example.com',
                'name' => 'Bill Keck',
            ]);
    }

    public function test_it_requires_authentication()
    {
        $response = $this->getJson('/api/my-settings');

        $response->assertUnauthorized();
    }
}
