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

        // 2. Seed Kandidat Ketua Koperasi (Visi & Misi tipe TEXT)
        $ketuaList = [
            [
                'nik' => 'KT01',
                'nama' => 'Ir. H. Bambang Sutrisno, M.M.',
                'nomor_urut' => 1,
                'foto' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&auto=format&fit=crop&q=80',
                'visi' => "Mewujudkan Koperasi Karyawan yang tangguh, modern, mandiri, dan berdaya saing global berbasis tata kelola teknologi digital pintar untuk kesejahteraan seluruh anggota secara berkelanjutan.",
                'misi' => "1. Mendorong transformasi digital menyeluruh pada sistem simpan pinjam dan unit usaha ritel koperasi.\n2. Meningkatkan dividen SHU tahunan anggota minimal 15% melalui diversifikasi investasi yang prudent.\n3. Memperluas program kesejahteraan langsung seperti bantuan pendidikan dan kesehatan darurat anggota.",
                'deskripsi' => 'Pengalaman 18 tahun dalam manajemen strategis korporasi dan 6 tahun di kepengurusan koperasi tingkat nasional.',
            ],
            [
                'nik' => 'KT02',
                'nama' => 'Dr. Hj. Nur Aisyah, S.E., M.Si.',
                'nomor_urut' => 2,
                'foto' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=400&auto=format&fit=crop&q=80',
                'visi' => "Membangun koperasi yang inklusif, akuntabel, dan berorientasi pada pemberdayaan finansial mikro setiap keluarga anggota koperasi.",
                'misi' => "1. Penerapan transparansi laporan keuangan *real-time open-book* yang dapat diakses 24/7 oleh seluruh anggota.\n2. Optimalisasi bunga simpanan anggota di atas rata-rata bank umum dengan risiko terkelola.\n3. Inkubasi wirausaha mandiri bagi keluarga anggota dengan pendanaan lunak tanpa jaminan berbelit.",
                'deskripsi' => 'Pakar Ekonomi Syariah & Koperasi dengan sertifikasi Risk Governance Professional (CRGP).',
            ],
            [
                'nik' => 'KT03',
                'nama' => 'Hendra Pratama, S.T., MBA',
                'nomor_urut' => 3,
                'foto' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=400&auto=format&fit=crop&q=80',
                'visi' => "Menjadikan Koperasi sebagai motor akselerator ekonomi hijau (Green Economy) yang adaptif terhadap revolusi industri 5.0.",
                'misi' => "1. Mengembangkan superapp koperasi pintar berbasis AI untuk automasi transaksi dan pinjaman kilat terverifikasi.\n2. Kolaborasi strategis B2B dengan supplier utama untuk memastikan harga sembako ritel koperasi termurah di kelasnya.\n3. Mengalokasikan 10% laba bersih untuk program dana abadi beasiswa putra-putri anggota berprestasi.",
                'deskripsi' => 'Head of Technology Innovation dengan rekam jejak kepemimpinan visioner selama 12 tahun.',
            ],
        ];

        foreach ($ketuaList as $k) {
            KandidatKetua::updateOrCreate(['nik' => $k['nik']], $k);
        }

        // 3. Seed Kandidat Pengawas Koperasi (Visi & Misi tipe TEXT)
        $pengawasList = [
            [
                'nik' => 'PW01',
                'nama' => 'Drs. Ahmad Fauzi, Ak., CA',
                'nomor_urut' => 1,
                'foto' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=400&auto=format&fit=crop&q=80',
                'visi' => "Pengawasan independen, objektif, dan berintegritas tanpa kompromi demi melindungi aset seluruh anggota koperasi.",
                'misi' => "1. Melaksanakan audit operasional dan finansial triwulanan secara ketat mengacu PSAK Koperasi.\n2. Menerapkan sistem deteksi dini (Early Warning Fraud Detection) pada setiap transaksi pencairan pinjaman.\n3. Memberikan rekomendasi perbaikan berkala yang konstruktif dan terukur kepada jajaran pengurus.",
                'deskripsi' => 'Akuntan Beregister Negara (CA) dengan pengalaman lebih dari 20 tahun di bidang Audit Forensik & Internal Control.',
            ],
            [
                'nik' => 'PW02',
                'nama' => 'Rina Wulandari, S.H., M.Kn.',
                'nomor_urut' => 2,
                'foto' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=400&auto=format&fit=crop&q=80',
                'visi' => "Menegakkan kepatuhan hukum (*legal compliance*) dan tata kelola etis (Good Cooperative Governance) di setiap lini usaha.",
                'misi' => "1. Memastikan setiap perjanjian kerjasama usaha dan kontrak pihak ketiga sah demi hukum dan tidak merugikan anggota.\n2. Membuka saluran pengaduan anggota (*whistleblowing system*) yang terjamin kerahasiaannya.\n3. Memperkuat advokasi hak-hak hukum anggota dalam persidangan RAT tahunan.",
                'deskripsi' => 'Konsultan Hukum Korporasi & Magister Kenotariatan dengan spesialisasi regulasi perkoperasian Indonesia.',
            ],
            [
                'nik' => 'PW03',
                'nama' => 'Doni Kusuma, M.Kom., CISA',
                'nomor_urut' => 3,
                'foto' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=400&auto=format&fit=crop&q=80',
                'visi' => "Menghadirkan pengawasan berbasis analitik data cerdas dan audit keamanan sistem informasi koperasi yang berstandar internasional.",
                'misi' => "1. Mengaudit keamanan data pribadi anggota dan keandalan sistem perbankan internal secara kontinu.\n2. Mencegah kebocoran finansial melalui automated anomaly detection pada log sistem kasir dan kas keluar.\n3. Memastikan SLA sistem e-voting dan operasional mencapai reliabilitas 99.9%.",
                'deskripsi' => 'Certified Information Systems Auditor (CISA) dan Lead Auditor ISO 27001.',
            ],
        ];

        foreach ($pengawasList as $p) {
            KandidatPengawas::updateOrCreate(['nik' => $p['nik']], $p);
        }

        // 4. Seed Pemilih (RFID Mifare ISO 14443A)
        $pemilihList = [
            [
                'nik' => '102401',
                'rfid' => 'E280681A',
                'nama' => 'Dimas Aditya Nugraha',
                'dept' => 'Information Technology',
                'pilih' => 'F',
            ],
            [
                'nik' => '102402',
                'rfid' => '04A1B2C3',
                'nama' => 'Sarah Maulida Rahman',
                'dept' => 'Keuangan & Akuntansi',
                'pilih' => 'F',
            ],
            [
                'nik' => '102403',
                'rfid' => '5F8E219D',
                'nama' => 'Reza Pratama Kusumo',
                'dept' => 'Operasional & Gudang',
                'pilih' => 'F',
            ],
            [
                'nik' => '102404',
                'rfid' => '9C34FA12',
                'nama' => 'Dewi Anggraini',
                'dept' => 'Human Resources (HRD)',
                'pilih' => 'F',
            ],
            [
                'nik' => '102405',
                'rfid' => '12FE78AB',
                'nama' => 'Fajar Hidayatullah',
                'dept' => 'Pemasaran & Bisnis',
                'pilih' => 'F',
            ],
            [
                'nik' => '102406',
                'rfid' => '88CD4321',
                'nama' => 'Annisa Tri Wahyuni',
                'dept' => 'Keuangan & Akuntansi',
                'pilih' => 'F',
            ],
            [
                'nik' => '102407',
                'rfid' => '6790BBAA',
                'nama' => 'Bagas Pamungkas',
                'dept' => 'Logistik & Distribusi',
                'pilih' => 'F',
            ],
            [
                'nik' => '102408',
                'rfid' => '4412CC89',
                'nama' => 'Melati Sukmawati',
                'dept' => 'Customer Service',
                'pilih' => 'F',
            ],
            [
                'nik' => '102409',
                'rfid' => '7733DDA1',
                'nama' => 'Rian Hidayat',
                'dept' => 'Information Technology',
                'pilih' => 'F',
            ],
            [
                'nik' => '102410',
                'rfid' => '3355EE99',
                'nama' => 'Tiara Kusuma Wardani',
                'dept' => 'Legal & Compliance',
                'pilih' => 'F',
            ],
        ];

        foreach ($pemilihList as $pem) {
            Pemilih::updateOrCreate(['nik' => $pem['nik']], $pem);
        }

        // 5. Activity Log Initial Seeding
        ActivityLog::create([
            'user_type' => 'system',
            'user_identifier' => 'SYSTEM_INIT',
            'action' => 'DATABASE_SEED',
            'module' => 'SYSTEM',
            'description' => 'Inisialisasi data awal master: Admin, 3 Calon Ketua, 3 Calon Pengawas, dan 10 Pemilih terdaftar dengan RFID Mifare.',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Seeder Script',
        ]);
    }
}
