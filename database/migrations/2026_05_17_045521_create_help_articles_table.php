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
        Schema::create('help_articles', function (Blueprint $table) {

            $table->id();

            $table->string('title', 255);

            $table->string('slug', 255)->unique();

            $table->unsignedInteger('help_article_category_id');

            $table->text('summary')->nullable();

            $table->longText('content');

            $table->boolean('is_published')->default(0);

            $table->timestamps();

            $table->index('help_article_category_id');

            $table->index('is_published');

            $table->fullText(['title', 'content']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('help_articles');
    }
};
