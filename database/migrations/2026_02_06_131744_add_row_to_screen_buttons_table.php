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
        Schema::table('screen_buttons', function (Blueprint $table) {
            $table->integer('row')->default(0)->after('order')->comment('Номер ряда для группировки кнопок (0 = отдельный ряд)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('screen_buttons', function (Blueprint $table) {
            $table->dropColumn('row');
        });
    }
};
