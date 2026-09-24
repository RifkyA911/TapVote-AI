# Product Requirements Document (PRD)
## TapVote AI - Enterprise SaaS E-Voting System (Pemilihan Ketua & Pengawas Koperasi)

---

### 1. Executive Summary
**TapVote AI** adalah sistem e-voting modern berstandar enterprise SaaS yang dirancang khusus untuk Pemilihan Ketua dan Pengawas Koperasi secara serentak, transparan, cepat, dan teruji secara audit trail. Sistem ini memanfaatkan teknologi **RFID Mifare ISO 14443A** untuk autentikasi pemilih nirsentuh (touchless/tap card), **Server-Sent Events (SSE)** untuk monitoring perolehan suara realtime tanpa jeda, serta **Fluid Animated UI** berbasis Tailwind CSS untuk menyuguhkan pengalaman pengguna kelas dunia.

---

### 2. Strategic Objectives & Core Value Proposition
- **High Security & Single-Vote Integrity**: Menjamin prinsip *one-voter-one-vote* melalui validasi token dan state database `pilih (T/F)` atomik.
- **Dual Ballot in Single Session**: Memungkinkan pemilih memilih 1 Kandidat Ketua dan 1 Kandidat Pengawas secara berbarengan dalam satu flow intuitif `/vote`.
- **Zero-Latency Realtime Broadcast**: Menggunakan SSE (Server-Sent Events) untuk streaming live persentase suara ke dashboard admin dan screen proyektor monitor umum.
- **Trace Back & Comprehensive Audit**: Menyediakan rekonsiliasi suara (trace back pemilih ke kandidat yang dipilih untuk verifikasi saksi/audit panitia), serta tabel `activity_logs` untuk melacak seluruh transaksi CRUD database.
- **Interactive Doorprize Engine**: Otomatisasi penarikan undian berhadiah (doorprize) khusus untuk pemilih yang telah menggunakan hak suaranya (`pilih = 'T'`).

---

### 3. User Personas & RBAC (Role-Based Access Control)

| Role | Identifikasi / Autentikasi | Akses & Wewenang |
| :--- | :--- | :--- |
| **Admin / Panitia** | Email & Password (Bcrypt session auth) | - Full Privileges & CRUD Management<br>- Dashboard Live Analytics & SSE Realtime Stream<br>- CRUD Kandidat Ketua (Foto, Visi, Misi, NIK, Deskripsi)<br>- CRUD Kandidat Pengawas (Foto, Visi, Misi, NIK, Deskripsi)<br>- CRUD Pemilih & Import Bulk Excel/CSV (NIK, Nama, Dept, RFID)<br>- Laporan Pemenang Ketua & Pengawas + Rincian Suara<br>- Trace Back Laporan Pilihan Anggota<br>- Mesin Undian Doorprize (Spinning Lucky Draw)<br>- Audit Log Viewer (`logs`) |
| **Voter / Pemilih** | RFID Mifare ISO 14443A Tap (UID Kartu) | - Mengakses `/voter` untuk Tap ID Card<br>- Section Simulasi Demo RFID (Interactive Card Picker)<br>- Mengakses `/vote` untuk memilih 1 Ketua & 1 Pengawas<br>- Mengakses `/finalization` (Animasi Sukses & Countdown 5 detik logout) |

---

### 4. Detailed Feature Specifications

#### 4.1. Voter Flow
1. **Halaman `/voter` (RFID Authentication)**:
   - Input auto-focus mendengarkan masukan USB RFID Reader (Mifare ISO 14443A keyboard emulation).
   - Indikator status visual (Menunggu Kartu, Memverifikasi, Kartu Ditolak / Diterima).
   - **Interactive DEMO Mode**: Box simulasi dengan sample kartu Mifare (NIK & Departemen berbeda) yang dapat di-klik langsung untuk testing tanpa alat fisik.
   - Validasi status `pilih`:
     - Jika `pilih == 'F'` (belum memilih) $\rightarrow$ Login berhasil, redirect ke `/vote`.
     - Jika `pilih == 'T'` (sudah memilih) $\rightarrow$ Tolak akses dengan peringatan: "Hak suara untuk NIK [xxx] telah digunakan."
2. **Halaman `/vote` (Dual Candidate Selection)**:
   - Tampilan split/tab modern:
     - **Kategori 1**: Calon Ketua Koperasi (Foto profil, Nama, NIK, Visi & Misi modal).
     - **Kategori 2**: Calon Pengawas Koperasi (Foto profil, Nama, NIK, Visi & Misi modal).
   - Hanya 1 kandidat yang dapat dipilih untuk masing-masing kategori.
   - Floating Action Bar / Review Drawer yang menampilkan kandidat terpilih sebelum final submit.
   - Konfirmasi ganda (Confirm Modal) dengan proteksi idempotency token untuk mencegah double click/race condition.
3. **Halaman `/finalization` (Celebration & Auto-Logout)**:
   - Menampilkan animasi fluid (Confetti canvas, fluid wave / pulse effect, verified badge).
   - Pesan terima kasih personalisasi atas partisipasi pemilih.
   - Timer otomatis countdown **5 detik** dengan visual progress bar circular / linear yang secara otomatis me-logout session dan mengarahkan kembali ke `/voter` untuk antrean pemilih berikutnya.

#### 4.2. Admin Flow & Dashboard
1. **Live Realtime Monitoring (SSE Engine)**:
   - Endpoint `/admin/stream/results` mentransmisikan data event-driven:
     - Total Pemilih Terdaftar, Total Suara Masuk, Persentase Partisipasi (*Turnout Rate*).
     - Grafik Bar & Donut Chart perolehan suara Kandidat Ketua.
     - Grafik Bar & Donut Chart perolehan suara Kandidat Pengawas.
     - Live Feed pemilih terakhir yang baru saja melakukan tap & vote.
2. **Kandidat Management (Ketua & Pengawas)**:
   - Upload foto resmi kandidat (storage symlink support).
   - Input Visi dan Misi dengan format teks terstruktur (`TEXT` type).
   - Preview kartu kandidat sesuai tampilan yang akan dilihat oleh pemilih.
3. **Voter Management & Excel Import**:
   - Tambah, edit, dan hapus data pemilih manual.
   - Import file Excel / CSV (kolom: `nik`, `nama`, `dept`, `rfid`).
   - Fitur Reset Suara individual atau massal (untuk keperluan gladi resik / simulasi).
4. **Laporan & Audit Eksekutif**:
   - **Pemenang Ketua Koperasi**: Menampilkan kandidat suara terbanyak, total suara, persentase, dan breakdown per departemen.
   - **Pemenang Pengawas Koperasi**: Menampilkan kandidat suara terbanyak, total suara, persentase, dan breakdown per departemen.
   - **Trace Back Report**: Matriks akuntabilitas untuk memeriksa pilihan masing-masing anggota (NIK, Nama, Dept, Pilihan Ketua, Pilihan Pengawas, Waktu Vote).
   - **Doorprize Undian Pemilih**: Otomatis memfilter daftar anggota berstatus `pilih == 'T'`. Dilengkapi tombol "Spin Undian" (animasi roda acak nama berhadiah) yang adil dan transparan.
5. **Activity Logs (`logs`)**:
   - Pencatatan seluruh aksi: Login, Logout, Vote Submit, Create Candidate, Update Candidate, Delete Candidate, Import Voters, Reset Votes.
   - Menyimpan atribut: `user_type`, `user_id`, `action`, `description`, `ip_address`, `user_agent`, `created_at`.

---

### 5. Non-Functional Requirements
- **Performance**: Response time transaksi vote $< 100\text{ ms}$ menggunakan database transaction with row-level locking.
- **Reliability**: SSE connection auto-reconnect fallback mechanism.
- **UX/Design**: Desain ultra-modern bergaya SaaS enterprise (palet indigo/emerald/slate, glassmorphism, fluid SVG wave animations, dark/light contrast high readability).
- **Responsive**: Mendukung layar Kios Tablet/Touchscreen (pemilih) dan Laptop/Desktop Panitia (Admin).
