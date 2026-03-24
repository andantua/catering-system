<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ward_id')->constrained()->onDelete('cascade');
            $table->foreignId('diet_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(0);
            $table->date('order_date');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
            
            $table->unique(['ward_id', 'diet_id', 'order_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};