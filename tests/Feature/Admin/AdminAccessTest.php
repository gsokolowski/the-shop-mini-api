<?php

namespace Tests\Feature\Admin;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_admin_login(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_log_in_and_view_dashboard(): void
    {
        $admin = User::factory()->admin()->create([
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $this->post(route('admin.login'), [
            'email' => 'admin@example.com',
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Dashboard');
    }

    public function test_non_admin_cannot_log_in_to_admin_panel(): void
    {
        User::factory()->create([
            'email' => 'user@example.com',
            'password' => 'password',
            'is_admin' => false,
        ]);

        $this->from(route('admin.login'))
            ->post(route('admin.login'), [
                'email' => 'user@example.com',
                'password' => 'password',
            ])
            ->assertRedirect(route('admin.login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_admin_can_manage_products(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post(route('admin.products.store'), [
                'name' => 'Test Product',
                'slug' => 'test-product',
                'description' => 'A test',
                'price' => 19.99,
                'stock' => 5,
            ])
            ->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', [
            'slug' => 'test-product',
            'name' => 'Test Product',
        ]);

        $product = Product::query()->where('slug', 'test-product')->firstOrFail();

        $this->actingAs($admin)
            ->delete(route('admin.products.destroy', $product))
            ->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_admin_users_cannot_be_updated_or_deleted(): void
    {
        $admin = User::factory()->admin()->create();
        $otherAdmin = User::factory()->admin()->create([
            'email' => 'other-admin@example.com',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.users.edit', $otherAdmin))
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('error');

        $this->actingAs($admin)
            ->put(route('admin.users.update', $otherAdmin), [
                'name' => 'Hacked',
                'email' => 'hacked@example.com',
            ])
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', [
            'id' => $otherAdmin->id,
            'email' => 'other-admin@example.com',
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.users.destroy', $otherAdmin))
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $otherAdmin->id]);
    }

    public function test_regular_users_can_be_updated_and_deleted(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create([
            'email' => 'client@example.com',
        ]);

        $this->actingAs($admin)
            ->put(route('admin.users.update', $user), [
                'name' => 'Updated Client',
                'email' => 'client@example.com',
                'is_admin' => false,
            ])
            ->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Client',
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.users.destroy', $user))
            ->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
