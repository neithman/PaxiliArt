<?php

namespace Tests\Feature;

use App\Models\Artwork;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_marketplace_shows_published_artwork_and_filters_by_search(): void
    {
        $artist = User::factory()->create(['role' => 'seller']);
        $artwork = Artwork::create([
            'artist_id' => $artist->id,
            'title' => 'Quiet Garden',
            'slug' => 'quiet-garden',
            'description' => 'A colorful digital garden.',
            'category' => 'Others',
            'price' => 120,
            'cover_image' => 'images/artwork/cosmic-orbit.jpeg',
            'status' => 'published',
        ]);
        Artwork::create([
            'artist_id' => $artist->id,
            'title' => 'Hidden Sketch',
            'slug' => 'hidden-sketch',
            'description' => 'Waiting for review.',
            'category' => 'Others',
            'price' => 120,
            'cover_image' => 'images/artwork/cosmic-orbit.jpeg',
            'status' => 'pending',
        ]);

        $this->get(route('marketplace.index'))
            ->assertOk()
            ->assertSee($artwork->title)
            ->assertDontSee('Hidden Sketch');

        $this->get(route('marketplace.index', ['q' => 'Quiet Garden']))
            ->assertOk()
            ->assertSee($artwork->title)
            ->assertDontSee('Hidden Sketch');
    }

    public function test_customer_can_register_and_start_a_seller_studio(): void
    {
        $this->post(route('register.store'), [
            'name' => 'New Collector',
            'email' => 'new@example.com',
            'password' => 'creative-password',
            'password_confirmation' => 'creative-password',
        ])->assertRedirect(route('home'));

        $user = User::where('email', 'new@example.com')->firstOrFail();
        $this->assertSame('customer', $user->role);

        $this->actingAs($user)
            ->post(route('seller.activate'))
            ->assertRedirect(route('seller.dashboard'));

        $this->assertSame('seller', $user->fresh()->role);
    }

    public function test_buyer_can_purchase_artwork_in_demo_checkout_and_download_it(): void
    {
        Storage::fake('local');
        $artist = User::factory()->create(['role' => 'seller']);
        $buyer = User::factory()->create();
        $artwork = Artwork::create([
            'artist_id' => $artist->id,
            'title' => 'Dreaming in Pink',
            'slug' => 'dreaming-in-pink',
            'description' => 'A test digital illustration.',
            'category' => 'Sci-Fi',
            'price' => 200,
            'cover_image' => 'images/artwork/mind-space.jpeg',
            'digital_file_path' => 'artworks/files/dreaming.zip',
            'status' => 'published',
        ]);
        Storage::disk('local')->put('artworks/files/dreaming.zip', 'sample digital file');

        $this->actingAs($buyer)->post(route('cart.store', $artwork))->assertRedirect();
        $this->get(route('checkout.index'))->assertOk()->assertSee('Test checkout only');
        $this->post(route('checkout.store'), [
            'billing_name' => $buyer->name,
            'billing_email' => $buyer->email,
            'payment_method' => 'GCash',
        ])->assertRedirect();

        $order = $buyer->orders()->firstOrFail();
        $this->assertSame('demo', $order->payment_status);
        $this->assertDatabaseMissing('cart_items', ['user_id' => $buyer->id]);
        $this->assertSame(1, $artwork->fresh()->sales_count);

        $this->get(route('orders.show', $order))->assertOk()->assertSee('No payment was charged');
        $this->get(route('orders.index'))->assertOk()->assertSee($order->reference);
        $this->actingAs($buyer)->get(route('orders.download', [$order, $order->items->first()]))
            ->assertOk()
            ->assertStreamedContent('sample digital file');

        $anotherBuyer = User::factory()->create();
        $this->actingAs($anotherBuyer)->get(route('orders.show', $order))->assertNotFound();
    }

    public function test_seller_uploads_artwork_for_admin_review(): void
    {
        Storage::fake('public');
        $seller = User::factory()->create(['role' => 'seller']);

        $this->actingAs($seller)->post(route('seller.artworks.store'), [
            'title' => 'New little world',
            'description' => 'An original test illustration.',
            'category' => 'Sci-Fi',
            'price' => 125,
            'cover_image' => UploadedFile::fake()->image('cover.png'),
        ])->assertRedirect(route('seller.dashboard'));

        $artwork = Artwork::firstOrFail();
        $this->assertSame('pending', $artwork->status);
        Storage::disk('public')->assertExists($artwork->cover_image);
    }

    public function test_non_admin_cannot_open_admin_dashboard(): void
    {
        $this->actingAs(User::factory()->create())->get(route('admin.dashboard'))->assertForbidden();
    }
}
