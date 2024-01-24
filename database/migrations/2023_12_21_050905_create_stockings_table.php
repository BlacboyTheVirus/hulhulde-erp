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
        Schema::create('stockings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parts_id')->constrained()->onDelete('cascade');

            $table->date('stocking_date');
            $table->decimal('quantity',6,2);
            $table->decimal('unit_cost',16,2);
            $table->string('source');
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
        Schema::dropIfExists('stockings');
    }
};
