import Chart from 'chart.js/auto';
import confetti from 'canvas-confetti';
import { DataTable } from 'simple-datatables';
import * as THREE from 'three';
import { initRfid3DCard } from './rfid3d';

window.Chart = Chart;
window.confetti = confetti;
window.DataTable = DataTable;
window.THREE = THREE;
window.initRfid3DCard = initRfid3DCard;

window.initDataTables = function() {
    document.querySelectorAll('.datatable').forEach(table => {
        if (!table.dataset.dataTableInitialized) {
            new DataTable(table, {
                searchable: true,
                fixedHeight: false,
                perPage: 10,
                perPageSelect: [10, 25, 50, 100],
                labels: {
                    placeholder: window.datatableSearchPlaceholder || "Search / Cari...",
                    perPage: "{select} entries per page",
                    noRows: "No records found / Data tidak ditemukan",
                    info: "Showing {start} to {end} of {rows} entries",
                }
            });
            table.dataset.dataTableInitialized = "true";
        }
    });
};

document.addEventListener('DOMContentLoaded', () => {
    window.initDataTables();
});

// Universal Audio Synthesizer via Web Audio API (Zero Latency, High Fidelity, No External Audio Files)
class SoundFX {
    constructor() {
        this.ctx = null;
    }

    init() {
        if (!this.ctx) {
            const AudioContext = window.AudioContext || window.webkitAudioContext;
            if (AudioContext) {
                this.ctx = new AudioContext();
            }
        }
        if (this.ctx && this.ctx.state === 'suspended') {
            this.ctx.resume();
        }
    }

    // 1. RFID Card Tap / Scan Beep
    tap() {
        this.init();
        if (!this.ctx) return;
        const now = this.ctx.currentTime;
        const osc = this.ctx.createOscillator();
        const gain = this.ctx.createGain();

        osc.type = 'sine';
        osc.frequency.setValueAtTime(880, now); // A5
        osc.frequency.exponentialRampToValueAtTime(1320, now + 0.12); // E6

        gain.gain.setValueAtTime(0.25, now);
        gain.gain.exponentialRampToValueAtTime(0.001, now + 0.14);

        osc.connect(gain);
        gain.connect(this.ctx.destination);
        osc.start(now);
        osc.stop(now + 0.15);
    }

    // 1b. 3-Second Warm Welcome Chime on Successful Tap Login (Smooth Boomer Satisfying)
    welcome() {
        this.init();
        if (!this.ctx) return;
        const now = this.ctx.currentTime;
        
        // Lush Warm Welcome Progression (F Major 9 to C Major, 3.0s duration)
        const notes = [
            { freq: 174.61, delay: 0.00, dur: 3.0, type: 'triangle', gain: 0.20 }, // F3 (warm anchor)
            { freq: 261.63, delay: 0.15, dur: 2.8, type: 'sine',     gain: 0.22 }, // C4
            { freq: 329.63, delay: 0.35, dur: 2.6, type: 'triangle', gain: 0.22 }, // E4
            { freq: 392.00, delay: 0.55, dur: 2.4, type: 'sine',     gain: 0.24 }, // G4
            { freq: 523.25, delay: 0.80, dur: 2.2, type: 'triangle', gain: 0.25 }, // C5
            { freq: 659.25, delay: 1.10, dur: 1.9, type: 'sine',     gain: 0.22 }, // E5
            { freq: 783.99, delay: 1.45, dur: 1.5, type: 'sine',     gain: 0.20 }  // G5 (Golden Shimmer)
        ];

        notes.forEach(n => {
            const start = now + n.delay;
            const osc = this.ctx.createOscillator();
            const gain = this.ctx.createGain();

            osc.type = n.type;
            osc.frequency.setValueAtTime(n.freq, start);

            gain.gain.setValueAtTime(0.001, start);
            gain.gain.linearRampToValueAtTime(n.gain, start + 0.08);
            gain.gain.exponentialRampToValueAtTime(0.0001, start + n.dur);

            osc.connect(gain);
            gain.connect(this.ctx.destination);

            osc.start(start);
            osc.stop(start + n.dur + 0.05);
        });
    }

    // 2. 3-Second Opulent Congrats Harmonic Chime (Smooth Boomer Satisfying)
    success() {
        this.init();
        if (!this.ctx) return;
        const now = this.ctx.currentTime;
        
        // C-Major Pentatonic Harmonic Arpeggio with Warm Reverberant Bell Tones (3.0s Duration)
        const notes = [
            { freq: 261.63, delay: 0.00, dur: 2.8, type: 'triangle', gain: 0.22 }, // C4
            { freq: 329.63, delay: 0.25, dur: 2.6, type: 'sine',     gain: 0.20 }, // E4
            { freq: 392.00, delay: 0.50, dur: 2.4, type: 'triangle', gain: 0.22 }, // G4
            { freq: 523.25, delay: 0.75, dur: 2.2, type: 'sine',     gain: 0.24 }, // C5
            { freq: 659.25, delay: 1.05, dur: 1.9, type: 'sine',     gain: 0.22 }, // E5
            { freq: 783.99, delay: 1.35, dur: 1.6, type: 'sine',     gain: 0.20 }, // G5
            { freq: 1046.5, delay: 1.65, dur: 1.35, type: 'triangle', gain: 0.25 }  // C6 (Peak Shimmer)
        ];

        notes.forEach(n => {
            const start = now + n.delay;
            const osc = this.ctx.createOscillator();
            const gain = this.ctx.createGain();

            osc.type = n.type;
            osc.frequency.setValueAtTime(n.freq, start);

            gain.gain.setValueAtTime(0.001, start);
            gain.gain.linearRampToValueAtTime(n.gain, start + 0.06);
            gain.gain.exponentialRampToValueAtTime(0.0001, start + n.dur);

            osc.connect(gain);
            gain.connect(this.ctx.destination);

            osc.start(start);
            osc.stop(start + n.dur + 0.05);
        });
    }

    // 3. Error / Rejected / Distracted Warning
    error() {
        this.init();
        if (!this.ctx) return;
        const now = this.ctx.currentTime;
        const osc = this.ctx.createOscillator();
        const gain = this.ctx.createGain();

        osc.type = 'sawtooth';
        osc.frequency.setValueAtTime(220, now); // A3
        osc.frequency.linearRampToValueAtTime(140, now + 0.28);

        gain.gain.setValueAtTime(0.3, now);
        gain.gain.exponentialRampToValueAtTime(0.001, now + 0.32);

        osc.connect(gain);
        gain.connect(this.ctx.destination);
        osc.start(now);
        osc.stop(now + 0.35);
    }

    // 4. Tactile Candidate Selection Click
    click() {
        this.init();
        if (!this.ctx) return;
        const now = this.ctx.currentTime;
        const osc = this.ctx.createOscillator();
        const gain = this.ctx.createGain();

        osc.type = 'sine';
        osc.frequency.setValueAtTime(600, now);
        osc.frequency.exponentialRampToValueAtTime(300, now + 0.05);

        gain.gain.setValueAtTime(0.15, now);
        gain.gain.exponentialRampToValueAtTime(0.001, now + 0.06);

        osc.connect(gain);
        gain.connect(this.ctx.destination);
        osc.start(now);
        osc.stop(now + 0.07);
    }

    // 5. Modal Open / Pop
    modal() {
        this.init();
        if (!this.ctx) return;
        const now = this.ctx.currentTime;
        const osc = this.ctx.createOscillator();
        const gain = this.ctx.createGain();

        osc.type = 'sine';
        osc.frequency.setValueAtTime(440, now);
        osc.frequency.exponentialRampToValueAtTime(880, now + 0.08);

        gain.gain.setValueAtTime(0.18, now);
        gain.gain.exponentialRampToValueAtTime(0.001, now + 0.1);

        osc.connect(gain);
        gain.connect(this.ctx.destination);
        osc.start(now);
        osc.stop(now + 0.11);
    }

    // 6. Suspense Drum Roll (10 Seconds for Doorprize Draw)
    drumRoll(duration = 10) {
        this.init();
        if (!this.ctx) return;
        this.stopDrumRoll();

        const ctx = this.ctx;
        const bufferSize = ctx.sampleRate * 2;
        const noiseBuffer = ctx.createBuffer(1, bufferSize, ctx.sampleRate);
        const output = noiseBuffer.getChannelData(0);
        for (let i = 0; i < bufferSize; i++) {
            output[i] = Math.random() * 2 - 1;
        }

        this.drumActive = true;
        const startTime = ctx.currentTime;
        const endTime = startTime + duration;

        const triggerHit = () => {
            if (!this.drumActive || ctx.currentTime >= endTime) {
                this.stopDrumRoll();
                return;
            }

            const now = ctx.currentTime;
            const progress = Math.min(1, (now - startTime) / duration);

            // Snare noise
            const noise = ctx.createBufferSource();
            noise.buffer = noiseBuffer;
            const filter = ctx.createBiquadFilter();
            filter.type = 'bandpass';
            filter.frequency.setValueAtTime(700 + progress * 500, now);
            filter.Q.setValueAtTime(2.2, now);

            const noiseGain = ctx.createGain();
            const vol = 0.06 + progress * 0.16 + (Math.random() * 0.03);
            noiseGain.gain.setValueAtTime(vol, now);
            noiseGain.gain.exponentialRampToValueAtTime(0.001, now + 0.04);

            noise.connect(filter);
            filter.connect(noiseGain);
            noiseGain.connect(ctx.destination);
            noise.start(now);
            noise.stop(now + 0.045);

            // Low thump
            const osc = ctx.createOscillator();
            const oscGain = ctx.createGain();
            osc.frequency.setValueAtTime(80 + (Math.random() * 15), now);
            oscGain.gain.setValueAtTime(0.1 + progress * 0.16, now);
            oscGain.gain.exponentialRampToValueAtTime(0.001, now + 0.04);
            osc.connect(oscGain);
            oscGain.connect(ctx.destination);
            osc.start(now);
            osc.stop(now + 0.045);

            const nextDelay = Math.max(26, 52 - (progress * 26));
            this.drumTimer = setTimeout(triggerHit, nextDelay);
        };

        triggerHit();
    }

    stopDrumRoll() {
        this.drumActive = false;
        if (this.drumTimer) {
            clearTimeout(this.drumTimer);
            this.drumTimer = null;
        }
    }

    // 7. Dramatic Cymbal Crash / Reveal Impact
    crash() {
        this.init();
        if (!this.ctx) return;
        const ctx = this.ctx;
        const now = ctx.currentTime;

        const bufferSize = Math.floor(ctx.sampleRate * 1.5);
        const noiseBuffer = ctx.createBuffer(1, bufferSize, ctx.sampleRate);
        const output = noiseBuffer.getChannelData(0);
        for (let i = 0; i < bufferSize; i++) {
            output[i] = Math.random() * 2 - 1;
        }

        const noise = ctx.createBufferSource();
        noise.buffer = noiseBuffer;
        const filter = ctx.createBiquadFilter();
        filter.type = 'highpass';
        filter.frequency.setValueAtTime(3200, now);

        const gain = ctx.createGain();
        gain.gain.setValueAtTime(0.35, now);
        gain.gain.exponentialRampToValueAtTime(0.001, now + 1.2);

        noise.connect(filter);
        filter.connect(gain);
        gain.connect(ctx.destination);
        noise.start(now);
        noise.stop(now + 1.25);

        const osc = ctx.createOscillator();
        const oscGain = ctx.createGain();
        osc.frequency.setValueAtTime(130, now);
        osc.frequency.exponentialRampToValueAtTime(45, now + 0.5);
        oscGain.gain.setValueAtTime(0.35, now);
        oscGain.gain.exponentialRampToValueAtTime(0.001, now + 0.6);
        osc.connect(oscGain);
        oscGain.connect(ctx.destination);
        osc.start(now);
        osc.stop(now + 0.65);
    }
}

window.SoundEffects = new SoundFX();

// Auto-attach subtle click sound on buttons and interactive cards
document.addEventListener('click', function(e) {
    if (e.target.closest('button, .kiosk-card, a[href*="lang"], .touch-target')) {
        window.SoundEffects.click();
    }
}, { passive: true });
