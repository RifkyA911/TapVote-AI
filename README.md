# TapVote AI — Cooperative E-Voting System (RFID & Realtime SSE)

**TapVote AI** is a modern, transparent, and accessible electronic voting system designed specifically for Cooperative General Elections (Chairman and Supervisory Board). The system features contactless **RFID Mifare (ISO 14443A)** card authentication, tablet-friendly kiosk voting booths, an **AI Election Conclusion & Analytics Engine**, and real-time live result streaming via **Server-Sent Events (SSE)**.

---

## 🌟 Key Features

### 1. Tablet Kiosk Voting Booth (`/voter`)
- **Card-Only RFID Tap**: Members simply tap their RFID card on the USB reader scanner. No manual text input on screen.
- **Fluid Kiosk Animations**:
  - Smooth bouncing floating card animation (`animate-smooth-bounce`).
  - **Nova Green** fluid expanding wave on successful card verification.
  - **Danger Red** distracted ripple waves and shake animation on card error or duplicate vote.
- **Universal Sound Effects (Web Audio API)**: Zero-latency audio cues for card tap, successful vote, error warning, candidate selection, and modal popup.
- **Dual Ballot in One Flow**: Simultaneously elect 1 Chairman and 1 Supervisor with atomic transactions and row-level locking.
- **Demo Accounts Toggle**: A collapsible drawer for test cards that can be shown or hidden with a single click.
- **Internationalization (i18n)**: Default English interface with one-click instant toggle to Indonesian (`EN` / `ID`).

### 2. Public Live Count Display (`/`)
- **Real-Time SSE Streaming**: Live updates streamed directly from server without page reloads.
- **Advanced Comparative Bar Chart**: Grouped multi-candidate comparison powered by Chart.js with tabbed filters (*All Candidates*, *Chairman Only*, *Supervisors Only*).
- **Candidate Manifesto & Profile Modal**: Clickable detail modals displaying full bio, track record, Vision (Visi), Mission (Misi), and vote tallies.
- **Public Election Summary**: Clean overview showing voter participation percentage, verified ballots count, and frontrunners. Confidential voter rosters are reserved strictly for admins.

### 3. Admin Control Panel (`/admin`)
- **AI Election Conclusion & Analytical Insights**:
  - Automated analysis of voting margins and participation rates.
  - Dynamic statistical confidence score (e.g. `98.4% Confidence`).
  - Quorum verification status tracking (50% threshold).
  - Frontrunner election projections for Chairman and Supervisory Board.
  - On-demand refresh endpoint (`/admin/api/ai-conclusion`).
- **Clean Light Theme**: Fully unified modern light theme across all admin pages, tables, reports, and audit logs.
- **Candidate Management**: Full CRUD for Chairman and Supervisor candidates.
- **Voter Management (DPT)**: Bulk Excel/CSV import, template download, manual voter registration, and audit trace-back.
- **Doorprize Lottery Module**: Automated random raffle draw for members who have cast their votes.

---

## 💻 Tech Stack & Requirements

- **PHP**: `>= 8.3.0` (`pdo_mysql`, `mbstring`, `openssl`, `bcmath`, `curl`)
- **Framework**: Laravel 12 (Tailwind CSS v4 & Vite)
- **Database**: MySQL `>= 8.0` / MariaDB
- **Node.js**: `>= 18.x` & NPM
- **Charts & Audio**: Chart.js, Canvas-Confetti, Web Audio API
- **Hardware**: USB RFID Mifare Reader 13.56 MHz (ISO 14443A)

---

## 🚀 Installation & Setup

### 1. Clone & Enter Directory
```bash
git clone <repository-url> TapVote-AI
cd TapVote-AI
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Configure Environment
```bash
copy .env.example .env
php artisan key:generate
```

Configure your `.env` database settings:
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

### 4. Database Migration & Seed
Make sure MySQL is running (e.g. in Laragon / XAMPP):
```bash
php artisan migrate --seed
```

### 5. Build Assets
```bash
npm run build
```

### 6. Run Application
```bash
php artisan serve
```

Access the application in your browser:
- **Public Live Count**: `http://localhost:8000/`
- **Kiosk Voting Booth**: `http://localhost:8000/voter`
- **Admin Panel**: `http://localhost:8000/admin/login`

---

## 🔑 Default Credentials

### Administrator
- **URL**: `/admin/login`
- **Email**: `admin@tapvote.ai`
- **Password**: `admin123`

### Sample Test Voters (DPT)
| NIK | Full Name | Department | RFID UID |
| :--- | :--- | :--- | :--- |
| `2024001` | Ahmad Subarjo | Produksi | `E2806894` |
| `2024002` | Siti Aminah | Keuangan | `A1B2C3D4` |
| `2024003` | Budi Santoso | Logistik | `98765432` |
| `2024004` | Ratna Dewi | HRD | `11223344` |
| `2024005` | Joko Wibowo | IT & Operasional | `55667788` |

---

## 📄 License
Copyright © 2026 TapVote AI. Developed for transparent, accessible digital cooperative governance.
