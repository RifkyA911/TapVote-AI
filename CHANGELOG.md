# Changelog

All notable changes to the **TapVote AI** e-voting application will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [1.3.0] - 2026-09-24

### Added
- **Ballot Booth UI/UX Overhaul (`/vote`)**:
  - Interactive "Sundul" (spring-lift) animations on candidate card hover, focus, and selection (`.ballot-card-sundul`, `.card-selected-pop`).
  - High-resolution candidate photo preview lightbox modal (`#image-preview-modal`) with smooth zoom and dismiss controls.
  - Boomer-friendly modal animations (`.boomer-modal-dialog`) featuring generous touch targets ($\ge 48\text{px}$) and high-contrast typography.
  - Elevated floating action dock with clear step-by-step indicators, status highlights, and safe bottom clearance (`pb-44 sm:pb-48`).
- **Interactive DataTables Integration in Admin Panel**:
  - Installed and configured `simple-datatables` across DPT Voters, Audit Logs, Rekapitulasi Ketua, Rekapitulasi Pengawas, and Traceback tables.
  - Client-side real-time search, column sorting, pagination controls, and per-page show list selector (`10, 25, 50, 100 entries`).
- **Comprehensive Excel & PDF Rekapitulasi Exports**:
  - Native Excel/CSV export with UTF-8 BOM encoding for Microsoft Excel compatibility:
    - `/admin/voters/export`: DPT voter roster with RFID UIDs and voting timestamps.
    - `/admin/reports/ketua/export`: Official Chairman election vote rekapitulasi.
    - `/admin/reports/pengawas/export`: Official Supervisory Board election vote rekapitulasi.
    - `/admin/reports/traceback/export`: Forensic ballot trace-back audit log.
  - Dedicated "Cetak / Export PDF" buttons on all admin reports triggering clean, print-optimized document layouts.
- **Admin Collapsible Sidebar & Tablet Responsive Layout**:
  - Added responsive toggle button in navbar for collapsing sidebar on desktop and toggling an off-canvas drawer on tablets/mobiles.
  - Exact height alignment between Top App Bar and Sidebar Brand header (`h-16` / `64px`).
  - Responsive flex layouts across all admin action headers preventing button wrap collisions on tablets.

### Changed
- **Calmed RFID Tap Ripple Animation (`/voter`)**:
  - Relaxed and slowed radar ripple wave from 2.4s to 4.5s with softened opacity, tailored for senior / 40+ year-old voters.
  - Calmed card bounce and fluid Nova green success ring to 3.2s for pleasant, gentle visual feedback.
- **Public Live Count Header Polish (`/`)**:
  - Removed/hidden Admin Panel button from public view to streamline the kiosk-friendly interface.
  - Reorganized header with a clean, spacious 2-column flex layout eliminating space competition.
- **Persistent Internationalization (i18n)**:
  - Synchronized language selection across persistent cookies (`app_locale`) and server sessions.
  - Replaced hardcoded strings across `/vote`, `/`, and `/voter` with standard Laravel `__()` translation tags.

---

## [1.2.0] - 2026-09-24

### Added
- **Internationalization (i18n / Localization)**:
  - English (`en`) configured as default project language.
  - Full Indonesian (`id`) translation support via `lang/en.json` and `lang/id.json`.
  - Session-based locale switcher route (`/lang/{locale}`) and middleware (`SetLocaleMiddleware`).
  - Interactive EN/ID language switcher pill on the navigation header.
- **Universal Sound Effects System (Web Audio API)**:
  - Zero-latency, browser-synthesized audio effects in `resources/js/app.js`:
    - `SoundEffects.tap()`: High-tech RFID card scan chirp.
    - `SoundEffects.success()`: Melodic 2-tone chime for valid card and vote submission.
    - `SoundEffects.error()`: Low warning sound for rejected or duplicate cards.
    - `SoundEffects.click()`: Subtle tactile feedback on candidate selection and button clicks.
    - `SoundEffects.modal()`: Pop chime for dialog/modal openings.
- **AI Election Conclusion & Analytics Feature (`/admin/dashboard`)**:
  - Automated statistical turnout analysis and margin calculation.
  - Dynamic confidence score indicator (e.g. `98.4% Confidence`).
  - Quorum verification status tracking (50% threshold).
  - Frontrunner election projections for Chairman and Supervisory Board.
  - On-demand AI analysis refresh endpoint (`/admin/api/ai-conclusion`).
- **Interactive Candidate Showcase Modal (`/`)**:
  - Modal displaying comprehensive candidate profile, full Vision (Visi), Mission (Misi), track record, and live vote tallies.
- **Advanced Comparative Bar Chart (`/`)**:
  - Replaced donut charts with responsive Chart.js comparative bar chart.
  - Tabbed filters: *All Candidates*, *Chairman Only*, and *Supervisors Only*.
  - Real-time animated updates on SSE events.
- **Enhanced Kiosk Animations (`/voter`)**:
  - Smooth bouncing floating animation on the RFID card target (`animate-smooth-bounce`).
  - Fluid **Nova Green** success transition with expanding wave rings when a card is verified.
  - Distracted **Danger Red** ripple waves and shake feedback on card read errors.
  - Hide/Show toggle button for the demo card simulator to keep kiosk clean.

### Changed
- **Home Page (`/`) Overhaul**:
  - Removed confidential individual voter breakdown cards (unvoted counts) and recent voter identity tables.
  - Focused on public election metrics: participation rate, active status, leading candidates, and comparative bar chart.
- **Admin Theme Overhaul**:
  - Cleaned up all leftover dark theme styling across `/admin/dashboard`, `/admin/ketua`, `/admin/pengawas`, `/admin/voters`, `/admin/reports`, and `/admin/logs`.
  - Unified clean light theme design with high-contrast slate typography and soft ambient backdrops.
- **Tablet Viewport Optimization**:
  - Scaled down oversized fonts for a balanced, comfortable layout on 10-11 inch tablets.

---

## [1.1.0] - 2026-09-24

### Added
- Real-time Live Count streaming via Server-Sent Events (SSE).
- Tablet-optimized responsive kiosk layout for voter booth.
- Mifare RFID 13.56 MHz hardware reader keystroke listener.

### Changed
- Converted primary UI to clean Light Theme.
- Removed text input for RFID on kiosk display to enforce pure card tapping.

---

## [1.0.0] - 2026-09-24

### Added
- Initial release of TapVote AI E-Voting System.
- Dual-ballot architecture (Ketua & Pengawas Koperasi).
- Admin management for candidates, DPT, audit logs, and reports.
