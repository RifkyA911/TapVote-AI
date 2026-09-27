<?php

namespace Tests\Feature;

use App\Models\AppSetting;
use App\Models\Doorprize;
use App\Models\DoorprizeWinner;
use App\Models\HasilKetua;
use App\Models\HasilPengawas;
use App\Models\KandidatKetua;
use App\Models\KandidatPengawas;
use App\Models\Pemilih;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoreFlowSecurityTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private KandidatKetua $ketua;
    private KandidatPengawas $pengawas;

    protected function setUp(): void
    {
        parent::setUp();

        AppSetting::set('voting_status', 'STARTED');
        AppSetting::set('voting_deadline', Carbon::now()->addHours(5)->toDateTimeString());

        $this->admin = User::create([
            'name' => 'Security Admin',
            'email' => 'admin_security@tapvote.ai',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->ketua = KandidatKetua::create([
            'nik' => 'KETUA001',
            'nomor_urut' => 1,
            'nama' => 'Calon Ketua Utama',
            'visi' => 'Visi Ketua',
            'misi' => 'Misi Ketua',
        ]);

        $this->pengawas = KandidatPengawas::create([
            'nik' => 'PENGAWAS001',
            'nomor_urut' => 1,
            'nama' => 'Calon Pengawas Utama',
            'visi' => 'Visi Pengawas',
            'misi' => 'Misi Pengawas',
        ]);
    }

    /**
     * Test 1: Mencegah pembajakan akun pemilih melalui partial LIKE substring
     */
    public function test_voter_cannot_tap_with_partial_substring_hijack(): void
    {
        Pemilih::create([
            'nik' => '100200300',
            'rfid' => 'A1B2C3D4',
            'nama' => 'Target Voter',
            'dept' => 'IT',
            'pilih' => 'F',
            'can_raffle' => true,
        ]);

        // Partial substring "200" atau "B2" tidak boleh mencocokkan Target Voter
        $response = $this->post(route('voter.tap.process'), [
            'rfid' => '200',
        ], ['Accept' => 'application/json']);

        $response->assertStatus(404);
        $this->assertNull(session('voter_nik'));

        $responseHex = $this->post(route('voter.tap.process'), [
            'rfid' => 'B2',
        ], ['Accept' => 'application/json']);

        $responseHex->assertStatus(404);
        $this->assertNull(session('voter_nik'));
    }

    /**
     * Test 2: Pemilih yang sudah memilih ditolak masuk showVotePage walaupun session tersisa
     */
    public function test_voter_cannot_access_vote_page_if_already_voted(): void
    {
        $voter = Pemilih::create([
            'nik' => 'VOTER001',
            'rfid' => 'RFID001',
            'nama' => 'Voter Sudah Memilih',
            'dept' => 'Finance',
            'pilih' => 'T',
            'voted_at' => now()->subMinutes(10),
            'can_raffle' => true,
        ]);

        $response = $this->withSession(['voter_nik' => $voter->nik])
            ->get(route('voter.vote'));

        $response->assertRedirect(route('voter.tap'));
        $response->assertSessionHas('error');
        $this->assertNull(session('voter_nik'));
    }

    /**
     * Test 3: Bilik suara menolak akses jika batas waktu pemilihan (deadline) terlewati
     */
    public function test_voter_cannot_access_vote_page_if_deadline_expired(): void
    {
        AppSetting::set('voting_deadline', Carbon::now()->subMinute()->toDateTimeString());

        $voter = Pemilih::create([
            'nik' => 'VOTER002',
            'rfid' => 'RFID002',
            'nama' => 'Voter Telat',
            'dept' => 'HR',
            'pilih' => 'F',
            'can_raffle' => true,
        ]);

        $response = $this->withSession(['voter_nik' => $voter->nik])
            ->get(route('voter.vote'));

        $response->assertRedirect(route('voter.tap'));
        $response->assertSessionHas('error');
    }

    /**
     * Test 4: Submit vote mengembalikan HTTP 403 JSON saat sistem di-jeda / ditutup
     */
    public function test_voter_cannot_submit_vote_when_system_paused_or_stopped_returns_json_403(): void
    {
        $voter = Pemilih::create([
            'nik' => 'VOTER003',
            'rfid' => 'RFID003',
            'nama' => 'Voter Siap',
            'dept' => 'Logistik',
            'pilih' => 'F',
            'can_raffle' => true,
        ]);

        // Jeda sistem
        AppSetting::set('voting_status', 'PAUSED');

        $response = $this->withSession(['voter_nik' => $voter->nik])
            ->postJson(route('voter.vote.store'), [
                'ketua_nik' => $this->ketua->nik,
                'pengawas_nik' => $this->pengawas->nik,
            ]);

        $response->assertStatus(403);
        $response->assertJson([
            'success' => false,
            'redirect' => route('voter.tap'),
        ]);

        // Status di database harus tetap belum memilih
        $voter->refresh();
        $this->assertEquals('F', $voter->pilih);
        $this->assertEquals(0, HasilKetua::count());
    }

    /**
     * Test 5: Submit vote mengembalikan HTTP 403 JSON jika melewati batas waktu
     */
    public function test_voter_cannot_submit_vote_after_deadline_expired_returns_json_403(): void
    {
        AppSetting::set('voting_deadline', Carbon::now()->subSecond()->toDateTimeString());

        $voter = Pemilih::create([
            'nik' => 'VOTER004',
            'rfid' => 'RFID004',
            'nama' => 'Voter Di Bilik Habis Waktu',
            'dept' => 'GA',
            'pilih' => 'F',
            'can_raffle' => true,
        ]);

        $response = $this->withSession(['voter_nik' => $voter->nik])
            ->postJson(route('voter.vote.store'), [
                'ketua_nik' => $this->ketua->nik,
                'pengawas_nik' => $this->pengawas->nik,
            ]);

        $response->assertStatus(403);
        $response->assertJson([
            'success' => false,
            'redirect' => route('voter.tap'),
        ]);
    }

    /**
     * Test 6: Anggota nonaktif (can_raffle = false) ditolak saat submit vote
     */
    public function test_ineligible_voter_cannot_submit_vote_returns_json_403(): void
    {
        $voter = Pemilih::create([
            'nik' => 'VOTER005',
            'rfid' => 'RFID005',
            'nama' => 'Voter Nonaktif',
            'dept' => 'Security',
            'pilih' => 'F',
            'can_raffle' => false,
        ]);

        $response = $this->withSession(['voter_nik' => $voter->nik])
            ->postJson(route('voter.vote.store'), [
                'ketua_nik' => $this->ketua->nik,
                'pengawas_nik' => $this->pengawas->nik,
            ]);

        $response->assertStatus(403);
        $this->assertNull(session('voter_nik'));
    }

    /**
     * Test 7: Mencegah pemilihan ganda (double-vote) jika voter sudah memilih
     */
    public function test_already_voted_cannot_resubmit_vote_returns_json_403(): void
    {
        $voter = Pemilih::create([
            'nik' => 'VOTER006',
            'rfid' => 'RFID006',
            'nama' => 'Voter Double Attempter',
            'dept' => 'Legal',
            'pilih' => 'T',
            'voted_at' => now(),
            'can_raffle' => true,
        ]);

        $response = $this->withSession(['voter_nik' => $voter->nik])
            ->postJson(route('voter.vote.store'), [
                'ketua_nik' => $this->ketua->nik,
                'pengawas_nik' => $this->pengawas->nik,
            ]);

        $response->assertStatus(403);
        $this->assertNull(session('voter_nik'));
    }

    /**
     * Test 8: Admin dilarang menghapus pemilih yang telah memberikan suara sah demi integritas pemilu
     */
    public function test_admin_cannot_delete_voter_who_already_voted(): void
    {
        $this->actingAs($this->admin);

        $voter = Pemilih::create([
            'nik' => 'VOTER007',
            'rfid' => 'RFID007',
            'nama' => 'Voter Suara Sah',
            'dept' => 'Pemasaran',
            'pilih' => 'T',
            'voted_at' => now(),
            'can_raffle' => true,
        ]);

        HasilKetua::create([
            'pemilih_nik' => $voter->nik,
            'ketua_nik' => $this->ketua->nik,
        ]);

        HasilPengawas::create([
            'pemilih_nik' => $voter->nik,
            'pengawas_nik' => $this->pengawas->nik,
        ]);

        // Coba hapus via endpoint admin
        $response = $this->deleteJson(route('admin.voters.destroy', $voter->nik));

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);

        // Pemilih dan hasil suara harus tetap ada
        $this->assertDatabaseHas('pemilih', ['nik' => 'VOTER007']);
        $this->assertDatabaseHas('hasil_ketua', ['pemilih_nik' => 'VOTER007']);
        $this->assertDatabaseHas('hasil_pengawas', ['pemilih_nik' => 'VOTER007']);
    }

    /**
     * Test 9: Pengundian doorprize atomik dan kompatibilitas accessor pemenang
     */
    public function test_doorprize_winner_claim_status_accessor_and_atomic_draw(): void
    {
        $this->actingAs($this->admin);

        $voter = Pemilih::create([
            'nik' => 'VOTER008',
            'rfid' => 'RFID008',
            'nama' => 'Pemenang Doorprize',
            'dept' => 'Produksi',
            'pilih' => 'T',
            'voted_at' => now(),
            'can_raffle' => true,
        ]);

        $doorprize = Doorprize::create([
            'title' => 'Sepeda Listrik',
            'category' => 'Utama',
            'quantity' => 1,
            'icon' => 'trophy',
        ]);

        $response = $this->postJson(route('admin.reports.doorprize.draw'), [
            'doorprize_id' => $doorprize->id,
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'winner' => [
                'nik' => 'VOTER008',
            ],
            'doorprize' => [
                'remaining_slots' => 0,
            ],
        ]);

        $winnerRecord = DoorprizeWinner::where('doorprize_id', $doorprize->id)->first();
        $this->assertNotNull($winnerRecord);
        $this->assertEquals('pending', $winnerRecord->claim_status);
        $this->assertEquals('VOTER008', $winnerRecord->pemilih_nik);

        // Jika kuota habis, penarikan berikutnya ditolak
        $responseSecond = $this->postJson(route('admin.reports.doorprize.draw'), [
            'doorprize_id' => $doorprize->id,
        ]);

        $responseSecond->assertStatus(422);
    }

    /**
     * Test 10: Pemilihan sah mencatat hasil ketua dan pengawas secara atomik
     */
    public function test_atomic_vote_submission_persists_ketua_and_pengawas_and_marks_voted(): void
    {
        $voter = Pemilih::create([
            'nik' => 'VOTER009',
            'rfid' => 'RFID009',
            'nama' => 'Pemilih Berhasil',
            'dept' => 'Akuntansi',
            'pilih' => 'F',
            'can_raffle' => true,
        ]);

        $response = $this->withSession(['voter_nik' => $voter->nik])
            ->postJson(route('voter.vote.store'), [
                'ketua_nik' => $this->ketua->nik,
                'pengawas_nik' => $this->pengawas->nik,
            ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
        ]);

        $voter->refresh();
        $this->assertEquals('T', $voter->pilih);
        $this->assertNotNull($voter->voted_at);

        $this->assertDatabaseHas('hasil_ketua', [
            'pemilih_nik' => 'VOTER009',
            'ketua_nik' => $this->ketua->nik,
        ]);

        $this->assertDatabaseHas('hasil_pengawas', [
            'pemilih_nik' => 'VOTER009',
            'pengawas_nik' => $this->pengawas->nik,
        ]);
    }
}
