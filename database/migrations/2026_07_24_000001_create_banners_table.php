<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title_en');
            $table->string('title_km')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_km')->nullable();
            $table->string('badge_en')->nullable();
            $table->string('badge_km')->nullable();
            $table->string('link')->nullable();
            $table->string('button_text_en')->nullable();
            $table->string('button_text_km')->nullable();
            $table->string('image_path')->nullable();
            $table->string('gradient_css')->nullable();
            $table->string('icon')->nullable();
            $table->string('status')->default('active');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
