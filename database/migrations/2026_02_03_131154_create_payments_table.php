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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('tariff_key')->comment('Ключ тарифа (например: tariffs.pricing.1month)');
            $table->string('tariff_name')->comment('Название тарифа');
            $table->decimal('amount', 10, 2)->comment('Сумма оплаты');
            $table->enum('status', ['pending', 'paid', 'expired', 'cancelled'])->default('pending');
            $table->timestamp('expires_at')->comment('Время истечения ожидания оплаты');
            $table->timestamp('paid_at')->nullable()->comment('Время оплаты');
            $table->string('external_id')->nullable()->comment('ID платежа во внешней системе');
            $table->timestamps();

            $table->index('user_id');
            $table->index('status');
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
