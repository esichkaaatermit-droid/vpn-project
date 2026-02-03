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
        Schema::create('screens', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->comment('Уникальный ключ экрана (например: faq.start, tariffs.pricing)');
            $table->string('title')->nullable()->comment('Заголовок экрана');
            $table->text('text')->comment('Текст сообщения');
            $table->string('handler_id')->nullable()->comment('Строковый ID обработчика для дополнительной логики');
            $table->timestamps();

            $table->index('key');
            $table->index('handler_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('screens');
    }
};
