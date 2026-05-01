<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('reservation_id')
                ->constrained('reservations')
                ->onDelete('cascade');

            $table->string('payment_kind', 20)->default('acompte');

            $table->string('payment_method'); // Waafi, Dmoney, Cash, Card...
            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('DJF');

            $table->string('transaction_id')->nullable()->unique();

            $table->enum('status', [
                'pending',
                'accepted',
                'refused',
                'refunded',
            ])->default('pending');

            $table->timestamp('paid_at')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
