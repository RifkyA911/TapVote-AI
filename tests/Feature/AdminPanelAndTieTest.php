<?php

namespace Tests\Feature;

use App\Models\AppSetting;
use App\Models\HasilKetua;
use App\Models\HasilPengawas;
use App\Models\KandidatKetua;
use App\Models\KandidatPengawas;
use App\Models\Pemilih;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelAndTieTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin_test@tapvote.ai',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);
    }

    public function test_voting_status_can_be_updated_by_admin(): void
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('admin.voting.status'), [
            'status' => 'PAUSED',
        ]);

        $response->assertRedirect();
        $this->assertEquals('PAUSED', AppSetting::get('voting_status'));

        $this->post(route('admin.voting.status'), [
            'status' => 'STOPPED',
        ]);
        $this->assertEquals('STOPPED', AppSetting::get('voting_status'));
    }

    public function test_voter_tap_is_blocked_when_voting_is_paused_or_stopped(): void
    {
        $voter = Pemilih::create([
            'nik' => '012345',
            'rfid' => '12345678',
            'nama' => 'Voter Test',
            'dept' => 'ICT',
            'pilih' => 'F',
        ]);

        // 1. Paused
        AppSetting::set('voting_status', 'PAUSED');
        $resPaused = $this->post(route('voter.tap.process'), ['rfid' => '12345678']);
        $resPaused->assertSessionHas('error');

        // 2. Stopped
        AppSetting::set('voting_status', 'STOPPED');
        $resStopped = $this->post(route('voter.tap.process'), ['rfid' => '12345678']);
        $resStopped->assertSessionHas('error');
    }

    public function test_rekapitulasi_detects_tie_properly(): void
    {
        $this->actingAs($this->admin);

        // Buat 2 kandidat dengan perolehan suara imbang (1 - 1)
        $k1 = KandidatKetua::create(['nik' => 'K01', 'nama' => 'Calon A', 'nomor_urut' => 1, 'visi' => 'Visi A', 'misi' => 'Misi A']);
        $k2 = KandidatKetua::create(['nik' => 'K02', 'nama' => 'Calon B', 'nomor_urut' => 2, 'visi' => 'Visi B', 'misi' => 'Misi B']);

        $v1 = Pemilih::create(['nik' => '000001', 'rfid' => 'RF01', 'nama' => 'Voter 1', 'dept' => 'Cor', 'pilih' => 'T']);
        $v2 = Pemilih::create(['nik' => '000002', 'rfid' => 'RF02', 'nama' => 'Voter 2', 'dept' => 'Cor', 'pilih' => 'T']);

        HasilKetua::create(['pemilih_nik' => $v1->nik, 'ketua_nik' => $k1->nik]);
        HasilKetua::create(['pemilih_nik' => $v2->nik, 'ketua_nik' => $k2->nik]);

        $response = $this->get(route('admin.reports.ketua'));
        $response->assertStatus(200);
        $response->assertViewHas('isSeri', true);
        $response->assertViewHas('pemenang', null);
        $response->assertSee('HASIL SERI / DRAW');
    }

    public function test_doorprize_page_renders_with_null_initial_winner(): void
    {
        $this->actingAs($this->admin);

        Pemilih::create(['nik' => '000001', 'rfid' => 'RF01', 'nama' => 'Eligible Voter', 'dept' => 'Cor', 'pilih' => 'T']);

        $response = $this->get(route('admin.reports.doorprize'));
        $response->assertStatus(200);
        $response->assertViewHas('pemenang', null);
        $response->assertSee('Siap Memulai Undian');
        $response->assertSee('Mulai Putar Undian (10 Detik)');
    }

    public function test_admin_can_save_gemini_api_key(): void
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('admin.settings.gemini-key'), [
            'gemini_api_key' => 'AIzaSyTestKey123',
        ]);

        $response->assertRedirect();
        $this->assertEquals('AIzaSyTestKey123', AppSetting::get('gemini_api_key'));
    }
}
