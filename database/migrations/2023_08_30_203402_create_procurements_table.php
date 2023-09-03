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
        Schema::create('procurements', function (Blueprint $table) {
            $table->id();
            $table->integer('count_id')->unique();
            $table->string('code')->unique();

            $table->foreignId('supplier_id')->constrained();
            $table->foreignId('input_id')->constrained();

            $table->date('procurement_date');
            $table->decimal('expected_weight', 6, 2);
            $table->integer('expected_bags');
            $table->string('location');
            $table->text('note')->nullable();
            $table->enum('status', ['open', 'close'])->default('open');
            $table->enum('next' , ['security', 'weighbridge','quality','warehouse','account'])->default('security');
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procurements');
    }
};
