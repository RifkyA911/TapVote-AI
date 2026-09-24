# Routes & RBAC API Documentation
## TapVote AI - Endpoints & Middleware Matrix

---

### 1. Route Table & Access Control Matrix

| Endpoint | Method | Middleware / Guard | Peran (Role) | Keterangan & Tujuan |
| :--- | :---: | :--- | :--- | :--- |
| `/` | GET | `web` | Public | Landing page TapVote AI (Overview & Navigasi ke Kios/Admin) |
| `/voter` | GET | `web` | Public / Voter | Halaman Tap Kartu RFID Mifare ISO 14443A + Demo Simulator |
| `/voter/tap` | POST | `web` | Public / Voter | Autentikasi UID RFID / NIK, menginisiasi voter session |
| `/voter/logout` | POST | `web` | Voter | Logout sesi pemilih manual |
| `/vote` | GET | `web`, `voter.auth` | Voter (Session aktif) | Halaman pemilihan 1 Kandidat Ketua & 1 Pengawas |
| `/vote/store` | POST | `web`, `voter.auth` | Voter (Session aktif) | Menyimpan pilihan suara secara atomik ke database |
| `/finalization` | GET | `web` | Voter | Animasi sukses suara tersimpan & countdown 5 detik auto-logout |
| `/admin/login` | GET | `web`, `guest` | Public | Form login panitia / admin |
| `/admin/login` | POST | `web`, `guest` | Public | Otentikasi kredensial email & password admin |
| `/admin/logout` | POST | `web`, `auth` | Admin | Logout admin |
| `/admin/dashboard` | GET | `web`, `auth`, `role:admin` | Admin | Dashboard metrik realtime & live monitor |
| `/admin/stream/results` | GET | `web`, `auth`, `role:admin` | Admin | SSE Stream live hasil suara dan persentase |
| `/admin/ketua` | GET | `web`, `auth`, `role:admin` | Admin | List data kandidat ketua |
| `/admin/ketua/create` | GET | `web`, `auth`, `role:admin` | Admin | Form tambah kandidat ketua |
| `/admin/ketua` | POST | `web`, `auth`, `role:admin` | Admin | Simpan data kandidat ketua |
| `/admin/ketua/{nik}/edit` | GET | `web`, `auth`, `role:admin` | Admin | Form edit kandidat ketua |
| `/admin/ketua/{nik}` | PUT | `web`, `auth`, `role:admin` | Admin | Update kandidat ketua |
| `/admin/ketua/{nik}` | DELETE | `web`, `auth`, `role:admin` | Admin | Hapus kandidat ketua |
| `/admin/pengawas` | GET | `web`, `auth`, `role:admin` | Admin | List data kandidat pengawas |
| `/admin/pengawas/create` | GET | `web`, `auth`, `role:admin` | Admin | Form tambah kandidat pengawas |
| `/admin/pengawas` | POST | `web`, `auth`, `role:admin` | Admin | Simpan data kandidat pengawas |
| `/admin/pengawas/{nik}/edit`| GET | `web`, `auth`, `role:admin` | Admin | Form edit kandidat pengawas |
| `/admin/pengawas/{nik}` | PUT | `web`, `auth`, `role:admin` | Admin | Update kandidat pengawas |
| `/admin/pengawas/{nik}` | DELETE | `web`, `auth`, `role:admin` | Admin | Hapus kandidat pengawas |
| `/admin/voters` | GET | `web`, `auth`, `role:admin` | Admin | List data pemilih, status pilih, dan filter |
| `/admin/voters` | POST | `web`, `auth`, `role:admin` | Admin | Tambah pemilih tunggal manual |
| `/admin/voters/import` | POST | `web`, `auth`, `role:admin` | Admin | Import pemilih via file Excel / CSV |
| `/admin/voters/{nik}` | DELETE | `web`, `auth`, `role:admin` | Admin | Hapus data pemilih |
| `/admin/voters/reset-votes`| POST | `web`, `auth`, `role:admin` | Admin | Reset seluruh hasil suara (keperluan gladi/demo) |
| `/admin/reports/ketua` | GET | `web`, `auth`, `role:admin` | Admin | Laporan Pemenang Ketua Koperasi & breakdown |
| `/admin/reports/pengawas` | GET | `web`, `auth`, `role:admin` | Admin | Laporan Pemenang Pengawas Koperasi & breakdown |
| `/admin/reports/traceback`| GET | `web`, `auth`, `role:admin` | Admin | Trace back pilihan anggota (audit trail saksi) |
| `/admin/reports/doorprize`| GET | `web`, `auth`, `role:admin` | Admin | Daftar pemilih yang berhak ikut undian + Wheel Spin |
| `/admin/logs` | GET | `web`, `auth`, `role:admin` | Admin | Audit Trail Log Viewer (semua aktivitas CRUD) |

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
