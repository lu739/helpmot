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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('client_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('driver_id')->nullable()->constrained('users')->onDelete('restrict');

            $table->string('status')->default(\App\Enum\OrderStatus::ACTIVE->value)->comment('статус заказа');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
