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
        Schema::create('production_warehouses', function (Blueprint $table) {
            $table->id();
            $table->integer('count_id')->unique();
            $table->string('code')->unique();

            $table->foreignId('production_id')->constrained()->onDelete('cascade');

            $table->date('release_date');
            $table->decimal('weight', 6,2);
            $table->string('released_by');
            $table->text('note');

            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_warehouses');
    }
};
