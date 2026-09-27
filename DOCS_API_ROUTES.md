# Routes & RBAC API Documentation
## TapVote AI - Endpoints, Middleware Matrix & Request Payloads

---

### 1. Route Table & Access Control Matrix

| Endpoint | Method | Middleware / Guard | Peran (Role) | Keterangan & Tujuan |
| :--- | :---: | :--- | :--- | :--- |
| `/` | GET | `web` | Public | Landing page TapVote AI (Live summary, navigasi bilik, status sistem) |
| `/lang/{locale}` | GET | `web` | Public / Admin | Language switcher (EN / ID) dengan cookie persisten `app_locale` & session |
| `/live-count/stream` | GET | `web` | Public | Server-Sent Events (SSE) stream publik untuk perolehan suara realtime |
| `/live-count/data` | GET | `web` | Public | JSON snapshot telemetry suara, partisipasi, dan kandidat terdepan |
| `/voter` | GET | `web` | Public / Voter | Kios tap kartu RFID Mifare ISO 14443A + Web NFC sensor + Demo Simulator |
| `/voter/tap` | POST | `web` | Public / Voter | Autentikasi UID RFID / NIK, validasi hak pilih, inisiasi sesi bilik |
| `/voter/logout` | POST | `web` | Voter | Logout manual sesi pemilih sebelum submisi |
| `/vote` | GET | `web`, `voter.auth` | Voter (Session aktif) | Halaman pemilihan tactile kiosk: 1 Kandidat Ketua & 1 Pengawas |
| `/vote/store` | POST | `web`, `voter.auth` | Voter (Session aktif) | Menyimpan suara secara atomik (DB Transaction, lockForUpdate) |
| `/finalization` | GET | `web` | Voter | Animasi sukses confetti & auto-logout countdown 5 detik |
| `/admin/login` | GET | `web`, `admin.ip`, `guest` | Public | Halaman form login administrator |
| `/admin/login` | POST | `web`, `admin.ip`, `guest` | Public | Autentikasi email & password admin dengan IP protection |
| `/admin/logout` | POST | `web`, `admin.ip`, `auth` | Admin | Invalidate session admin dan redirect ke login |
| `/admin/dashboard` | GET | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Dashboard eksekutif metrik realtime, live chart, & status voting |
| `/admin/stream/results` | GET | `web`, `admin.ip`, `auth`, `role:admin` | Admin | SSE Stream khusus admin dengan telemetry audit & dynamic frequency |
| `/admin/api/live-results` | GET | `web`, `admin.ip`, `auth`, `role:admin` | Admin | JSON API polling fallback metrik suara & turnout |
| `/admin/api/ai-conclusion` | GET | `web`, `admin.ip`, `auth`, `role:admin` | Admin | AI Reasoning Engine: Gemini 2.0 Flash / Local Heuristic fallback |
| `/admin/voting/status` | POST | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Pengendali status pemilihan: START (Aktif), PAUSE (Jeda), STOP (Tutup) |
| `/admin/settings/gemini-key` | POST | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Simpan Google Gemini API key terenkripsi ke tabel `app_settings` |
| `/admin/settings/gemini-test` | POST | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Uji konektivitas, validitas token, dan latensi (ms) Gemini AI |
| `/admin/dashboard/export/excel` | GET | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Ekspor rekapitulasi resmi format Excel Spreadsheet (UTF-8 BOM) |
| `/admin/dashboard/export/pdf` | GET | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Unduh sertifikat berita acara rekapitulasi resmi Vector PDF DomPDF |
| `/admin/analytics` | GET | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Deep Telemetry: Tab terpisah Ketua & Pengawas, partisipasi departemen |
| `/admin/analytics/ai-analysis` | GET | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Analisis anomali lonjakan & evaluasi kuorum voting berbasis AI |
| `/admin/analytics/export-pdf` | GET | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Ekspor Dokumen Resmi Analisis Telemetri format Vector PDF A4 |
| `/admin/vote-flow` | GET | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Visualisasi Sankey Diagram: Distribusi pilihan departemen ke kandidat |
| `/admin/vote-flow/data` | GET | `web`, `admin.ip`, `auth`, `role:admin` | Admin | JSON payload node & link Sankey per departemen dan kandidat |
| `/admin/vote-flow/ai-summary` | GET | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Ringkasan naratif AI terhadap kohesi elektoral dan basis suara divisi |
| `/admin/vote-flow/export-pdf` | GET | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Unduh laporan Berita Acara Distribusi Suara Departemen Vector PDF |
| `/admin/settings` | GET | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Panel konfigurasi sistem, parameter kuorum, durasi sesi, dan tema |
| `/admin/settings` | POST | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Simpan perubahan parameter sistem ke tabel `app_settings` |
| `/admin/ketua` | GET | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Daftar kandidat ketua dengan nomor urut dan foto |
| `/admin/ketua/create` | GET | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Form input kandidat ketua baru |
| `/admin/ketua` | POST | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Simpan data kandidat ketua baru |
| `/admin/ketua/{nik}/edit` | GET | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Form peremajaan data kandidat ketua |
| `/admin/ketua/{nik}` | PUT | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Simpan update data kandidat ketua |
| `/admin/ketua/{nik}` | DELETE | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Hapus kandidat ketua |
| `/admin/ketua/reorder` | POST | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Drag-and-drop reordering nomor urut kandidat ketua |
| `/admin/pengawas` | GET | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Daftar kandidat pengawas dengan nomor urut dan foto |
| `/admin/pengawas/create` | GET | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Form input kandidat pengawas baru |
| `/admin/pengawas` | POST | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Simpan data kandidat pengawas baru |
| `/admin/pengawas/{nik}/edit`| GET | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Form edit kandidat pengawas |
| `/admin/pengawas/{nik}` | PUT | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Simpan update kandidat pengawas |
| `/admin/pengawas/{nik}` | DELETE | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Hapus kandidat pengawas |
| `/admin/pengawas/reorder` | POST | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Drag-and-drop reordering nomor urut kandidat pengawas |
| `/admin/voters` | GET | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Master DPT pemilih, status hak suara, dan kelayakan undian |
| `/admin/voters/query` | GET, POST, QUERY | `web`, `admin.ip`, `auth`, `role:admin` | Admin | RFC safe HTTP QUERY endpoint untuk filter pencarian server-side DPT |
| `/admin/voters` | POST | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Pendaftaran anggota pemilih manual |
| `/admin/voters/import` | POST | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Impor massal data anggota DPT format CSV/Excel |
| `/admin/voters/template` | GET | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Unduh template CSV impor anggota standar |
| `/admin/voters/export` | GET | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Ekspor daftar pemilih format CSV UTF-8 BOM |
| `/admin/voters/export-pdf` | GET | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Unduh daftar DPT pemilih resmi format Vector PDF A4 |
| `/admin/voters/{nik}/toggle-raffle` | PATCH | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Toggle hak keikutsertaan doorprize (`can_raffle`) |
| `/admin/voters/{nik}` | DELETE | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Hapus anggota pemilih dari DPT |
| `/admin/voters/reset-votes`| POST | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Reset seluruh hasil suara & status pilih (Gladi/Demo) |
| `/admin/reports/ketua` | GET | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Laporan pemenang Ketua Koperasi & breakdown departemen |
| `/admin/reports/ketua/export` | GET | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Ekspor Excel perolehan suara ketua |
| `/admin/reports/ketua/export-pdf` | GET | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Berita Acara Penetapan Pemenang Ketua Koperasi format Vector PDF |
| `/admin/reports/pengawas` | GET | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Laporan pemenang Pengawas Koperasi & breakdown departemen |
| `/admin/reports/pengawas/export` | GET | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Ekspor Excel perolehan suara pengawas |
| `/admin/reports/pengawas/export-pdf` | GET | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Berita Acara Penetapan Pemenang Pengawas format Vector PDF |
| `/admin/reports/traceback`| GET | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Audit trail rekonsiliasi saksi (pilihan anggota & timestamp) |
| `/admin/reports/traceback/export` | GET | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Ekspor CSV rekap jejak audit saksi |
| `/admin/reports/traceback/export-pdf` | GET | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Unduh Laporan Rekonsiliasi Saksi & Audit Trail format Vector PDF |
| `/admin/reports/doorprize`| GET | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Manajemen reward, undian interaktif, & log pemenang |
| `/admin/reports/doorprize/draw` | POST | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Trigger undian pemenang otomatis via server-side CSPRNG |
| `/admin/reports/doorprize/items` | POST | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Tambah jenis hadiah doorprize baru dengan foto |
| `/admin/reports/doorprize/items/{id}` | POST, PUT, PATCH | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Update spesifikasi hadiah, kuota stok, dan foto |
| `/admin/reports/doorprize/items/{id}` | DELETE | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Hapus master hadiah doorprize |
| `/admin/reports/doorprize/winners/{id}` | DELETE | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Batalkan/hapus catatan pemenang undian |
| `/admin/reports/doorprize/winners/{id}/status` | POST, PATCH | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Update status klaim: pending, accepted, rejected, other |
| `/admin/reports/doorprize/export-excel` | GET | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Ekspor daftar pemenang doorprize format Excel |
| `/admin/reports/doorprize/export-pdf` | GET | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Berita Acara Serah Terima Hadiah Doorprize format Vector PDF |
| `/doorprize` | GET | `web` | Public | Panggung penonton layar lebar / proyektor 3D Three.js |
| `/doorprize/data` | GET | `web` | Public | JSON eligible voters pool (`pilih='T' AND can_raffle=1`) & reward list |
| `/admin/logs` | GET | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Viewer audit trail menyeluruh seluruh mutasi sistem |
| `/admin/logs/ai-analysis` | GET | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Analisis integritas sistem & deteksi anomali log via Gemini AI |
| `/admin/docs` | GET | `web`, `admin.ip`, `auth`, `role:admin` | Admin | Dokumentasi sistem interaktif terintegrasi di dalam panel admin |

---

### 2. Request & Response Payloads

#### 2.1. Voter RFID Tap (`POST /voter/tap`)
**Request Body**:
```json
{
  "_token": "csrf_token_here",
  "rfid": "E280681A"
}
```
**Response Sukses (Redirect to `/vote`)**:
```http
HTTP/1.1 302 Found
Location: /vote
```
**Response Gagal**:
```http
HTTP/1.1 302 Found
Location: /voter
Set-Cookie: alert_error="Kartu RFID tidak terdaftar atau hak suara telah digunakan!"
```

#### 2.2. Submit Vote (`POST /vote/store`)
**Request Body**:
```json
{
  "_token": "csrf_token_here",
  "ketua_nik": "KT01",
  "pengawas_nik": "PW02"
}
```
**Response Sukses**:
```http
HTTP/1.1 302 Found
Location: /finalization?status=success
```

#### 2.3. Safe HTTP QUERY Method (`QUERY /admin/voters/query`)
Mendukung RFC Safe Query dengan payload pencarian terenkapsulasi:
```http
QUERY /admin/voters/query HTTP/1.1
Content-Type: application/json
X-CSRF-TOKEN: ...

{
  "search": "Dimas",
  "status": "voted",
  "dept": "Engineering",
  "sort_by": "nama",
  "sort_dir": "asc",
  "per_page": 25
}
```
**Response JSON**:
```json
{
  "success": true,
  "data": [
    {
      "nik": "EMP-001",
      "nama": "Dimas Pratama",
      "dept": "Engineering",
      "rfid": "E280681A",
      "pilih": "T",
      "can_raffle": true,
      "voted_at": "2026-09-27 10:14:22"
    }
  ],
  "pagination": {
    "total": 1,
    "current_page": 1,
    "last_page": 1
  }
}
```

#### 2.4. Drag & Drop Reorder Candidates (`POST /admin/ketua/reorder` / `POST /admin/pengawas/reorder`)
**Request Body**:
```json
{
  "_token": "csrf_token_here",
  "order": ["KT03", "KT01", "KT02"]
}
```
**Response**:
```json
{
  "success": true,
  "message": "Urutan nomor kandidat berhasil diperbarui."
}
```

#### 2.5. Toggle Raffle Eligibility (`PATCH /admin/voters/{nik}/toggle-raffle`)
**Request Body**:
```json
{
  "_token": "csrf_token_here"
}
```
**Response**:
```json
{
  "success": true,
  "can_raffle": false,
  "message": "Status kelayakan undian berhasil diubah."
}
```

#### 2.6. Gemini Latency & Token Diagnostic (`POST /admin/settings/gemini-test`)
**Request Body**:
```json
{
  "_token": "csrf_token_here",
  "api_key": "AIzaSy..."
}
```
**Response**:
```json
{
  "success": true,
  "latency_ms": 342,
  "model": "gemini-2.0-flash",
  "status": "CONNECTED",
  "message": "API key valid dan responsif."
}
```
