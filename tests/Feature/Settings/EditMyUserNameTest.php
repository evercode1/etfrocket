<?php

namespace Tests\Feature\Settings;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EditMyUserNameTest extends TestCase
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

    public function test_it_returns_edit_username_form_config()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        $response = $this->getJson('/api/edit-my-user-name');

        $response->assertOk()
            ->assertJson([
                'form_config' => [
                    [
                        'name' => 'name',
                        'type' => 'text',
                        'label' => 'Username',
                        'required' => 1,
                        'max_length' => 50,
                        'instructions' => '',
                    ],
                ],
                'post_endpoint' => 'update-my-user-name',
            ]);
    }

    public function test_it_requires_authentication()
    {
        $response = $this->getJson('/api/edit-my-user-name');

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    }
}
