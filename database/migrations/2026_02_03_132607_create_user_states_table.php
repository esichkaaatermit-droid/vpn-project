<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Миграция для таблицы user_states.
 * 
 * Хранит текущее состояние пользователя в боте:
 * - На каком экране находится
 * - Дополнительные данные сессии
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_states', function (Blueprint $table) {
            $table->id();
            
            // Telegram chat_id — уникальный идентификатор чата
            $table->bigInteger('chat_id')->unique();
            
            // Ключ текущего экрана (ссылается на screens.key)
            $table->string('current_screen_key', 255)->nullable();
            
            // Дополнительные данные состояния (JSON)
            $table->json('data')->nullable()->comment('Дополнительные данные сессии');
            
            $table->timestamps();
            
            // Индексы
            $table->index('current_screen_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_states');
    }
};
