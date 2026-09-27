<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat&logo=laravel&logoColor=white" alt="Laravel 12" />
  <img src="https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=flat&logo=php&logoColor=white" alt="PHP 8.3+" />
  <img src="https://img.shields.io/badge/Tailwind_CSS-v4.0-38B2AC?style=flat&logo=tailwind-css&logoColor=white" alt="Tailwind CSS v4" />
  <img src="https://img.shields.io/badge/Three.js-3D-000000?style=flat&logo=three.js&logoColor=white" alt="Three.js" />
  <img src="https://img.shields.io/badge/MCP-Compatible-blueviolet?style=flat" alt="MCP" />
  <img src="https://img.shields.io/badge/License-MIT-green.svg" alt="MIT" />
</p>

<p align="center">
  <picture>
    <source media="(prefers-color-scheme: dark)" srcset="https://readme-typing-svg.herokuapp.com?font=Plus+Jakarta+Sans&weight=800&size=36&duration=3500&pause=1200&color=F59E0B&center=true&vCenter=true&multiline=true&width=700&height=90&lines=TapVote+AI;Next-Gen+E-Voting+%26+Election+Intelligence" />
    <img src="https://readme-typing-svg.herokuapp.com?font=Plus+Jakarta+Sans&weight=800&size=36&duration=3500&pause=1200&color=0F172A&center=true&vCenter=true&multiline=true&width=700&height=90&lines=TapVote+AI;Next-Gen+E-Voting+%26+Election+Intelligence" alt="TapVote AI" />
  </picture>
</p>

<p align="center">
  <sub>Contactless RFID Voting • Three.js 3D Cards • AI Analytics • SSE Live Count • Doorprize Lottery • MCP Server</sub>
</p>

<p align="center">

```
 ┌──────────────────────────────────────────────────────────────────────┐
 │                                                                      │
 │   📱 NFC/RFID TAP      🗳️ TACTILE KIOSK      📊 LIVE SSE COUNT    │
 │   ─────────────── ──▶  ────────────────── ──▶  ─────────────────    │
 │                                                                      │
 │   🤖 AI GEMINI         🎰 3D DOORPRIZE        🔗 MCP PROTOCOL      │
 │   ─────────────── ──▶  ────────────────── ──▶  ─────────────────    │
 │                                                                      │
 │   🏗️ THREE.JS 3D       📈 APEX CHARTS         🔐 PWA READY         │
 │                                                                      │
 └──────────────────────────────────────────────────────────────────────┘
```

</p>

---

# TapVote AI — Next-Gen E-Voting System & Realtime Election Intelligence

**TapVote AI** is a state-of-the-art, transparent, and senior/boomer-friendly electronic voting system engineered for institutional and Cooperative General Elections (*Ketua & Pengawas*). It features contactless **RFID Mifare (ISO 14443A)** card tap authentication, tactile tablet voting kiosks, **AI Election Conclusions & Predictive Analytics**, real-time live result streaming via **Server-Sent Events (SSE)**, Three.js 3D immersive visuals, a digital doorprize lottery system, and a built-in **Model Context Protocol (MCP)** server for AI agent integration.

---

## 🌟 Key Features

### 1. Tablet & Mobile Kiosk Voting Booth (`/voter` & `/vote`)
- **Direct Smartphone Web NFC Support (`NDEFReader`)**:
  - Android Chrome users can tap physical NFC/RFID cards directly against the back of their smartphone.
  - Built-in guidance modal for enabling Web NFC over LAN IP development origins via `chrome://flags/#unsafely-treat-insecure-origin-as-secure`.
- **Smooth GSAP Entrance Animation**:
  - Fluid, cinematic staggered entrance animation powered by GreenSock GSAP for headers, card containers, and interactive controls upon kiosk boot.
- **Hardware-Only Tap (Zero Text Input)**:
  - Eliminated manual text input fields entirely to ensure complete voting integrity and prevent spoofing.
  - Automatically captures hardware RFID reader keystrokes or Web NFC tag serial numbers without exposing any editable inputs.
- **Unified Sticky Footer Ballot Dock (`/vote`)**:
  - Fixed-bottom persistent dock with candidate selection summary chips, "Kembali", "Lanjut", "Tinjau Pilihan", and "Vote Sekarang" buttons dynamically updating across ballot steps.
- **Calm, Senior-Friendly Visuals**:
  - Relaxed, gentle ripple waves (4.5s) optimized for older eyes.
  - Floating card hover animation with tactile spring-lift ("sundul") on touch/hover (`.ballot-card-sundul`).
  - Fluid **Nova Green** success transition and gentle **Danger Red** error warning wave.
- **Dynamic Contextual Election Title**:
  - Synchronized dynamically with the official election title configured in Admin System Settings (`/admin/settings`).
- **Raffle Eligibility Control**:
  - Per-voter toggle to include or exclude individual members from doorprize raffle participation.
  - Ineligible voters are blocked from proceeding to `/vote` with a warning notification.
- **Demo Accounts Interactive DataTable**:
  - Searchable, status-filtered DataTable replacing old static card grids for testing and dry runs.
- **Interactive Audio Feedback (Web Audio API)**:
  - Zero-latency, browser-synthesized audio effects for card tap, successful vote, errors, candidate selection, and modal transitions.

### 2. Public Live Count (`/`)
- **Real-Time SSE Streaming**: Live updates broadcast without page refreshes.
- **Interactive 3D UBS Gold Card Showcase**:
  - Three.js vertical badge card loaded with official UBS Card front texture, white PVC back, and responsive touch/cursor drag rotation.
  - Smooth card swipe-out animation and white overlay transition on Start Voting with synchronized audio cue.
- **Slimmed Bar Chart & Candidate Avatars**: Slimmer, modern bars (`barThickness: 22`) powered by Chart.js featuring custom circular candidate portrait avatars, ballot numbers, and candidate names directly on the x-axis.
- **Election Categories & Procedural Guide with Text-to-Speech (TTS)**:
  - Explanatory section explaining Chairman and Supervisory Board roles, with Web Speech API audio narration and audio playback toggles.
- **Candidate Manifesto & Profile Modals**: Click to inspect Vision (*Visi*), Mission (*Misi*), and background.
- **Clean Public Header**: High-contrast, spacious layout with deadline countdown badge, client IP admin quick-link, and instant language switcher (`EN` / `ID`).

### 3. Executive Admin Control Panel (`/admin`)
- **Clean Light/Dark Theme & Unified Header Banners**:
  - Standardized modern interface across all admin views with optional dark/system mode toggle saved in local storage.
  - Every admin section features a unified, responsive card header banner with status chips, bold titles, and quick action bars.
- **Responsive Mobile & Tablet Drawer**:
  - Off-canvas drawer navigation (`z-50`) with backdrop overlay (`z-40`), smooth slide transitions, and instant touch dismissal on mobile and iPad screens.
- **HeadlessUI Operational Status Sync (LIVE / PAUSED / FINISHED)**:
  - Context-aware modal confirmation popups safeguarding status changes with Indonesian & English localized warnings.
  - Synchronizes real-time status badges across top navbar, kiosk booth, and public live stream instantly.
- **Bilingual Election Titles (`election_title_id` & `election_title_en`)**:
  - Independent official title configuration for Indonesian and English interfaces, dynamically rendered via `AppSetting::getElectionTitle()`.
- **Interactive 3D RFID Smart Card Simulator (`/admin/dashboard`)**:
  - Realistic Three.js physical model replicating the official UBS Gold ID badge: "BUDI" nameplate on solid black bar, blue collared shirt portrait, vertical barcode, slot punch, matte black swivel clasp, and royal blue satin lanyard ribbon loop.
  - 360° interactive rotation on X & Y axes with mouse drag, touch controls, and pure white spotless PVC back.
- **ApexCharts Donut Charts & Responsive Candidate Recaps**:
  - Donut charts with thickened slices (`size: 50%`) and responsive column stacking (`lg:grid-cols-12`) preventing chart and tally collisions on tablet/mobile screens.
- **Telemetry Analytics Module (`/admin/analytics`)**:
  - Comprehensive telemetry center: 24-hour voting velocity histogram, department turnout rankings, RFID Mifare authenticity audit, foreign card scan tracking, and downloadable PDF report.
- **Vote Flow & Department Distribution Module (`/admin/vote-flow`)**:
  - **100% Real Database Analytics**: Direct relational SQL joins across ballots, voters, and candidates.
  - **Interactive Apache ECharts Sankey Flow Diagram**: Maps directional vote currents from individual departments to candidates.
  - **Department Vote Distribution & Cross-Tabulation Matrix**: Real-time division rankings, vote share %, candidate solidity scores, and search filter with shortcut key (`/`).
  - **AI Vision Flow Intelligence**: Integrated Google Gemini 2.0 Flash reasoning with bilingual heuristic fallback (EN / ID) for identifying dominant coalitions and swing departments.
- **System Settings Management Panel (`/admin/settings`)**:
  - Real-time debounced autosave engine for bilingual election titles, quorum threshold percentage, Gemini AI model and API keys, voice greeting autoplay, sound effects toggles, and kiosk session timeouts.
- **Candidate Drag-and-Drop Reordering (`/admin/ketua`, `/admin/pengawas`)**:
  - Interactive HTML5 table row drag-and-drop with real-time ballot number recalculation and asynchronous AJAX persistence.
- **Eligible Voters DataTable with Raffle Toggle (`/admin/voters`)**:
  - Standardized table with search filter toolbar, department selection dropdowns, per-voter raffle eligibility toggle switch, and batch operations.
- **Interactive Documentation Hub (`/admin/docs`)**:
  - Comprehensive English-only documentation with interactive flowcharts, tech stack breakdown, API specification, DDL/DML reference, feature matrix, and deployment requirements.
- **Custom Controller-Based PDF & Excel Exports**:
  - Real server-side PDF exports via DomPDF and CSV/Excel downloads for Chairman, Supervisor, Forensic Traceback, Doorprize, and Voter rosters.
- **Progressive Web App (PWA)**:
  - Installable standalone application on Android, iOS, Windows, and Linux via Web App Manifest and Service Worker.
  - Fully responsive on mobile, iPad Mini (768px-834px), and desktop displays.

### 4. Doorprize & Digital Lottery System (`/admin/reports/doorprize` & `/doorprize`)
- **Three.js 3D Animated Lottery Machine**:
  - Golden gift box with red ribbon and bow, rendered in real-time 3D with floating golden particle effects.
  - Three animation states: idle floating rotation → high-speed spin with particle burst → lid-opening winner reveal.
- **Immersive Audio Experience**:
  - Drum roll tension build-up during spinning, cymbal crash on winner reveal, and celebratory harmonic chime.
  - Per-tick synthetic slot machine audio feedback via Web Audio API.
- **Master Rewards DataTable with Image Upload**:
  - Inventories rewards with title, quantity, category, sponsor, and prize image upload with preview.
- **Official Winner Claim Management**:
  - Tracks winner claim status: **Sudah Diterima** (`accepted`), **Ditolak / Gugur** (`rejected`), **Belum Diambil** (`pending`), or **Alasan Lain** (`other`).
  - Icon-based status action buttons and automated `received_at` timestamps.
- **Standalone Cinema Stage View (`/doorprize`)**:
  - Dedicated big-screen projector display with full Three.js 3D scene, winner ticker marquee, and keyboard shortcuts (`Space` to roll, `F` for fullscreen).

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
- **Doorprize Stage**: [http://localhost:8000/doorprize](http://localhost:8000/doorprize)

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
