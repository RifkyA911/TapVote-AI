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
        Schema::table('doorprizes', function (Blueprint $table) {
            $table->string('image')->nullable()->after('icon');
            $table->text('description')->nullable()->after('title');
        });

        Schema::table('doorprize_winners', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('won_at'); // pending, accepted, rejected, other
            $table->text('status_note')->nullable()->after('status');
            $table->timestamp('received_at')->nullable()->after('status_note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doorprize_winners', function (Blueprint $table) {
            $table->dropColumn(['status', 'status_note', 'received_at']);
        });

        Schema::table('doorprizes', function (Blueprint $table) {
            $table->dropColumn(['image', 'description']);
        });
    }
};
