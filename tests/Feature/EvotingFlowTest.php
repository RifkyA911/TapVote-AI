<?php

namespace Tests\Feature;

use App\Models\HasilKetua;
use App\Models\HasilPengawas;
use App\Models\KandidatKetua;
use App\Models\KandidatPengawas;
use App\Models\Pemilih;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EvotingFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_voter_can_tap_card_and_access_ballot(): void
    {
        $pemilih = Pemilih::first();

        // 1. Tap card
        $response = $this->post(route('voter.tap.process'), [
            'rfid' => $pemilih->rfid,
        ]);

        $response->assertRedirect(route('voter.vote'));
        $this->assertEquals($pemilih->nik, session('voter_nik'));

        // 2. View Ballot
        $ballotResponse = $this->get(route('voter.vote'));
        $ballotResponse->assertStatus(200);
        $ballotResponse->assertSee($pemilih->nama);
    }

    public function test_voter_can_cast_dual_vote_and_gets_finalized(): void
    {
        $pemilih = Pemilih::where('pilih', 'F')->first();
        $ketua = KandidatKetua::first();
        $pengawas = KandidatPengawas::first();

        // Simulate active voter session
        $response = $this->withSession(['voter_nik' => $pemilih->nik])
            ->post(route('voter.vote.store'), [
                'ketua_nik' => $ketua->nik,
                'pengawas_nik' => $pengawas->nik,
            ]);

        $response->assertRedirect(route('voter.finalization'));

        // Check DB updates
        $pemilih->refresh();
        $this->assertEquals('T', $pemilih->pilih);
        $this->assertNotNull($pemilih->voted_at);

        $this->assertDatabaseHas('hasil_ketua', [
            'pemilih_nik' => $pemilih->nik,
            'ketua_nik' => $ketua->nik,
        ]);

        $this->assertDatabaseHas('hasil_pengawas', [
            'pemilih_nik' => $pemilih->nik,
            'pengawas_nik' => $pengawas->nik,
        ]);
    }

    public function test_voter_cannot_vote_twice(): void
    {
        $votedPemilih = Pemilih::where('pilih', 'T')->first();

        if (!$votedPemilih) {
            $votedPemilih = Pemilih::first();
            $votedPemilih->update(['pilih' => 'T', 'voted_at' => now()]);
        }

        // Tap attempt with already voted card
        $response = $this->post(route('voter.tap.process'), [
            'rfid' => $votedPemilih->rfid,
        ]);

        $response->assertRedirect(route('voter.tap'));
        $response->assertSessionHas('error');
    }

    public function test_admin_dashboard_metrics_api(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get(route('admin.api.live-results'));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'timestamp',
            'metrics' => [
                'total_voters',
                'total_voted',
                'remaining_voters',
                'turnout_percentage',
            ],
            'ketua_results',
            'pengawas_results',
            'recent_votes',
        ]);
    }
}
