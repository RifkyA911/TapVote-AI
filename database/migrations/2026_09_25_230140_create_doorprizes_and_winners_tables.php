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
        // 1. Master Doorprize Rewards
        Schema::create('doorprizes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category')->default('Utama'); // Utama, Hiburan, Elektronik, Voucher, dll
            $table->integer('quantity')->default(1);
            $table->string('sponsor')->nullable();
            $table->string('icon')->default('gift'); // gift, star, award, trophy, sparkles
            $table->timestamps();
        });

        // 2. Log Pemenang Doorprize
        Schema::create('doorprize_winners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doorprize_id')->constrained('doorprizes')->cascadeOnDelete();
            $table->string('nik', 50);
            $table->foreign('nik')->references('nik')->on('pemilih')->cascadeOnDelete();
            $table->timestamp('won_at')->useCurrent();
            $table->timestamps();

            $table->unique(['doorprize_id', 'nik']); // Satu orang tidak boleh menang reward yg sama berkali-kali
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doorprize_winners');
        Schema::dropIfExists('doorprizes');
    }
};
