<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            $table->date('service_date');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['item_id', 'service_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
