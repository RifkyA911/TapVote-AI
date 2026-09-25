<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pemilih;
use App\Models\KandidatKetua;
use App\Models\KandidatPengawas;
use App\Models\ActivityLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@tapvote.ai'],
            [
                'name' => 'Administrator TapVote',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // 2. Seed Kandidat Ketua Koperasi (Semua Laki-Laki Mengenakan Batik)
        $ketuaList = [
            [
                'nik' => 'KT01',
                'nama' => 'Ir. H. Bambang Sutrisno, M.M.',
                'nomor_urut' => 1,
                'foto' => '/images/candidates/kandidat_ketua_1.jpg',
                'visi' => "Mewujudkan Koperasi Karyawan yang tangguh, modern, mandiri, dan berdaya saing global berbasis tata kelola teknologi digital pintar untuk kesejahteraan seluruh anggota secara berkelanjutan.",
                'misi' => "1. Mendorong transformasi digital menyeluruh pada sistem simpan pinjam dan unit usaha ritel koperasi.\n2. Meningkatkan dividen SHU tahunan anggota minimal 15% melalui diversifikasi investasi yang prudent.\n3. Memperluas program kesejahteraan langsung seperti bantuan pendidikan dan kesehatan darurat anggota.",
                'deskripsi' => 'Pengalaman 18 tahun dalam manajemen strategis korporasi dan 6 tahun di kepengurusan koperasi tingkat nasional.',
            ],
            [
                'nik' => 'KT02',
                'nama' => 'Drs. H. Hendra Pratama, M.Si.',
                'nomor_urut' => 2,
                'foto' => '/images/candidates/kandidat_ketua_2.jpg',
                'visi' => "Membangun koperasi yang inklusif, akuntabel, dan berorientasi pada pemberdayaan finansial mikro setiap keluarga anggota koperasi.",
                'misi' => "1. Penerapan transparansi laporan keuangan real-time open-book yang dapat diakses 24/7 oleh seluruh anggota.\n2. Optimalisasi bunga simpanan anggota di atas rata-rata bank umum dengan risiko terkelola.\n3. Inkubasi wirausaha mandiri bagi keluarga anggota dengan pendanaan lunak tanpa jaminan berbelit.",
                'deskripsi' => 'Pakar Manajemen Risiko & Koperasi dengan sertifikasi Risk Governance Professional (CRGP).',
            ],
            [
                'nik' => 'KT03',
                'nama' => 'Rahmat Hidayat, S.T., MBA',
                'nomor_urut' => 3,
                'foto' => '/images/candidates/kandidat_ketua_3.jpg',
                'visi' => "Menjadikan Koperasi sebagai motor akselerator ekonomi hijau (Green Economy) yang adaptif terhadap revolusi industri 5.0.",
                'misi' => "1. Mengembangkan superapp koperasi pintar berbasis AI untuk automasi transaksi dan pinjaman kilat terverifikasi.\n2. Kolaborasi strategis B2B dengan supplier utama untuk memastikan harga sembako ritel koperasi termurah di kelasnya.\n3. Mengalokasikan 10% laba bersih untuk program dana abadi beasiswa putra-putri anggota berprestasi.",
                'deskripsi' => 'Head of Technology Innovation dengan rekam jejak kepemimpinan visioner selama 12 tahun.',
            ],
        ];

        foreach ($ketuaList as $k) {
            KandidatKetua::updateOrCreate(['nik' => $k['nik']], $k);
        }

        // 3. Seed Kandidat Pengawas Koperasi (Semua Laki-Laki Mengenakan Batik)
        $pengawasList = [
            [
                'nik' => 'PW01',
                'nama' => 'Drs. Ahmad Fauzi, Ak., CA',
                'nomor_urut' => 1,
                'foto' => '/images/candidates/kandidat_pengawas_1.jpg',
                'visi' => "Pengawasan independen, objektif, dan berintegritas tanpa kompromi demi melindungi aset seluruh anggota koperasi.",
                'misi' => "1. Melaksanakan audit operasional dan finansial triwulanan secara ketat mengacu PSAK Koperasi.\n2. Menerapkan sistem deteksi dini (Early Warning Fraud Detection) pada setiap transaksi pencairan pinjaman.\n3. Memberikan rekomendasi perbaikan berkala yang konstruktif dan terukur kepada jajaran pengurus.",
                'deskripsi' => 'Akuntan Beregister Negara (CA) dengan pengalaman lebih dari 20 tahun di bidang Audit Forensik & Internal Control.',
            ],
            [
                'nik' => 'PW02',
                'nama' => 'Budi Santoso, S.H., M.Kn.',
                'nomor_urut' => 2,
                'foto' => '/images/candidates/kandidat_pengawas_2.jpg',
                'visi' => "Menegakkan kepatuhan hukum (legal compliance) dan tata kelola etis (Good Cooperative Governance) di setiap lini usaha.",
                'misi' => "1. Memastikan setiap perjanjian kerjasama usaha dan kontrak pihak ketiga sah demi hukum dan tidak merugikan anggota.\n2. Membuka saluran pengaduan anggota (whistleblowing system) yang terjamin kerahasiaannya.\n3. Memperkuat advokasi hak-hak hukum anggota dalam persidangan RAT tahunan.",
                'deskripsi' => 'Konsultan Hukum Korporasi & Magister Kenotariatan dengan spesialisasi regulasi perkoperasian Indonesia.',
            ],
            [
                'nik' => 'PW03',
                'nama' => 'Doni Kusuma, M.Kom., CISA',
                'nomor_urut' => 3,
                'foto' => '/images/candidates/kandidat_pengawas_3.jpg',
                'visi' => "Menghadirkan pengawasan berbasis analitik data cerdas dan audit keamanan sistem informasi koperasi yang berstandar internasional.",
                'misi' => "1. Mengaudit keamanan data pribadi anggota dan keandalan sistem perbankan internal secara kontinu.\n2. Mencegah kebocoran finansial melalui automated anomaly detection pada log sistem kasir dan kas keluar.\n3. Memastikan SLA sistem e-voting dan operasional mencapai reliabilitas 99.9%.",
                'deskripsi' => 'Certified Information Systems Auditor (CISA) dan Lead Auditor ISO 27001.',
            ],
        ];

        foreach ($pengawasList as $p) {
            KandidatPengawas::updateOrCreate(['nik' => $p['nik']], $p);
        }

        // 4. Seed 100 Pemilih (Format NIK 0xxxxx, Departemen Resmi, RFID Mifare ISO 14443A)
        $departments = [
            'Areso', 'Hollow', 'Kalung', 'EG', 'EGV', 
            'QMS', 'HC', 'Bahan', 'Maintenance', 'ICT', 
            'Marketing', 'Workshop', 'Cor'
        ];

        $firstNames = [
            'Dimas', 'Reza', 'Bagas', 'Rian', 'Fajar', 'Aditya', 'Bayu', 'Gilang', 'Arif', 'Eko',
            'Teguh', 'Wahyu', 'Rizky', 'Doni', 'Agus', 'Surya', 'Ilham', 'Bambang', 'Hadi', 'Joko',
            'Sigit', 'Dwi', 'Tri', 'Catur', 'Wawan', 'Nanang', 'Dedi', 'Yanto', 'Hendra', 'Rudi',
            'Asep', 'Dadang', 'Cecep', 'Ujang', 'Budi', 'Danang', 'Imam', 'Lukman', 'Galih', 'Yudi'
        ];

        $lastNames = [
            'Nugraha', 'Pratama', 'Kusumo', 'Hidayatullah', 'Pamungkas', 'Santoso', 'Wijaya', 'Kusuma',
            'Saputra', 'Setiawan', 'Gunawan', 'Wibowo', 'Prasetyo', 'Hartono', 'Purnomo', 'Syahputra',
            'Firmansyah', 'Sudrajat', 'Suryanto', 'Utomo', 'Subagyo', 'Purwanto', 'Susanto', 'Hermawan'
        ];

        // Seed 100 voters deterministically
        mt_srand(12345);

        for ($i = 1; $i <= 100; $i++) {
            if ($i === 1) {
                $nik = '018513';
                $nama = 'Rifky Akhmad Fernanda';
                $dept = 'ICT';
                $rfidHex = '0549936289';
            } else {
                $nik = sprintf('0%05d', $i); // Format NIK: 000002, ..., 000100
                
                // Generate standard 4-byte / 8-char Hex UID for Mifare Classic
                $byte0 = sprintf('%02X', ($i * 17 + 23) % 256);
                $byte1 = sprintf('%02X', ($i * 31 + 41) % 256);
                $byte2 = sprintf('%02X', ($i * 47 + 59) % 256);
                $byte3 = sprintf('%02X', ($i * 61 + 73) % 256);
                $rfidHex = $byte0 . $byte1 . $byte2 . $byte3;

                $nama = $firstNames[($i - 1) % count($firstNames)] . ' ' . $lastNames[($i * 3) % count($lastNames)];
                $dept = $departments[($i - 1) % count($departments)];
            }

            Pemilih::updateOrCreate(
                ['nik' => $nik],
                [
                    'rfid' => $rfidHex,
                    'nama' => $nama,
                    'dept' => $dept,
                    'pilih' => 'F',
                    'voted_at' => null,
                ]
            );
        }

        // 5. Activity Log Initial Seeding
        ActivityLog::create([
            'user_type' => 'system',
            'user_identifier' => 'SYSTEM_INIT',
            'action' => 'DATABASE_SEED',
            'module' => 'SYSTEM',
            'description' => 'Inisialisasi data awal master: Admin, 3 Calon Ketua (Laki-laki Batik), 3 Calon Pengawas (Laki-laki Batik), dan 100 Pemilih DPT format NIK 0xxxxx terdaftar di 13 Departemen resmi.',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Seeder Script',
        ]);
    }
}
