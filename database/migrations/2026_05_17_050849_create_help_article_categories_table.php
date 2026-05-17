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
        Schema::create('help_article_categories', function (Blueprint $table) {

            $table->id();

            $table->string('category_name', 100)->unique();

            $table->string('slug', 120)->unique();

            $table->unsignedInteger('sort_order')->default(0);

            $table->boolean('is_active')->default(1);

            $table->timestamps();

            $table->index('is_active');

            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('help_article_categories');
    }
};
