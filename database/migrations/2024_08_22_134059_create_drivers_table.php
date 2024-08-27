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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id()->comment('id водителя');
            $table->foreignId('user_id')->constrained(\App\Models\User::class);
            $table->tinyInteger('is_activate')->default(0)->comment('он активный?');
            $table->json('location_activate')->nullable()->comment('его локация (широта и долгота)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
