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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('phone_code')->nullable()->comment('код из смс для подтверждения телефона при смене пароля');
            $table->string('new_password')->nullable()->comment('новый пароль юзера');
            $table->timestamp('phone_code_datetime')->nullable()->comment('время отправки кода из смс для подтверждения телефона при смене пароля');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone_code', 'phone_code_datetime', 'new_password']);
        });
    }
};
