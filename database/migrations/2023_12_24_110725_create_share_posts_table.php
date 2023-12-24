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
        Schema::create('share_posts', function (Blueprint $table) {
            $table->id();
            $table->text('title')->nullable();
            $table->foreignId('post_id')->references('id')->on('posts')->cascadeOnUpdate()->cascadeOnDelete();
            //$table->foreignId('share_from')->references('id')->on('posts')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('share_by')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('share_posts');
    }
};
