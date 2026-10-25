<?php

namespace Tests\Feature\Api\V1\Account;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AccountContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_crud_and_default_address_switch(): void
    {
        $customer = $this->postJson('/api/v1/customers', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '01700000000',
        ])->assertCreated()
            ->assertJsonPath('data.name', 'Jane Doe')
            ->json('data');

        $first = $this->postJson('/api/v1/addresses', [
            'customer_id' => $customer['id'],
            'type' => 'shipping',
            'address_line1' => '12 Main St',
            'city' => 'Dhaka',
            'country' => 'Bangladesh',
            'is_default' => true,
        ])->assertCreated()->json('data');

        $second = $this->postJson('/api/v1/addresses', [
            'customer_id' => $customer['id'],
            'type' => 'billing',
            'address_line1' => '45 Side St',
            'city' => 'Chattogram',
            'country' => 'Bangladesh',
            'is_default' => true,
        ])->assertCreated()->json('data');

        $this->assertDatabaseHas('addresses', ['id' => $first['id'], 'is_default' => false]);
        $this->assertDatabaseHas('addresses', ['id' => $second['id'], 'is_default' => true]);

        $this->getJson("/api/v1/customers/{$customer['id']}")
            ->assertOk()
            ->assertJsonPath('data.addresses_count', 2)
            ->assertJsonCount(2, 'data.addresses');

        $this->getJson('/api/v1/customers?search=Jane')
            ->assertOk()
            ->assertJsonCount(1, 'data');

        $this->patchJson("/api/v1/customers/{$customer['id']}", [
            'phone' => '01800000000',
        ])->assertOk()
            ->assertJsonPath('data.phone', '01800000000');
    }

    public function test_post_syncs_tags_and_publishes(): void
    {
        $user = User::factory()->create();

        $firstTag = Tag::query()->create(['name' => 'Laravel', 'slug' => 'laravel']);
        $secondTag = Tag::query()->create(['name' => 'API', 'slug' => 'api']);

        $post = $this->postJson('/api/v1/posts', [
            'user_id' => $user->id,
            'title' => 'Building APIs',
            'slug' => 'building-apis',
            'content' => 'Long form content.',
            'status' => 'published',
            'tags' => [$firstTag->id, $secondTag->id],
        ])->assertCreated()
            ->assertJsonPath('data.status', 'published')
            ->assertJsonPath('data.tags_count', 2)
            ->assertJsonPath('data.published_at', fn ($value) => $value !== null)
            ->json('data');

        $this->assertDatabaseCount('post_tag', 2);

        $this->patchJson("/api/v1/posts/{$post['id']}", [
            'tags' => [$firstTag->id],
        ])->assertOk()
            ->assertJsonPath('data.tags_count', 1);

        $this->assertDatabaseCount('post_tag', 1);

        $this->getJson('/api/v1/posts?status=published&search=Building')
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_post_category_crud(): void
    {
        $category = $this->postJson('/api/v1/post-categories', [
            'name' => 'News',
            'slug' => 'news',
            'description' => 'Company news',
        ])->assertCreated()->json('data');

        $this->getJson('/api/v1/post-categories?search=News')
            ->assertOk()
            ->assertJsonPath('data.0.slug', 'news')
            ->assertJsonPath('data.0.posts_count', 0);

        $this->deleteJson("/api/v1/post-categories/{$category['id']}")
            ->assertNoContent();
    }

    public function test_user_password_is_hashed_and_never_exposed(): void
    {
        $user = $this->postJson('/api/v1/users', [
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'secret-password',
            'role' => 'admin',
        ])->assertCreated()
            ->assertJsonPath('data.role', 'admin')
            ->assertJsonMissingPath('data.password')
            ->json('data');

        $this->assertTrue(Hash::check('secret-password', User::query()->findOrFail($user['id'])->password));

        $this->patchJson("/api/v1/users/{$user['id']}", [
            'password' => '',
        ])->assertOk();

        $this->assertTrue(Hash::check('secret-password', User::query()->findOrFail($user['id'])->password));

        $this->patchJson("/api/v1/users/{$user['id']}", [
            'password' => 'new-secret-password',
        ])->assertOk();

        $this->assertTrue(Hash::check('new-secret-password', User::query()->findOrFail($user['id'])->password));

        $this->getJson('/api/v1/users?role=admin')
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }
}
