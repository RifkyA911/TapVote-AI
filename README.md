# TapVote AI — Next-Gen E-Voting System & Realtime Election Intelligence

[![Laravel 12](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP 8.3+](https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=flat&logo=php&logoColor=white)](https://www.php.net)
[![Tailwind CSS v4](https://img.shields.io/badge/Tailwind_CSS-v4.0-38B2AC?style=flat&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Model Context Protocol](https://img.shields.io/badge/MCP-Compatible-blueviolet?style=flat)](https://modelcontextprotocol.io)
[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

**TapVote AI** is a state-of-the-art, transparent, and senior/boomer-friendly electronic voting system engineered for institutional and Cooperative General Elections (*Ketua & Pengawas*). It features contactless **RFID Mifare (ISO 14443A)** card tap authentication, tactile tablet voting kiosks, **AI Election Conclusions & Predictive Analytics**, real-time live result streaming via **Server-Sent Events (SSE)**, and a built-in **Model Context Protocol (MCP)** server for AI agent integration.

---

## 🌟 Key Features

### 1. Tablet & Mobile Kiosk Voting Booth (`/voter` & `/vote`)
- **Direct Smartphone Web NFC Support (`NDEFReader`)**:
  - Android Chrome users can tap physical NFC/RFID cards directly against the back of their smartphone.
  - Built-in guidance modal for enabling Web NFC over LAN IP development origins via `chrome://flags/#unsafely-treat-insecure-origin-as-secure`.
- **Hardware-Only Tap (Zero Text Input)**:
  - Eliminated manual text input fields entirely to ensure complete voting integrity and prevent spoofing.
  - Automatically captures hardware RFID reader keystrokes or Web NFC tag serial numbers without exposing any editable inputs.
- **Unified Sticky Footer Ballot Dock (`/vote`)**:
  - Fixed-bottom persistent dock with candidate selection summary chips, "Kembali", "Lanjut", "Tinjau Pilihan", and "Vote Sekarang" buttons dynamically updating across ballot steps.
- **Calm, Senior-Friendly Visuals**:
  - Relaxed, gentle ripple waves (4.5s) optimized for older eyes.
  - Floating card hover animation with tactile spring-lift ("sundul") on touch/hover (`.ballot-card-sundul`).
  - Fluid **Nova Green** success transition and gentle **Danger Red** error warning wave.
- **Privacy & Security**:
  - Personal NIK numbers are completely omitted from candidate cards on public voting ballots.
- **Demo Accounts Interactive DataTable**:
  - Searchable, status-filtered DataTable replacing old static card grids for testing and dry runs.
- **Interactive Audio Feedback (Web Audio API)**:
  - Zero-latency, browser-synthesized audio effects for card tap, successful vote, errors, candidate selection, and modal transitions.

### 2. Public Live Count (`/`)
- **Real-Time SSE Streaming**: Live updates broadcast without page refreshes.
- **Slimmed Bar Chart & Candidate Avatars**: Slimmer, modern bars (`barThickness: 22`) powered by Chart.js featuring custom circular candidate portrait avatars, ballot numbers, and candidate names directly on the x-axis.
- **Election Categories & Procedural Guide**: Clean explanatory section between live tally charts and candidate cards explaining Chairman, Supervisory Board, and 3-step voting procedure.
- **Candidate Manifesto & Profile Modals**: Click to inspect Vision (*Visi*), Mission (*Misi*), and background.
- **Clean Public Header**: High-contrast, spacious layout with single official timestamp and instant language switcher (`EN` / `ID`).

### 3. Executive Admin Control Panel (`/admin`)
- **Procedural 3D RFID Smart Card Reconstructed (`/admin/dashboard`)**:
  - High-resolution procedural Three.js model generated via HTML5 canvas mirroring `contoh.png`: coral-crimson gradient, dynamic white swoosh curve, gentleman photo in light blue collared shirt, solid black box with white "RIFKY" text and "018513 • ICT DIV.", vertical barcode, and TapVote diamond crest.
  - Pure, spotless white back face ("back itu putih polos") with oblong slot punch hole.
  - Full 3D accessories: black loop cord, black breakaway clasp buckle, and double-loop crimson satin lanyard ribbon.
  - 360° interactive rotation on X & Y axes with auto-spin, flip, 360° X, and view reset controls.
- **Mata Langit (Sky Eye Analytics) Module (`/admin/analytics`)**:
  - Deep telemetry center featuring real database analytics: 24-hour voting velocity histogram, department turnout ranking and participation matrix, RFID Mifare authenticity audit & foreign card scan tracking, and forensic audit timeline.
- **System Settings Management Panel (`/admin/settings`)**:
  - Comprehensive control panel for election title, quorum threshold percentage, Gemini AI model and API keys, voice greeting autoplay, sound effects toggles, and kiosk session timeouts.
- **SpeechSynthesis Auto-Play Welcome Greeting**:
  - Web Speech API greeting upon administrator login: *"Selamat datang, Mas Admin di Control Panel TapVote AI."*
- **HeadlessUI-Style Confirmation Modal (`layouts/admin.blade.php`)**:
  - Custom HeadlessUI-style animated popup modal replacing native browser `confirm()` for START, PAUSE, and STOP voting system controls.
- **Candidate Drag-and-Drop Reordering (`/admin/ketua`, `/admin/pengawas`)**:
  - Interactive HTML5 table row drag-and-drop with real-time ballot number recalculation and asynchronous AJAX persistence.
- **Custom Controller-Based Vector PDF & Excel Exports**:
  - Real server-side PDF exports via DomPDF (`Barryvdh\DomPDF\Facade\Pdf`) and CSV/Excel downloads for Chairman, Supervisor, Forensic Traceback, Doorprize, and Voter rosters.
- **Doorprize Raffle & Digital Gyroscope Roulette (`/admin/reports/doorprize`)**:
  - Matched "Pilih" button styling to "Edit" button with smooth anchor scroll directly to `#undi-section`.
  - High-tech digital gyroscope roulette animation with glowing concentric rings and clean light-theme styling.
- **Uniform Eligible Voters DataTable (`/admin/voters`)**:
  - Replaced DPT with "Eligible Voters", added a foldable "Filter" header with icon, renamed "Run QUERY" to "Apply", and changed department text input to `<select>` dropdown in the Add Voter modal.
- **Progressive Web App (PWA)**:
  - Supports installable standalone web application on Android, iOS, Windows, and Linux via Web App Manifest and Service Worker.
  - Polished responsive filter toolbar optimized for **iPad Mini (768px-834px)**, mobile, and desktop.

### 4. Doorprize System & Winner Claim Tracking (`/admin/reports/doorprize`)
- **Admin Light Theme Harmonization**: Full light theme overhaul replacing dark backgrounds.
- **Master Rewards DataTable with Image Upload**:
  - Inventories rewards with title, quantity, category, and sponsor.
  - Direct prize image upload with preview, stored on the `public/storage/doorprizes` disk.
- **Official Winner Claim Management**:
  - Tracks winner claim status: **Sudah Diterima** (`accepted`), **Ditolak / Gugur** (`rejected`), **Belum Diambil** (`pending`), or **Alasan Lain** (`other`).
  - Claim notes modal and automated `received_at` timestamps.
- **No-Countdown High-FPS Animated SVG Lottery Machine**:
  - Dynamic animated SVG slot reel and tumbler with synthetic Web Audio ticker sounds and smooth deceleration easing.
- **Standalone Cinema Stage View (`/doorprize`)**:
  - Dedicated big-screen projector display without admin controls with winner ticker marquee and keyboard shortcuts (`Space` to roll, `F` for fullscreen).

### 5. Model Context Protocol (MCP) Server
- Integrated MCP server (`mcp/server.js`) compliant with standard MCP JSON-RPC protocol.
- Connect **Claude Desktop**, **Cursor**, **Antigravity**, or remote LLMs to query live voter turnout, candidate profiles, AI conclusions, and verify voter card validity.
- Supports both **Stdio** and **HTTP/SSE** transport (`--http 3100`).

---

## 💻 System Prerequisites

| Requirement | Minimum Version | Notes |
|---|---|---|
| **PHP** | `^8.3.0` | Extensions: `pdo_mysql`, `mbstring`, `openssl`, `bcmath`, `curl`, `xml` |
| **Composer** | `^2.5.0` | PHP dependency manager |
| **Node.js** | `^18.0.0` (LTS recommended) | With npm |
| **MySQL / MariaDB** | `^8.0` / `^10.4` | Database service |
| **RFID Reader** | USB Mifare 13.56MHz | Emulates keyboard keystrokes ending with `Enter` |

---

## 🚀 Step-by-Step Installation Guide

### 1. Clone Repository
```bash
git clone https://github.com/RifkyA911/TapVote-AI.git
cd TapVote-AI
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node.js frontend & MCP dependencies
npm install
```

### 3. Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application encryption key
php artisan key:generate
```

Edit `.env` to configure your database connection:
```ini
APP_NAME="TapVote AI"
APP_ENV=local
APP_LOCALE=en
APP_FALLBACK_LOCALE=en

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tapvote_ai
DB_USERNAME=root
DB_PASSWORD=
```

> **Note for Laragon users**: Make sure MySQL is started from Laragon, and PHP 8.3 is selected.

### 4. Database Migration & Seed
Run migrations and load default seeders (candidates, demo voters, and administrator account):
```bash
php artisan migrate --seed
```

### 5. Build Frontend Assets
```bash
# Production build
npm run build

# Or development hot-reload
npm run dev
```

### 6. Start Application Server
```bash
php artisan serve
```

Access the system at:
- **Public Live Count**: [http://localhost:8000/](http://localhost:8000/)
- **Voter Kiosk**: [http://localhost:8000/voter](http://localhost:8000/voter)
- **Admin Control Panel**: [http://localhost:8000/admin/dashboard](http://localhost:8000/admin/dashboard)

---

## 🔐 Default Credentials

### Admin Account
- **URL**: [http://localhost:8000/admin/login](http://localhost:8000/admin/login)
- **Email**: `admin@tapvote.test`
- **Password**: `password`

### Test Demo RFID Cards
| Member Name | NIK | RFID UID | Status |
|---|---|---|---|
| **Budi Santoso** | `1001` | `A1B2C3D4` | Eligible (Belum Memilih) |
| **Siti Aminah** | `1002` | `E5F6A7B8` | Eligible (Belum Memilih) |
| **Ahmad Dahlan** | `1003` | `C9D8E7F6` | Eligible (Belum Memilih) |
| **Dewi Sartika** | `1004` | `1A2B3C4D` | Eligible (Belum Memilih) |

---

## 🤖 Model Context Protocol (MCP) Integration

Connect your favorite AI agent to query TapVote AI live data.

### Available MCP Tools
- `get_election_metrics`: Live turnout %, total verified ballots, quorum status, and current leaders.
- `get_candidate_standings`: Vote count and percentage breakdown for Chairman and Supervisory Board.
- `get_candidate_profile`: Retrieve full biography, Vision, Mission, and live votes for any candidate.
- `get_ai_election_conclusion`: Run the analytical AI engine to get quorum status, victory margin, and guidance.
- `verify_voter_card`: Check if an RFID card UID or member NIK is registered and eligible to vote.

### 1. Stdio Mode (Claude Desktop, Cursor, Antigravity)
Run via stdio:
```bash
npm run mcp
# or
node mcp/server.js
```

**Claude Desktop Configuration (`claude_desktop_config.json`)**:
```json
{
  "mcpServers": {
    "tapvote-ai": {
      "command": "node",
      "args": ["C:\\laragon\\www\\TapVote-AI\\mcp\\server.js"],
      "cwd": "C:\\laragon\\www\\TapVote-AI"
    }
  }
}
```

### 2. Public HTTP / SSE Mode
Run the MCP server over HTTP/SSE:
```bash
npm run mcp:http
# Server will listen on http://0.0.0.0:3100
```
- Health Check: `http://localhost:3100/health`
- Tool Catalog: `http://localhost:3100/tools`
- Query Endpoint: `POST http://localhost:3100/query` with payload `{"tool": "get_election_metrics"}`

---

## 🛠️ CLI Diagnostic & Management Tools

TapVote AI includes built-in Artisan commands for rapid terminal diagnostics:

```bash
# Verify system health (DB connection, public/storage junction, Gemini AI key, voting lifecycle)
php artisan tapvote:health

# Print real-time DPT turnout, participation percentage, and quorum achievement
php artisan tapvote:recap
```

---

## 🧪 Testing

Run automated PHPUnit test suites:
```bash
php artisan test
```

---

## 📄 License

This open-source project is licensed under the [MIT License](LICENSE).
