<?php

namespace Tests\Feature;

use App\Models\KandidatKetua;
use App\Models\KandidatPengawas;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CandidatePhotoUploadTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin_photo@tapvote.ai',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);
    }

    public function test_can_upload_profile_photo_for_kandidat_ketua_to_separated_storage_folder(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin);

        $file = UploadedFile::fake()->create('calon_ketua_1.jpg', 200, 'image/jpeg');

        $response = $this->post(route('admin.ketua.store'), [
            'nik' => 'KT99',
            'nama' => 'Budi Santoso, M.M.',
            'nomor_urut' => 1,
            'visi' => 'Visi Koperasi Unggul dan Amanah',
            'misi' => 'Misi 1. Pelayanan Cepat 2. Transparansi Keuangan',
            'deskripsi' => 'Pengalaman perbankan 15 tahun',
            'foto' => $file,
        ]);

        $response->assertRedirect(route('admin.ketua.index'));

        $kandidat = KandidatKetua::where('nik', 'KT99')->firstOrFail();
        $this->assertNotNull($kandidat->foto);
        $this->assertStringStartsWith('/storage/kandidat_ketua/', $kandidat->foto);

        $relativePath = str_replace('/storage/', '', $kandidat->foto);
        Storage::disk('public')->assertExists($relativePath);
    }

    public function test_can_upload_profile_photo_for_kandidat_pengawas_to_separated_storage_folder(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin);

        $file = UploadedFile::fake()->create('calon_pengawas_1.jpg', 200, 'image/jpeg');

        $response = $this->post(route('admin.pengawas.store'), [
            'nik' => 'PW99',
            'nama' => 'Dra. Hj. Siti Aminah, Ak.',
            'nomor_urut' => 1,
            'visi' => 'Pengawasan Kredibel dan Akuntabel',
            'misi' => 'Misi 1. Audit Rutin 2. Kepatuhan SOP',
            'deskripsi' => 'Pengalaman auditor senior',
            'foto' => $file,
        ]);

        $response->assertRedirect(route('admin.pengawas.index'));

        $kandidat = KandidatPengawas::where('nik', 'PW99')->firstOrFail();
        $this->assertNotNull($kandidat->foto);
        $this->assertStringStartsWith('/storage/kandidat_pengawas/', $kandidat->foto);

        $relativePath = str_replace('/storage/', '', $kandidat->foto);
        Storage::disk('public')->assertExists($relativePath);
    }

    public function test_updating_kandidat_photo_replaces_old_photo_in_storage(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin);

        // 1. Upload initial photo
        $oldFile = UploadedFile::fake()->create('initial_ketua.jpg', 150, 'image/jpeg');
        $this->post(route('admin.ketua.store'), [
            'nik' => 'KT100',
            'nama' => 'Calon Ketua Update',
            'nomor_urut' => 2,
            'visi' => 'Visi Awal',
            'misi' => 'Misi Awal',
            'foto' => $oldFile,
        ]);

        $kandidat = KandidatKetua::where('nik', 'KT100')->firstOrFail();
        $oldStoragePath = str_replace('/storage/', '', $kandidat->foto);
        Storage::disk('public')->assertExists($oldStoragePath);

        // 2. Upload replacement photo
        $newFile = UploadedFile::fake()->create('new_ketua.jpg', 250, 'image/jpeg');
        $response = $this->put(route('admin.ketua.update', 'KT100'), [
            'nama' => 'Calon Ketua Terupdate',
            'nomor_urut' => 2,
            'visi' => 'Visi Baru',
            'misi' => 'Misi Baru',
            'foto' => $newFile,
        ]);

        $response->assertRedirect(route('admin.ketua.index'));

        $kandidat->refresh();
        $newStoragePath = str_replace('/storage/', '', $kandidat->foto);

        // Old photo must be deleted
        Storage::disk('public')->assertMissing($oldStoragePath);
        // New photo must exist in storage
        Storage::disk('public')->assertExists($newStoragePath);
        $this->assertStringStartsWith('/storage/kandidat_ketua/', $kandidat->foto);
    }

    public function test_updating_pengawas_photo_and_destroying_cleans_storage(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin);

        // 1. Upload initial photo
        $oldFile = UploadedFile::fake()->create('initial_pengawas.jpg', 150, 'image/jpeg');
        $this->post(route('admin.pengawas.store'), [
            'nik' => 'PW100',
            'nama' => 'Calon Pengawas Test',
            'nomor_urut' => 2,
            'visi' => 'Visi Awal',
            'misi' => 'Misi Awal',
            'foto' => $oldFile,
        ]);

        $kandidat = KandidatPengawas::where('nik', 'PW100')->firstOrFail();
        $this->assertStringStartsWith('/storage/kandidat_pengawas/', $kandidat->foto);
        $oldStoragePath = str_replace('/storage/', '', $kandidat->foto);
        Storage::disk('public')->assertExists($oldStoragePath);

        // 2. Delete candidate -> photo should be deleted from storage
        $response = $this->delete(route('admin.pengawas.destroy', 'PW100'));
        $response->assertRedirect(route('admin.pengawas.index'));

        Storage::disk('public')->assertMissing($oldStoragePath);
        $this->assertDatabaseMissing('kandidat_pengawas', ['nik' => 'PW100']);
    }
}
