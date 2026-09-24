# Changelog

All notable changes to the **TapVote AI** e-voting application will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

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
