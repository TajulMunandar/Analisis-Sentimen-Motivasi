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
        Schema::create('sentiments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tweet_id')->constrained('tweets')->onDelete('restrict')->onUpdate('cascade');
            $table->string('sentiment');
            $table->float('confidence');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sentiments');
    }
};
