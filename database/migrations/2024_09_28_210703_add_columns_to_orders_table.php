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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('type')->default('tow_truck')->comment('Тип заказа: tow_truck (эвакуатор) в будущем будут другие');
            $table->json('location_start')->comment('Стартовая локация заказа (широта и долгота, а также адрес текстом)');
            $table->text('client_comment')->nullable()->comment('Комментарий клиента к заказу');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['type', 'location_start', 'client_comment']);
        });
    }
};
