<?php

namespace App\Console\Commands;

use App\Models\HasilKetua;
use App\Models\HasilPengawas;
use App\Models\KandidatKetua;
use App\Models\KandidatPengawas;
use App\Models\Pemilih;
use Illuminate\Console\Command;

class McpQueryCommand extends Command
{
    protected $signature = 'mcp:query {action} {--arg=}';
    protected $description = 'Provide Model Context Protocol (MCP) data for AI agents';

    public function handle()
    {
        $action = $this->argument('action');
        $arg = $this->option('arg');

        switch ($action) {
            case 'get_election_metrics':
                return $this->getElectionMetrics();

            case 'get_candidate_standings':
                return $this->getCandidateStandings($arg);

            case 'get_candidate_profile':
                return $this->getCandidateProfile($arg);

            case 'get_ai_election_conclusion':
                return $this->getAiElectionConclusion();

            case 'verify_voter_card':
                return $this->verifyVoterCard($arg);

            default:
                $this->line(json_encode(['error' => "Unknown action: {$action}"]));
                return 1;
        }
    }

    private function getElectionMetrics()
    {
        $totalVoters = Pemilih::count();
        $totalVoted = Pemilih::where('pilih', 'T')->count();
        $turnoutPct = $totalVoters > 0 ? round(($totalVoted / $totalVoters) * 100, 1) : 0;
        $totalKetua = HasilKetua::count();
        $totalPengawas = HasilPengawas::count();

        $leaderKetua = KandidatKetua::withCount('perolehanSuara')->orderBy('perolehan_suara_count', 'desc')->first();
        $leaderPengawas = KandidatPengawas::withCount('perolehanSuara')->orderBy('perolehan_suara_count', 'desc')->first();

        $data = [
            'election_name' => 'Koperasi Pemilu E-Voting (TapVote AI)',
            'status' => 'ACTIVE & VERIFIED',
            'total_registered_voters' => $totalVoters,
            'total_ballots_cast' => $totalVoted,
            'turnout_percentage' => $turnoutPct . '%',
            'quorum_reached' => $turnoutPct >= 50.0,
            'total_votes_chairman' => $totalKetua,
            'total_votes_supervisor' => $totalPengawas,
            'leader_chairman' => $leaderKetua ? "No. {$leaderKetua->nomor_urut} - {$leaderKetua->nama} ({$leaderKetua->perolehan_suara_count} votes)" : 'None',
            'leader_supervisor' => $leaderPengawas ? "No. {$leaderPengawas->nomor_urut} - {$leaderPengawas->nama} ({$leaderPengawas->perolehan_suara_count} votes)" : 'None',
            'timestamp' => now()->toIso8601String(),
        ];

        $this->line(json_encode($data));
        return 0;
    }

    private function getCandidateStandings($category = 'all')
    {
        $totalKetua = HasilKetua::count();
        $totalPengawas = HasilPengawas::count();

        $result = [];

        if (!$category || in_array($category, ['all', 'ketua', 'chairman'])) {
            $ketua = KandidatKetua::withCount('perolehanSuara')->orderBy('nomor_urut', 'asc')->get()->map(function ($k) use ($totalKetua) {
                $pct = $totalKetua > 0 ? round(($k->perolehan_suara_count / $totalKetua) * 100, 2) : 0;
                return [
                    'ballot_number' => $k->nomor_urut,
                    'name' => $k->nama,
                    'nik' => $k->nik,
                    'votes' => $k->perolehan_suara_count,
                    'percentage' => $pct . '%',
                    'category' => 'Chairman',
                ];
            });
            $result['chairman_candidates'] = $ketua;
        }

        if (!$category || in_array($category, ['all', 'pengawas', 'supervisor'])) {
            $pengawas = KandidatPengawas::withCount('perolehanSuara')->orderBy('nomor_urut', 'asc')->get()->map(function ($p) use ($totalPengawas) {
                $pct = $totalPengawas > 0 ? round(($p->perolehan_suara_count / $totalPengawas) * 100, 2) : 0;
                return [
                    'ballot_number' => $p->nomor_urut,
                    'name' => $p->nama,
                    'nik' => $p->nik,
                    'votes' => $p->perolehan_suara_count,
                    'percentage' => $pct . '%',
                    'category' => 'Supervisory Board',
                ];
            });
            $result['supervisor_candidates'] = $pengawas;
        }

        $this->line(json_encode($result));
        return 0;
    }

    private function getCandidateProfile($identifier)
    {
        if (!$identifier) {
            $this->line(json_encode(['error' => 'Please provide candidate identifier (ballot number, name, or NIK).']));
            return 1;
        }

        $k = KandidatKetua::where('nik', $identifier)
            ->orWhere('nomor_urut', $identifier)
            ->orWhere('nama', 'like', "%{$identifier}%")
            ->first();

        if ($k) {
            $total = HasilKetua::count();
            $votes = $k->perolehanSuara()->count();
            $this->line(json_encode([
                'category' => 'Chairman',
                'ballot_number' => $k->nomor_urut,
                'name' => $k->nama,
                'nik' => $k->nik,
                'vision' => $k->visi,
                'mission' => $k->misi,
                'description' => $k->deskripsi,
                'current_votes' => $votes,
                'vote_share' => ($total > 0 ? round(($votes / $total) * 100, 2) : 0) . '%',
            ]));
            return 0;
        }

        $p = KandidatPengawas::where('nik', $identifier)
            ->orWhere('nomor_urut', $identifier)
            ->orWhere('nama', 'like', "%{$identifier}%")
            ->first();

        if ($p) {
            $total = HasilPengawas::count();
            $votes = $p->perolehanSuara()->count();
            $this->line(json_encode([
                'category' => 'Supervisory Board',
                'ballot_number' => $p->nomor_urut,
                'name' => $p->nama,
                'nik' => $p->nik,
                'vision' => $p->visi,
                'mission' => $p->misi,
                'description' => $p->deskripsi,
                'current_votes' => $votes,
                'vote_share' => ($total > 0 ? round(($votes / $total) * 100, 2) : 0) . '%',
            ]));
            return 0;
        }

        $this->line(json_encode(['error' => "Candidate '{$identifier}' not found."]));
        return 1;
    }

    private function getAiElectionConclusion()
    {
        $dashboardController = new \App\Http\Controllers\Admin\DashboardController();
        $payloadMethod = new \ReflectionMethod($dashboardController, 'gatherVotingMetrics');
        $payloadMethod->setAccessible(true);
        $payload = $payloadMethod->invoke($dashboardController);

        $conclusionMethod = new \ReflectionMethod($dashboardController, 'generateAiConclusion');
        $conclusionMethod->setAccessible(true);
        $conclusion = $conclusionMethod->invoke($dashboardController, $payload);

        $this->line(json_encode($conclusion));
        return 0;
    }

    private function verifyVoterCard($identifier)
    {
        if (!$identifier) {
            $this->line(json_encode(['error' => 'Please provide card RFID UID or member NIK.']));
            return 1;
        }

        $pemilih = Pemilih::where('rfid', $identifier)->orWhere('nik', $identifier)->first();

        if (!$pemilih) {
            $this->line(json_encode([
                'registered' => false,
                'message' => "Card UID / NIK '{$identifier}' is not registered in DPT.",
            ]));
            return 0;
        }

        $this->line(json_encode([
            'registered' => true,
            'nik' => $pemilih->nik,
            'name' => $pemilih->nama,
            'department' => $pemilih->dept,
            'has_voted' => $pemilih->pilih === 'T',
            'voted_at' => $pemilih->voted_at ? $pemilih->voted_at->toIso8601String() : null,
            'can_vote' => $pemilih->pilih !== 'T',
        ]));
        return 0;
    }
}
