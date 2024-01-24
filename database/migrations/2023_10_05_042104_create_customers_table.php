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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            $table->integer('count_id')->unique();
            $table->string('code', 10)->nullable()->unique();
            $table->string('name', 255);
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->decimal('invoice_due', 14, 2)->default(0.00);
            $table->decimal('wallet',20,2)->default(0.00);
            $table->foreignId('user_id')->constrained();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
