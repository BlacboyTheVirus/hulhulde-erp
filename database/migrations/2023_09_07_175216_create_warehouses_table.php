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
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->integer('count_id')->unique();
            $table->string('code')->unique();

            $table->foreignId('procurement_id')->constrained()->onDelete('cascade');

            $table->date('receipt_date');
            $table->integer('bags');
            $table->decimal('weight', 6,2);
            $table->string('received_by');
            $table->text('note')->nullable();

            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};
