<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('artworks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artist_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('category')->index();
            $table->decimal('price', 10, 2);
            $table->string('cover_image');
            $table->string('digital_file_path')->nullable();
            $table->string('dimensions')->nullable();
            $table->string('status')->default('pending')->index();
            $table->unsignedInteger('sales_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });

        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('artwork_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'artwork_id']);
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buyer_id')->constrained('users')->cascadeOnDelete();
            $table->string('reference')->unique();
            $table->string('billing_name');
            $table->string('billing_email');
            $table->string('payment_method');
            $table->string('payment_status')->default('demo');
            $table->string('status')->default('completed_demo');
            $table->decimal('subtotal', 10, 2);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('artwork_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('artist_name');
            $table->string('cover_image');
            $table->string('digital_file_path')->nullable();
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('artworks');
    }
};
