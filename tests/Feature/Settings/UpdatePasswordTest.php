<?php

namespace Tests\Feature\Settings;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdatePasswordTest extends TestCase
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

    public function test_it_updates_authenticated_users_password()
    {
        $user = User::factory()->create([
            'password' => bcrypt('old-password'),
        ]);

        Sanctum::actingAs($user, ['*']);

        $response = $this->postJson('/api/update-password', [
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertOk()
            ->assertJson([
                'status' => 'success',
                'message' => 'your password has been updated.',
            ]);

        $user->refresh();

        $this->assertTrue(Hash::check('new-password', $user->password));
    }

    public function test_it_returns_error_when_password_confirmation_does_not_match()
    {
        $user = User::factory()->create([
            'password' => bcrypt('old-password'),
        ]);

        Sanctum::actingAs($user, ['*']);

        $response = $this->postJson('/api/update-password', [
            'password' => 'new-password',
            'password_confirmation' => 'different-password',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'status' => 'error',
                'message' => 'password and password confirmation do not match.',
            ]);

        $user->refresh();

        $this->assertTrue(Hash::check('old-password', $user->password));
    }

    public function test_it_requires_password()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        $response = $this->postJson('/api/update-password', [
            'password_confirmation' => 'new-password',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'status' => 'error',
                'message' => 'password and password confirmation do not match.',
            ]);
    }

    public function test_it_requires_password_confirmation()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        $response = $this->postJson('/api/update-password', [
            'password' => 'new-password',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'status' => 'error',
                'message' => 'password and password confirmation do not match.',
            ]);
    }

    public function test_it_requires_authentication()
    {
        $response = $this->postJson('/api/update-password', [
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    }
}
