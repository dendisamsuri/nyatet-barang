<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fuel_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('fuel_type_id')->constrained()->restrictOnDelete();
            $table->date('fuel_date');
            $table->decimal('amount', 12, 2)->default(0);
            $table->decimal('liter', 8, 3)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['item_id', 'fuel_date']);
            $table->index('fuel_type_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fuel_logs');
    }
};
