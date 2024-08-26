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
        Schema::create('onboarding_users', function (Blueprint $table) {
            $table->id()->comment('id пользователя');
            $table->string('role')->comment('роль');
            $table->string('name')->comment('имя');
            $table->string('phone')->comment('телефон');
            $table->string('password')->comment('хешированнный пароль');
            $table->integer('phone_code')->nullable()->comment('код из смс для подтверждения телефона');
            $table->foreignIdFor(\App\Models\User::class)->nullable()->comment('id user в случае успешной регистрации');
            $table->timestamp('phone_code_datetime')->nullable()->comment('время отправки кода из смс для подтверждения телефона');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('onboarding_users');
    }
};
