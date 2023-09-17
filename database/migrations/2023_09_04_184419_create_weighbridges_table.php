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
        Schema::create('weighbridges', function (Blueprint $table) {
            $table->id();
            $table->integer('count_id')->unique();
            $table->string('code')->unique();

            $table->foreignId('procurement_id')->constrained()->onDelete('cascade');

            $table->date('first_date');
            $table->string('first_time');
            $table->decimal('first_weight', 6, 2);

            $table->date('second_date');
            $table->string('second_time');
            $table->decimal('second_weight', 6, 2);

            $table->decimal('weight', 6,2);
            $table->integer('bags');

            $table->string('operator');
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
        Schema::dropIfExists('weighbridges');
    }
};
