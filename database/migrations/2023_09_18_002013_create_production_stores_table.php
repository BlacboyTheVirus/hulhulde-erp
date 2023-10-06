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
        Schema::create('production_stores', function (Blueprint $table) {
            $table->id();

            $table->foreignId('production_id')->constrained()->onDelete('cascade');
            $table->date('received_date');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');

            $table->decimal('weight', 6,2);
            $table->integer('bags');

            $table->string('received_by');

            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_stores');
    }
};
