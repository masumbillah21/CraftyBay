<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('shot_desc')->nullable();
            $table->text('desc')->nullable();
            $table->string('remark')->default('new');
            $table->float('reqular_price')->default(1);
            $table->float('sales_price')->nullable();
            $table->string('featured_image');
            $table->json("gallery_images")->nullable();
            $table->float('weight')->default(0);
            $table->float('length')->default(0);
            $table->float('width')->default(0);
            $table->float('height')->default(0);
            $table->integer('stock')->default(1);
            $table->foreignId('brand_id')->constrained()->onDelete('restrict');
            $table->boolean('is_active')->default(1);
            $table->boolean('is_featured')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
