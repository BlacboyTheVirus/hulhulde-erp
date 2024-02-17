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
        Schema::create('parts', function (Blueprint $table) {
            $table->id();
            $table->integer('count_id')->unique();
            $table->string('code')->unique();
            $table->string('name')->unique();

            $table->string('description');
            $table->string('unit',);
            $table->decimal('quantity',6,2)->default(0.00);
            $table->decimal('restock_level',6,2);
            $table->enum('status', [0, 1])->default(1);

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
        Schema::dropIfExists('parts');
    }
};
