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
        Schema::table('pemilih', function (Blueprint $table) {
            $table->boolean('can_raffle')->default(true)->after('pilih');
            $table->index('can_raffle');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemilih', function (Blueprint $table) {
            $table->dropIndex(['can_raffle']);
            $table->dropColumn('can_raffle');
        });
    }
};
