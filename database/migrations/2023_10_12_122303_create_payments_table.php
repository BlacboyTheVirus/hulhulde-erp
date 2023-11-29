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
        Schema::create('invoice_payments', function (Blueprint $table) {

            $table->id();
            $table->integer('count_id')->unique();
            $table->string('code')->unique();

            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->date('payment_date');
            $table->string('payment_type')->default('transfer');
            $table->decimal('amount', 10 , 2);
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
        Schema::dropIfExists('invoice_payments');
    }
};
