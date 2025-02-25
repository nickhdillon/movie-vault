<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vaults', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->integer('vault_id');
            $table->string('imdb_id')->nullable();
            $table->string('vault_type')->nullable();
            $table->string('title')->nullable();
            $table->string('original_title')->nullable();
            $table->longText('overview');
            $table->string('backdrop_path')->nullable();
            $table->string('poster_path')->nullable();
            $table->string('release_date')->nullable();
            $table->string('first_air_date')->nullable();
            $table->boolean('on_wishlist')->default(0);
            $table->string('rating')->nullable();
            $table->string('genres')->nullable();
            $table->integer('runtime')->nullable();
            $table->integer('seasons')->nullable();
            $table->string('actors')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vaults');
    }
};
