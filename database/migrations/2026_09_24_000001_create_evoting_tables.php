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
        // 1. Pemilih Table (Berdasarkan Skema db_requirements.jpg)
        Schema::create('pemilih', function (Blueprint $table) {
            $table->string('nik', 50)->primary();
            $table->string('rfid', 100)->unique();
            $table->string('nama', 255);
            $table->string('dept', 100);
            $table->enum('pilih', ['T', 'F'])->default('F'); // T = Sudah Memilih, F = Belum Memilih
            $table->timestamp('voted_at')->nullable();
            $table->timestamps();

            $table->index('rfid');
            $table->index('pilih');
        });

        // 2. Kandidat Ketua (Visi & Misi bertipe TEXT sesuai instruksi)
        Schema::create('kandidat_ketua', function (Blueprint $table) {
            $table->string('nik', 50)->primary();
            $table->string('nama', 255);
            $table->string('foto', 255)->nullable();
            $table->text('visi');
            $table->text('misi');
            $table->text('deskripsi')->nullable();
            $table->integer('nomor_urut')->default(1);
            $table->timestamps();

            $table->index('nomor_urut');
        });

        // 3. Kandidat Pengawas (Visi & Misi bertipe TEXT sesuai instruksi)
        Schema::create('kandidat_pengawas', function (Blueprint $table) {
            $table->string('nik', 50)->primary();
            $table->string('nama', 255);
            $table->string('foto', 255)->nullable();
            $table->text('visi');
            $table->text('misi');
            $table->text('deskripsi')->nullable();
            $table->integer('nomor_urut')->default(1);
            $table->timestamps();

            $table->index('nomor_urut');
        });

        // 4. Hasil Pemilihan Ketua (Relasi Hasil -> Pemilih-Nik & Ketua-Nik)
        Schema::create('hasil_ketua', function (Blueprint $table) {
            $table->id();
            $table->string('pemilih_nik', 50)->unique();
            $table->string('ketua_nik', 50);
            $table->timestamps();

            $table->foreign('pemilih_nik')->references('nik')->on('pemilih')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('ketua_nik')->references('nik')->on('kandidat_ketua')->restrictOnDelete()->cascadeOnUpdate();
            $table->index('ketua_nik');
        });

        // 5. Hasil Pemilihan Pengawas (Relasi Hasil -> Pengawas-Nik & Pemilih-Nik)
        Schema::create('hasil_pengawas', function (Blueprint $table) {
            $table->id();
            $table->string('pemilih_nik', 50)->unique();
            $table->string('pengawas_nik', 50);
            $table->timestamps();

            $table->foreign('pemilih_nik')->references('nik')->on('pemilih')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('pengawas_nik')->references('nik')->on('kandidat_pengawas')->restrictOnDelete()->cascadeOnUpdate();
            $table->index('pengawas_nik');
        });

        // 6. Activity Logs (Audit CRUD & Transaksi Sistem)
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('user_type', 50)->default('system'); // admin, voter, system
            $table->string('user_identifier', 100)->nullable(); // NIK, ID, atau Email
            $table->string('action', 100); // LOGIN, VOTE, INSERT, UPDATE, DELETE, RESET, IMPORT
            $table->string('module', 100); // VOTING, KANDIDAT_KETUA, KANDIDAT_PENGAWAS, PEMILIH, REPORTS
            $table->text('description');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index('module');
            $table->index('action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('hasil_pengawas');
        Schema::dropIfExists('hasil_ketua');
        Schema::dropIfExists('kandidat_pengawas');
        Schema::dropIfExists('kandidat_ketua');
        Schema::dropIfExists('pemilih');
    }
};
