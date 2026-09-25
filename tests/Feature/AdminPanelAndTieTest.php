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
        $response->assertSee('Undian Doorprize Anggota');
        $response->assertSee('Putar Undian Sekarang');
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

    public function test_admin_can_export_recap_excel(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.dashboard.export.excel'));
        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('content-type'), 'text/csv'));
    }

    public function test_admin_can_export_recap_pdf(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.dashboard.export.pdf'));
        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('content-type'), 'application/pdf'));
    }

    public function test_admin_can_update_doorprize_winner_status(): void
    {
        $this->actingAs($this->admin);

        $doorprize = \App\Models\Doorprize::create([
            'title' => 'Rice Cooker Smart',
            'category' => 'Elektronik',
            'quantity' => 1
        ]);

        $pemilih = Pemilih::create([
            'nik' => '990011',
            'rfid' => 'RF990011',
            'nama' => 'Pemenang Uji Coba',
            'dept' => 'IT',
            'pilih' => 'T'
        ]);

        $winner = \App\Models\DoorprizeWinner::create([
            'doorprize_id' => $doorprize->id,
            'nik' => $pemilih->nik,
            'won_at' => now(),
            'status' => 'pending'
        ]);

        $response = $this->post(route('admin.reports.doorprize.winner.status', $winner->id), [
            'status' => 'accepted',
            'status_note' => 'Hadiah diserahkan langsung oleh ketua panitia'
        ], ['Accept' => 'application/json']);

        $response->assertStatus(200);
        $winner->refresh();
        $this->assertEquals('accepted', $winner->status);
        $this->assertNotNull($winner->received_at);
        $this->assertEquals('Hadiah diserahkan langsung oleh ketua panitia', $winner->status_note);
    }

    public function test_doorprize_view_loads_successfully_for_admin(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.reports.doorprize'));
        $response->assertStatus(200);
        $response->assertSee('Master Pilihan Hadiah Doorprize');
        $response->assertSee('Log Pemenang & Status Klaim Doorprize', false);
    }

    public function test_gemini_api_key_test_endpoint(): void
    {
        $this->actingAs($this->admin);

        \Illuminate\Support\Facades\Http::fake([
            '*' => \Illuminate\Support\Facades\Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'PONG: TapVote AI Gemini Connected']
                            ]
                        ]
                    ]
                ]
            ], 200)
        ]);

        $response = $this->post(route('admin.settings.gemini-test'), [
            'api_key' => 'AIzaSyFakeTestKeyForValidation123456789'
        ], ['Accept' => 'application/json']);

        $response->assertStatus(200);
        $this->assertTrue($response->json('success'));
        $this->assertArrayHasKey('latency_ms', $response->json());
        $this->assertStringContainsString('PONG', $response->json('reply'));
    }

    public function test_admin_can_reject_doorprize_winner_with_custom_reason(): void
    {
        $this->actingAs($this->admin);

        $doorprize = \App\Models\Doorprize::create([
            'title' => 'Smart Watch Pro',
            'category' => 'Gadget',
            'quantity' => 1
        ]);

        $pemilih = Pemilih::create([
            'nik' => '881122',
            'rfid' => 'RF881122',
            'nama' => 'Peserta Gugur',
            'dept' => 'Finance',
            'pilih' => 'T'
        ]);

        $winner = \App\Models\DoorprizeWinner::create([
            'doorprize_id' => $doorprize->id,
            'nik' => $pemilih->nik,
            'won_at' => now(),
            'status' => 'pending'
        ]);

        $response = $this->post(route('admin.reports.doorprize.winner.status', $winner->id), [
            'status' => 'rejected',
            'status_note' => 'Peserta tidak hadir saat dipanggil 3 kali'
        ], ['Accept' => 'application/json']);

        $response->assertStatus(200);
        $winner->refresh();
        $this->assertEquals('rejected', $winner->status);
        $this->assertNull($winner->received_at);
        $this->assertEquals('Peserta tidak hadir saat dipanggil 3 kali', $winner->status_note);
    }

    public function test_recap_excel_contains_utf8_bom_and_proper_data(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.dashboard.export.excel'));
        $response->assertStatus(200);
        
        $content = $response->getContent();
        // Check for UTF-8 BOM
        $this->assertStringStartsWith("\xEF\xBB\xBF", $content);
        $this->assertStringContainsString('REKAPITULASI RESMI HASIL PEMILIHAN', $content);
    }

    public function test_admin_can_query_voters_using_query_http_method(): void
    {
        $this->actingAs($this->admin);

        Pemilih::create([
            'nik' => '554433',
            'rfid' => 'RF554433',
            'nama' => 'Query Tester',
            'dept' => 'Engineering',
            'pilih' => 'F'
        ]);

        $response = $this->call('QUERY', route('admin.voters.query'), [
            'search' => 'Query Tester',
            'per_page' => 10
        ], [], [], ['HTTP_ACCEPT' => 'application/json']);

        $response->assertStatus(200);
        $this->assertArrayHasKey('data', $response->json());
        $this->assertEquals('Query Tester', $response->json('data.0.nama'));
    }

    public function test_artisan_tapvote_health_command_executes_successfully(): void
    {
        $this->artisan('tapvote:health')
            ->expectsOutputToContain('TapVote-AI System Health Check')
            ->expectsOutputToContain('System check completed.')
            ->assertExitCode(0);
    }

    public function test_artisan_tapvote_recap_command_executes_successfully(): void
    {
        $this->artisan('tapvote:recap')
            ->expectsOutputToContain('REKAPITULASI HASIL PEMILIHAN TAPVOTE-AI')
            ->expectsOutputToContain('Total DPT')
            ->assertExitCode(0);
    }
}
