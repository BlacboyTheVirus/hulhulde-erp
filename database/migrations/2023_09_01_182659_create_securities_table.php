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

            Schema::create('securities', function (Blueprint $table) {
                $table->id();
                $table->integer('count_id')->unique();
                $table->string('code')->unique();

                $table->foreignId('procurement_id')->constrained()->onDelete('cascade');

                $table->date('checkin_date');
                $table->string('vehicle_no');
                $table->string('driver');
                $table->integer('bags');
                $table->string('security');
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
        Schema::dropIfExists('securities');
    }
};
