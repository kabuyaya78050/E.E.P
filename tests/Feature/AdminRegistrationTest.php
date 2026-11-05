<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class AdminRegistrationTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_first_administrator_can_register_and_is_signed_in(): void
    {
        $this->get(route('admin.register'))
            ->assertOk()
            ->assertSee('Créer le premier compte administrateur');

        $response = $this->post(route('admin.register'), [
            'name' => 'Admin Peniel',
            'email' => 'admin@example.test',
            'password' => 'strong-password',
            'password_confirmation' => 'strong-password',
        ]);

        $response->assertRedirect(route('admin.events.index'));
        $this->assertAuthenticatedAs(User::where('email', 'admin@example.test')->firstOrFail());
        $this->assertDatabaseHas('users', ['email' => 'admin@example.test']);
    }

    public function test_registration_is_closed_once_an_administrator_exists(): void
    {
        User::factory()->create();

        $this->get(route('admin.register'))->assertNotFound();
        $this->post(route('admin.register'), [])->assertNotFound();
        $this->assertDatabaseCount('users', 1);
    }

    public function test_registration_validates_account_details(): void
    {
        $this->post(route('admin.register'), [
            'name' => 'Admin Peniel',
            'email' => 'invalid-email',
            'password' => 'short',
            'password_confirmation' => 'different',
        ])->assertSessionHasErrors(['email', 'password']);
    }
}
