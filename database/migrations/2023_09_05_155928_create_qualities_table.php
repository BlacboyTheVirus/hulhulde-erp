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
        Schema::create('qualities', function (Blueprint $table) {
            $table->id();
            $table->integer('count_id')->unique();
            $table->string('code')->unique();

            $table->foreignId('procurement_id')->constrained()->onDelete('cascade');

            $table->date('analysis_date');

            $table->decimal('moisture', 4,2)->nullable();
            $table->decimal('broken', 4,2)->nullable();
            $table->decimal('crackness', 4,2)->nullable();
            $table->decimal('immature', 4,2)->nullable();
            $table->decimal('red_grain', 4,2)->nullable();
            $table->decimal('green_grain', 4,2)->nullable();
            $table->decimal('yellow_grain', 4,2)->nullable();
            $table->decimal('discolour', 4,2)->nullable();
            $table->decimal('short_grain', 4,2)->nullable();
            $table->decimal('paddy_length', 4,2)->nullable();
            $table->decimal('bran_length', 4,2)->nullable();
            $table->decimal('milled_length', 4,2)->nullable();
            $table->decimal('impurity', 4,2)->nullable();

            $table->integer('rejected_bags')->default(0);
            $table->decimal('rejected_weight', 6, 2)->default(0);

            $table->decimal('recommended_price', 10, 2);

            $table->string('analyst');
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
        Schema::dropIfExists('qualities');
    }
};
