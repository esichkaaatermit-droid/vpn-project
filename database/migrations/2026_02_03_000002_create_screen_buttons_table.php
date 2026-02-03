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
        Schema::create('screen_buttons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('screen_id')->constrained('screens')->onDelete('cascade');
            $table->string('text')->comment('Текст кнопки');
            $table->string('next_screen_key')->nullable()->comment('Ключ следующего экрана');
            $table->integer('order')->default(0)->comment('Порядок отображения');
            $table->timestamps();

            $table->index('screen_id');
            $table->index('next_screen_key');
            $table->index('order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('screen_buttons');
    }
};
