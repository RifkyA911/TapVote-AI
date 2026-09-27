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
    - **Web NFC Support**: Mendukung tap langsung kartu RFID/NFC fisik di punggung smartphone Android Chrome dengan hardware `NDEFReader`.
3. **Halaman `/finalization` (Celebration & Auto-Logout)**:
    - Menampilkan animasi fluid (Confetti canvas, fluid wave / pulse effect, verified badge).
    - Pesan terima kasih personalisasi atas partisipasi pemilih.
    - Timer otomatis countdown **5 detik** dengan visual progress bar circular / linear yang secara otomatis me-logout session dan mengarahkan kembali ke `/voter` untuk antrean pemilih berikutnya.

#### 4.2. Admin Flow & Dashboard
1. **Live Realtime Monitoring (SSE Engine)**:
    - Endpoint `/admin/stream/results` mentransmisikan data event-driven:
      - Total Pemilih Terdaftar, Total Suara Masuk, Persentase Partisipasi (*Turnout Rate*).
      - Grafik Bar & Donut Chart perolehan suara Kandidat Ketua & Pengawas.
      - Live Feed pemilih terakhir yang baru saja melakukan tap & vote.
2. **AI Intelligence & Telemetry Reasoning Engine**:
    - **Google Gemini 2.0 Flash REST Integration**: Analisis prediktif, kurva kecepatan suara 24 jam, deteksi anomali voting rush, dan penilaian kuorum otomatis.
    - **Deterministic Local Heuristic AI Fallback**: Menjamin kesimpulan analisis AI tetap tersedia dalam skenario offline tanpa koneksi internet.
    - **Cybersecurity & Forensic Reasoner (`/admin/logs`)**: Evaluasi ancaman brute force RFID, tap kartu asing, dan verifikasi integritas tamper-proof.
3. **Kandidat Management (Ketua & Pengawas)**:
    - Upload foto resmi kandidat (storage symlink support) dengan live image preview.
    - Input Visi dan Misi dengan format teks terstruktur (`TEXT` type).
    - HTML5 Drag-and-Drop table row reordering untuk mengatur nomor urut surat suara secara interaktif.
    - Cardless lightbox preview modal untuk inspeksi foto resolusi tinggi.
4. **Voter Management & Dynamic HTTP QUERY**:
    - Tambah, edit, dan hapus data pemilih manual.
    - Import file Excel / CSV (kolom: `nik`, `nama`, `dept`, `rfid`).
    - Filter dinamis aman via metode HTTP `QUERY` (RFC Draft safe method) dengan sorting multi-kolom.
    - Per-voter raffle toggle switch berbasis Tailwind 2-elemen tanpa overflow.
    - Fitur Reset Suara individual atau massal (untuk keperluan gladi resik / simulasi).
5. **Vote Flow & Sankey Diagram (`/admin/vote-flow`)**:
    - Pemetaan 100% data riil relasional basis data dari pergerakan suara unit kerja/departemen ke pasangan kandidat terpilih via Apache ECharts Sankey.
    - AI Vision Flow Intelligence untuk mendeteksi swing departments dan koalisi dominan.
6. **Laporan & Audit Eksekutif (Vector PDF & Excel)**:
    - **Pemenang Ketua Koperasi**: Menampilkan kandidat suara terbanyak, total suara, persentase, dan breakdown per departemen.
    - **Pemenang Pengawas Koperasi**: Menampilkan kandidat suara terbanyak, total suara, persentase, dan breakdown per departemen.
    - **Trace Back Report**: Matriks akuntabilitas untuk memeriksa pilihan masing-masing anggota (NIK, Nama, Dept, Pilihan Ketua, Pilihan Pengawas, Waktu Vote).
    - **Doorprize Undian Pemilih**: Otomatis memfilter daftar anggota berstatus `pilih == 'T'`. Mesin undian 3D Three.js kotak hadiah emas dengan ribbon dan efek partikel floating.
    - Unduh berita acara dan rekapitulasi resmi dalam format **Vector PDF DomPDF** dan **Excel UTF-8 BOM**.
7. **System Settings & Controls (`/admin/settings`)**:
    - Panel kendali autosave debounce real-time: judul resmi dwibahasa (`election_title_id` & `election_title_en`), kuorum %, API Key Gemini AI, batas waktu kiosk, dan sakelar hardware sensor.
8. **Model Context Protocol (MCP) Server**:
    - Standar integrasi agentic AI (`mcp/server.js`) via Stdio dan SSE/HTTP transport (`--http 3100`) untuk interaksi LLM eksternal.
9. **Activity Logs (`logs`)**:
    - Pencatatan seluruh aksi: Login, Logout, Vote Submit, Create Candidate, Update Candidate, Delete Candidate, Import Voters, Reset Votes.
    - Menyimpan atribut: `user_type`, `user_identifier`, `action`, `module`, `description`, `ip_address`, `user_agent`, `created_at`.

---

### 5. Non-Functional Requirements
- **Performance**: Response time transaksi vote $< 100\text{ ms}$ menggunakan database transaction with row-level locking (`SELECT ... FOR UPDATE`).
- **Reliability**: SSE connection auto-reconnect fallback mechanism.
- **UX/Design**: Desain ultra-modern bergaya SaaS enterprise (palet indigo/emerald/slate, glassmorphism, fluid SVG wave animations, dark/light contrast high readability).
- **Internationalization**: Dukungan dwibahasa penuh (**Bahasa Indonesia** dan **English**) dengan 740+ frasa terjemahan terpadu.
- **Theme Architecture**: Dark Mode, Light Mode, dan System Auto-detection tersinkronisasi di `localStorage`.
- **Responsive**: Mendukung layar Kios Tablet/Touchscreen (pemilih), Smartphone Android (Web NFC), dan Laptop/Desktop Panitia (Admin) dengan off-canvas navigation drawer.
- **Automated Verification**: Dilengkapi 40 test case terotomatisasi (160 assertions) yang menjamin keandalan alur core voting.
