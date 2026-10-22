<?php

namespace Database\Seeders;

use App\Models\Artwork;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@admin.pax'],
            ['name' => 'PaxiliArt Admin', 'username' => 'paxiliart-admin', 'password' => 'password', 'role' => 'admin'],
        );
        $neith = User::updateOrCreate(
            ['email' => 'neith@paxiliart.test'],
            ['name' => 'neith', 'username' => 'neith', 'password' => 'password', 'role' => 'seller', 'bio' => 'Tiny worlds, big feelings. I make playful digital illustrations inspired by space, old stories, and bright colors.'],
        );
        $kim = User::updateOrCreate(
            ['email' => 'kim@paxiliart.test'],
            ['name' => 'kim', 'username' => 'kim', 'password' => 'password', 'role' => 'seller', 'bio' => 'Collecting strange little faces from faraway places.'],
        );
        $jay = User::updateOrCreate(
            ['email' => 'jay@paxiliart.test'],
            ['name' => 'jay', 'username' => 'jay', 'password' => 'password', 'role' => 'seller', 'bio' => 'Digital art and other transmissions from the unknown.'],
        );
        $brix = User::updateOrCreate(
            ['email' => 'brix@paxiliart.test'],
            ['name' => 'brix', 'username' => 'brix', 'password' => 'password', 'role' => 'seller', 'bio' => 'Character artist, world builder, and creature enthusiast.'],
        );
        $buyer = User::updateOrCreate(
            ['email' => 'collector@paxiliart.test'],
            ['name' => 'Jamie Collector', 'username' => 'jamie-collector', 'password' => 'password', 'role' => 'customer'],
        );

        $demoCustomers = collect([$buyer]);
        for ($number = 1; $number <= 117; $number++) {
            $suffix = str_pad((string) $number, 3, '0', STR_PAD_LEFT);
            $demoCustomers->push(User::updateOrCreate(
                ['email' => "collector+{$suffix}@paxiliart.test"],
                ['name' => "Art Collector {$suffix}", 'username' => "art-collector-{$suffix}", 'password' => 'password', 'role' => 'customer'],
            ));
        }

        $pieces = [
            ['artist' => $neith, 'title' => 'Mind Space', 'slug' => 'mind-space', 'category' => 'Sci-Fi', 'cover_image' => 'images/artwork/mind-space.jpeg', 'dimensions' => '3000 × 3000 px', 'sales_count' => 24, 'is_featured' => true, 'description' => 'A vibrant sci-fi digital artwork featuring an alien astronaut exploring the universe.'],
            ['artist' => $kim, 'title' => 'Big brain alien', 'slug' => 'big-brain-alien', 'category' => 'Sci-Fi', 'cover_image' => 'images/artwork/big-brain-alien.jpeg', 'dimensions' => '3000 × 3000 px', 'sales_count' => 18, 'is_featured' => true, 'description' => 'A vivid visitor from somewhere far away, rendered in electric color and tiny cosmic details.'],
            ['artist' => $jay, 'title' => 'Stare at U', 'slug' => 'stare-at-u', 'category' => 'Sci-Fi', 'cover_image' => 'images/artwork/stare-at-u.jpeg', 'dimensions' => '3000 × 3000 px', 'sales_count' => 12, 'is_featured' => true, 'description' => 'An intense close-up portrait from the other side of the stars.'],
            ['artist' => $brix, 'title' => 'Alien Beauty', 'slug' => 'alien-beauty', 'category' => 'Sci-Fi', 'cover_image' => 'images/artwork/portrait-emerald.jpeg', 'dimensions' => '3000 × 3000 px', 'sales_count' => 9, 'is_featured' => true, 'description' => 'A richly textured alien portrait with an unexpected gaze and a bright, otherworldly palette.'],
            ['artist' => $neith, 'title' => 'Forest Daydream', 'slug' => 'forest-daydream', 'category' => 'Fantasy', 'cover_image' => 'images/artwork/forest-dreamer.jpeg', 'dimensions' => '2400 × 3600 px', 'sales_count' => 8, 'is_featured' => false, 'description' => 'A quiet daydream tucked into a luminous green forest.'],
            ['artist' => $kim, 'title' => 'Anime Wanderer', 'slug' => 'anime-wanderer', 'category' => 'Anime', 'cover_image' => 'images/artwork/portrait-orange.jpeg', 'dimensions' => '2000 × 3200 px', 'sales_count' => 6, 'is_featured' => false, 'description' => 'A bright-eyed traveler from a story still waiting to be told.'],
            ['artist' => $jay, 'title' => 'Monster Parade', 'slug' => 'monster-parade', 'category' => 'Monsters', 'cover_image' => 'images/artwork/cosmic-orbit.jpeg', 'dimensions' => '3000 × 3000 px', 'sales_count' => 4, 'is_featured' => false, 'description' => 'A colorful crowd of curious little creatures.'],
            ['artist' => $brix, 'title' => 'Garden Visitor', 'slug' => 'garden-visitor', 'category' => 'Others', 'cover_image' => 'images/artwork/portrait-gold.jpeg', 'dimensions' => '2400 × 3600 px', 'sales_count' => 3, 'is_featured' => false, 'description' => 'A fantastical visitor caught in a moment of quiet wonder.'],
        ];

        foreach ($pieces as $piece) {
            $artist = $piece['artist'];
            unset($piece['artist']);
            Artwork::updateOrCreate(
                ['slug' => $piece['slug']],
                ['artist_id' => $artist->id, ...$piece, 'price' => 200, 'status' => 'published', 'digital_file_path' => null],
            );
        }

        $categories = ['Sci-Fi', 'Anime', 'Fantasy', 'Monsters', 'Others'];
        $images = [
            'images/artwork/mind-space.jpeg',
            'images/artwork/big-brain-alien.jpeg',
            'images/artwork/stare-at-u.jpeg',
            'images/artwork/portrait-emerald.jpeg',
            'images/artwork/forest-dreamer.jpeg',
            'images/artwork/portrait-orange.jpeg',
            'images/artwork/cosmic-orbit.jpeg',
            'images/artwork/portrait-gold.jpeg',
        ];
        $artists = [$neith, $kim, $jay, $brix];

        for ($number = 9; $number <= 50; $number++) {
            $imageIndex = ($number - 9) % count($images);
            $artist = $artists[($number - 9) % count($artists)];
            Artwork::updateOrCreate(
                ['slug' => sprintf('color-study-%02d', $number)],
                [
                    'artist_id' => $artist->id,
                    'title' => sprintf('Color study %02d', $number),
                    'description' => 'An original digital artwork from the PaxiliArt studio collection.',
                    'category' => $categories[($number - 9) % count($categories)],
                    'price' => 200,
                    'cover_image' => $images[$imageIndex],
                    'dimensions' => '3000 × 3000 px',
                    'status' => 'published',
                    'sales_count' => 50 - $number,
                    'is_featured' => false,
                ],
            );
        }

        $artworks = Artwork::where('status', 'published')->orderBy('id')->get();
        $paymentMethods = ['GCash', 'Maya', 'Credit/Debit Card', 'PayPal'];

        foreach ($artworks as $index => $artwork) {
            $orderNumber = $index + 1;
            $createdAt = now()->subDays($index % 7);
            $order = Order::updateOrCreate(
                ['reference' => sprintf('PA-DEMO-%04d', $orderNumber)],
                [
                    'buyer_id' => $demoCustomers[$index % $demoCustomers->count()]->id,
                    'billing_name' => $demoCustomers[$index % $demoCustomers->count()]->name,
                    'billing_email' => $demoCustomers[$index % $demoCustomers->count()]->email,
                    'payment_method' => $paymentMethods[$index % count($paymentMethods)],
                    'payment_status' => 'demo',
                    'status' => 'completed_demo',
                    'subtotal' => $artwork->price,
                    'completed_at' => $createdAt,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ],
            );
            $order->items()->updateOrCreate(
                ['artwork_id' => $artwork->id],
                [
                    'title' => $artwork->title,
                    'artist_name' => $artwork->artist->name,
                    'cover_image' => $artwork->cover_image,
                    'price' => $artwork->price,
                ],
            );
        }

        for ($orderNumber = 51; $orderNumber <= 120; $orderNumber++) {
            $createdAt = now()->subDays($orderNumber % 7);
            $customer = $demoCustomers[$orderNumber % $demoCustomers->count()];
            $artwork = $artworks[($orderNumber - 1) % $artworks->count()];
            $order = Order::updateOrCreate(
                ['reference' => sprintf('PA-DEMO-%04d', $orderNumber)],
                [
                    'buyer_id' => $customer->id,
                    'billing_name' => $customer->name,
                    'billing_email' => $customer->email,
                    'payment_method' => $paymentMethods[$orderNumber % count($paymentMethods)],
                    'payment_status' => 'pending',
                    'status' => 'pending',
                    'subtotal' => $artwork->price,
                    'completed_at' => null,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ],
            );
            $order->items()->updateOrCreate(
                ['artwork_id' => $artwork->id],
                [
                    'title' => $artwork->title,
                    'artist_name' => $artwork->artist->name,
                    'cover_image' => $artwork->cover_image,
                    'price' => $artwork->price,
                ],
            );
        }
    }
}
