# TapVote AI — Next-Gen E-Voting System & Realtime Election Intelligence

[![Laravel 12](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP 8.3+](https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=flat&logo=php&logoColor=white)](https://www.php.net)
[![Tailwind CSS v4](https://img.shields.io/badge/Tailwind_CSS-v4.0-38B2AC?style=flat&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Model Context Protocol](https://img.shields.io/badge/MCP-Compatible-blueviolet?style=flat)](https://modelcontextprotocol.io)
[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

**TapVote AI** is a state-of-the-art, transparent, and senior/boomer-friendly electronic voting system engineered for institutional and Cooperative General Elections (*Ketua & Pengawas*). It features contactless **RFID Mifare (ISO 14443A)** card tap authentication, tactile tablet voting kiosks, **AI Election Conclusions & Predictive Analytics**, real-time live result streaming via **Server-Sent Events (SSE)**, and a built-in **Model Context Protocol (MCP)** server for AI agent integration.

---

## 🌟 Key Features

### 1. Tablet Kiosk Voting Booth (`/voter` & `/vote`)
- **Card-Only RFID Tap**: Fast contactless voting. Hardware RFID reader keystrokes are automatically captured; zero on-screen text input required.
- **Calm, Senior-Friendly Visuals**:
  - Relaxed, gentle ripple waves (4.5s) optimized for older eyes.
  - Floating card hover animation with tactile spring-lift ("sundul") on touch/hover (`.ballot-card-sundul`).
  - Fluid **Nova Green** success transition and gentle **Danger Red** error warning wave.
- **Candidate Image Zoom-In Lightbox**: Tap candidate photo to preview high-resolution portraits with manifesto overview.
- **Interactive Audio Feedback (Web Audio API)**: Zero-latency, browser-synthesized audio effects for card tap, successful vote, errors, candidate selection, and modal transitions.
- **Dual-Ballot Unified Flow**: Elect 1 Chairman and 1 Supervisor in a single, verified transaction with database-level row locking.
- **Demo Cards Drawer**: Toggleable test account drawer for demonstration and rehearsals.

### 2. Public Live Count Display (`/`)
- **Real-Time SSE Streaming**: Live updates broadcast without page refreshes.
- **Comparative Bar Chart**: Grouped multi-candidate comparative charts powered by Chart.js with tabbed filtering (*All Candidates*, *Chairman Only*, *Supervisors Only*).
- **Candidate Manifesto & Profile Modals**: Click to inspect Vision (*Visi*), Mission (*Misi*), and background.
- **Clean Public Header**: High-contrast, spacious layout with instant language switcher (`EN` / `ID`).

### 3. Admin Control Panel (`/admin`)
- **AI Election Conclusion & Analytical Insights**:
  - Real-time quorum verification ($50\%$ threshold).
  - Margin of victory calculations and winner projections.
  - Statistical confidence score indicator (e.g. `98.4% Confidence`).
  - Commission recommendations with on-demand refresh.
- **DataTables Integration**:
  - Live client-side instant search, column sorting, pagination, and per-page entries selector (`10, 25, 50, 100 entries`).
- **Excel & PDF Rekapitulasi Exports**:
  - Instant Excel (`.csv` with UTF-8 BOM encoding for Microsoft Excel) for DPT Voters, Rekapitulasi Ketua, Rekapitulasi Pengawas, and Forensic Traceback.
  - Dedicated "Cetak / Export PDF" buttons on all report tables.
- **Responsive Collapsible Sidebar**: Matches navbar height (`64px`) and collapses into an off-canvas drawer on mobile and tablet devices.

### 4. Model Context Protocol (MCP) Server
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

## 🧪 Testing

Run automated PHPUnit test suites:
```bash
php artisan test
```

---

## 📄 License

This open-source project is licensed under the [MIT License](LICENSE).
