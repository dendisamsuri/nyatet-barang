<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('electricity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            $table->date('log_date');
            $table->decimal('before_kwh', 10, 2)->default(0);
            $table->decimal('amount', 12, 2)->default(0);
            $table->decimal('purchased_kwh', 10, 2)->nullable();
            $table->decimal('after_kwh', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['item_id', 'log_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('electricity_logs');
    }
};
