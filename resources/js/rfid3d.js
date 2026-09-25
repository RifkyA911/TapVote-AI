import * as THREE from 'three';

/**
 * TapVote AI 3D RFID Card Interactive Showcase
 * Built with Three.js (ISO/IEC 7810 ID-1 Standard Proportions)
 * Supports 360° X-axis and Y-axis rotation via cursor/touch drag and hover.
 */
export function initRfid3DCard(containerId, options = {}) {
    const container = document.getElementById(containerId);
    if (!container) return null;

    // Remove any previous canvas
    container.innerHTML = '';

    const width = container.clientWidth || 480;
    const height = container.clientHeight || 340;

    // 1. Scene, Camera, Renderer
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(40, width / height, 0.1, 100);
    camera.position.set(0, 0, 4.6);

    const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
    renderer.setSize(width, height);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.shadowMap.enabled = true;
    renderer.shadowMap.type = THREE.PCFSoftShadowMap;
    container.appendChild(renderer.domElement);

    // 2. Generate Canvas Textures (Front & Back)
    const frontTexture = createFrontCardTexture();
    const backTexture = createBackCardTexture();

    // 3. Card Geometry & Group
    const cardGroup = new THREE.Group();
    scene.add(cardGroup);

    // Card Dimensions: Standard ID-1 Ratio (85.6mm x 53.98mm) -> 3.3 x 2.08 x 0.04
    const cardWidth = 3.3;
    const cardHeight = 2.08;
    const cardThickness = 0.04;

    // Card Core Body (White/Silver Matte Plastic Edge)
    const coreGeometry = new THREE.BoxGeometry(cardWidth, cardHeight, cardThickness);
    const coreMaterial = new THREE.MeshStandardMaterial({
        color: 0xe2e8f0,
        metalness: 0.1,
        roughness: 0.4,
    });
    const coreMesh = new THREE.Mesh(coreGeometry, coreMaterial);
    cardGroup.add(coreMesh);

    // Front Face Plane
    const frontGeometry = new THREE.PlaneGeometry(cardWidth, cardHeight);
    const frontMaterial = new THREE.MeshStandardMaterial({
        map: frontTexture,
        metalness: 0.35,
        roughness: 0.25,
    });
    const frontMesh = new THREE.Mesh(frontGeometry, frontMaterial);
    frontMesh.position.z = cardThickness / 2 + 0.002;
    cardGroup.add(frontMesh);

    // Back Face Plane (Rotated 180° around Y)
    const backGeometry = new THREE.PlaneGeometry(cardWidth, cardHeight);
    const backMaterial = new THREE.MeshStandardMaterial({
        map: backTexture,
        metalness: 0.3,
        roughness: 0.3,
    });
    const backMesh = new THREE.Mesh(backGeometry, backMaterial);
    backMesh.position.z = - (cardThickness / 2 + 0.002);
    backMesh.rotation.y = Math.PI;
    cardGroup.add(backMesh);

    // 4. Lighting Rig
    const ambientLight = new THREE.AmbientLight(0xffffff, 1.4);
    scene.add(ambientLight);

    const dirLight1 = new THREE.DirectionalLight(0xffffff, 2.2);
    dirLight1.position.set(4, 5, 5);
    scene.add(dirLight1);

    const cyanRimLight = new THREE.DirectionalLight(0x38bdf8, 1.2);
    cyanRimLight.position.set(-5, -3, 3);
    scene.add(cyanRimLight);

    const goldAccentLight = new THREE.PointLight(0xf59e0b, 1.5, 10);
    goldAccentLight.position.set(2, 2, 2.5);
    scene.add(goldAccentLight);

    // 5. Interactive Cursor & Touch Drag Controls (360° X & Y Axis Rotation)
    let autoRotate = options.autoRotate !== undefined ? options.autoRotate : true;
    let isDragging = false;
    let previousPointerPosition = { x: 0, y: 0 };
    let targetRotationX = 0.15;
    let targetRotationY = -0.3;
    let currentRotationX = 0.15;
    let currentRotationY = -0.3;
    let targetFloatY = 0;

    // Cursor position over container for parallax tilt when not dragging
    container.addEventListener('mousemove', (e) => {
        if (!isDragging) {
            const rect = container.getBoundingClientRect();
            const mouseX = ((e.clientX - rect.left) / rect.width) * 2 - 1;
            const mouseY = -(((e.clientY - rect.top) / rect.height) * 2 - 1);
            if (!autoRotate) {
                targetRotationY = mouseX * 1.2;
                targetRotationX = -mouseY * 0.9;
            }
        }
    });

    // Drag to rotate 360° on X & Y axes
    function onPointerDown(clientX, clientY) {
        isDragging = true;
        previousPointerPosition = { x: clientX, y: clientY };
    }

    function onPointerMove(clientX, clientY) {
        if (!isDragging) return;
        const deltaX = clientX - previousPointerPosition.x;
        const deltaY = clientY - previousPointerPosition.y;

        // 360° Free Rotation on both axes
        targetRotationY += deltaX * 0.015;
        targetRotationX += deltaY * 0.015;

        previousPointerPosition = { x: clientX, y: clientY };
    }

    function onPointerUp() {
        isDragging = false;
    }

    // Mouse events
    container.addEventListener('mousedown', (e) => {
        onPointerDown(e.clientX, e.clientY);
    });
    window.addEventListener('mousemove', (e) => {
        onPointerMove(e.clientX, e.clientY);
    });
    window.addEventListener('mouseup', onPointerUp);

    // Touch events for mobile phones & tablets
    container.addEventListener('touchstart', (e) => {
        if (e.touches.length === 1) {
            onPointerDown(e.touches[0].clientX, e.touches[0].clientY);
        }
    }, { passive: true });
    window.addEventListener('touchmove', (e) => {
        if (e.touches.length === 1 && isDragging) {
            onPointerMove(e.touches[0].clientX, e.touches[0].clientY);
        }
    }, { passive: true });
    window.addEventListener('touchend', onPointerUp);

    // 6. Animation Loop
    let clock = new THREE.Clock();
    let animationFrameId = null;

    function animate() {
        animationFrameId = requestAnimationFrame(animate);

        const elapsedTime = clock.getElapsedTime();

        // Idle Auto-Rotation
        if (autoRotate && !isDragging) {
            targetRotationY += 0.012;
            targetRotationX = Math.sin(elapsedTime * 1.5) * 0.18;
        }

        // Smooth Lerp Damping
        currentRotationX += (targetRotationX - currentRotationX) * 0.08;
        currentRotationY += (targetRotationY - currentRotationY) * 0.08;

        cardGroup.rotation.x = currentRotationX;
        cardGroup.rotation.y = currentRotationY;

        // Subtle floating levitation
        targetFloatY = Math.sin(elapsedTime * 2.2) * 0.08;
        cardGroup.position.y += (targetFloatY - cardGroup.position.y) * 0.1;

        renderer.render(scene, camera);
    }

    animate();

    // 7. Responsive Resize
    function handleResize() {
        const newWidth = container.clientWidth || 480;
        const newHeight = container.clientHeight || 340;
        camera.aspect = newWidth / newHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(newWidth, newHeight);
    }

    window.addEventListener('resize', handleResize);

    // Return controller methods
    return {
        toggleAutoRotate: function() {
            autoRotate = !autoRotate;
            return autoRotate;
        },
        setAutoRotate: function(val) {
            autoRotate = !!val;
        },
        resetAngle: function() {
            targetRotationX = 0.15;
            targetRotationY = -0.3;
        },
        flipCard: function() {
            targetRotationY += Math.PI;
        },
        rotateX360: function() {
            targetRotationX += Math.PI * 2;
        },
        rotateY360: function() {
            targetRotationY += Math.PI * 2;
        },
        destroy: function() {
            if (animationFrameId) cancelAnimationFrame(animationFrameId);
            window.removeEventListener('resize', handleResize);
            window.removeEventListener('mouseup', onPointerUp);
            window.removeEventListener('touchend', onPointerUp);
            renderer.dispose();
            if (container.contains(renderer.domElement)) {
                container.removeChild(renderer.domElement);
            }
        }
    };
}

/**
 * Generate High-Resolution Front Card Texture using HTML5 Canvas (1024x646)
 */
function createFrontCardTexture() {
    const canvas = document.createElement('canvas');
    canvas.width = 1024;
    canvas.height = 646;
    const ctx = canvas.getContext('2d');

    // 1. Deep Slate Gradient Background
    const bgGrad = ctx.createLinearGradient(0, 0, 1024, 646);
    bgGrad.addColorStop(0, '#090d16');
    bgGrad.addColorStop(0.4, '#111827');
    bgGrad.addColorStop(0.75, '#1e1b4b');
    bgGrad.addColorStop(1, '#0f172a');
    ctx.fillStyle = bgGrad;
    ctx.fillRect(0, 0, 1024, 646);

    // 2. Futuristic Geometric Grid Overlay
    ctx.strokeStyle = 'rgba(255, 255, 255, 0.05)';
    ctx.lineWidth = 1.5;
    for (let x = 40; x < 1024; x += 40) {
        ctx.beginPath();
        ctx.moveTo(x, 0);
        ctx.lineTo(x, 646);
        ctx.stroke();
    }
    for (let y = 40; y < 646; y += 40) {
        ctx.beginPath();
        ctx.moveTo(0, y);
        ctx.lineTo(1024, y);
        ctx.stroke();
    }

    // Concentric Wave Ring in background
    ctx.strokeStyle = 'rgba(56, 189, 248, 0.12)';
    ctx.lineWidth = 2;
    ctx.beginPath();
    ctx.arc(880, 180, 160, 0, Math.PI * 2);
    ctx.stroke();
    ctx.beginPath();
    ctx.arc(880, 180, 240, 0, Math.PI * 2);
    ctx.stroke();

    // 3. Top Header: TapVote AI Brand
    // Shield Icon
    ctx.fillStyle = '#2563eb';
    roundRect(ctx, 60, 60, 56, 56, 16);
    ctx.fill();

    ctx.fillStyle = '#ffffff';
    ctx.font = 'bold 30px "Plus Jakarta Sans", sans-serif';
    ctx.fillText('✓', 76, 99);

    ctx.fillStyle = '#ffffff';
    ctx.font = '900 36px "Plus Jakarta Sans", sans-serif';
    ctx.fillText('TapVote AI', 132, 94);

    ctx.fillStyle = '#94a3b8';
    ctx.font = '700 16px "Plus Jakarta Sans", sans-serif';
    ctx.fillText('OFFICIAL VOTER CREDENTIAL', 132, 118);

    // Contactless Wave Symbol (Upper Right)
    drawContactlessIcon(ctx, 880, 90, '#38bdf8');

    // 4. Gold RFID Microchip (EMV Chip)
    const chipX = 70;
    const chipY = 190;
    const chipW = 140;
    const chipH = 110;

    // Gold gradient
    const goldGrad = ctx.createLinearGradient(chipX, chipY, chipX + chipW, chipY + chipH);
    goldGrad.addColorStop(0, '#fef08a');
    goldGrad.addColorStop(0.3, '#f59e0b');
    goldGrad.addColorStop(0.6, '#d97706');
    goldGrad.addColorStop(1, '#b45309');

    ctx.fillStyle = goldGrad;
    roundRect(ctx, chipX, chipY, chipW, chipH, 16);
    ctx.fill();

    // Etched Chip Contact Dividers
    ctx.strokeStyle = '#78350f';
    ctx.lineWidth = 2.5;
    ctx.strokeRect(chipX + 25, chipY, 90, chipH);
    ctx.beginPath();
    ctx.moveTo(chipX, chipY + 36);
    ctx.lineTo(chipX + chipW, chipY + 36);
    ctx.moveTo(chipX, chipY + 74);
    ctx.lineTo(chipX + chipW, chipY + 74);
    ctx.stroke();

    // 5. Holographic Rainbow Shimmer Strip (Right Side)
    const holoGrad = ctx.createLinearGradient(820, 0, 960, 646);
    holoGrad.addColorStop(0, 'rgba(239, 68, 68, 0.25)');
    holoGrad.addColorStop(0.25, 'rgba(234, 179, 8, 0.35)');
    holoGrad.addColorStop(0.5, 'rgba(34, 197, 94, 0.35)');
    holoGrad.addColorStop(0.75, 'rgba(59, 130, 246, 0.35)');
    holoGrad.addColorStop(1, 'rgba(168, 85, 247, 0.25)');
    ctx.fillStyle = holoGrad;
    roundRect(ctx, 820, 240, 140, 200, 24);
    ctx.fill();

    ctx.strokeStyle = 'rgba(255, 255, 255, 0.4)';
    ctx.lineWidth = 2;
    roundRect(ctx, 820, 240, 140, 200, 24);
    ctx.stroke();

    ctx.fillStyle = '#ffffff';
    ctx.font = '900 18px "Plus Jakarta Sans", sans-serif';
    ctx.textAlign = 'center';
    ctx.fillText('SECURITY', 890, 320);
    ctx.fillText('SEAL', 890, 345);
    ctx.font = 'bold 12px sans-serif';
    ctx.fillText('ISO 14443A', 890, 375);
    ctx.textAlign = 'left';

    // 6. Cardholder & NFC Credential Information
    ctx.fillStyle = '#94a3b8';
    ctx.font = '700 15px "Plus Jakarta Sans", sans-serif';
    ctx.fillText('CREDENTIAL CARD ID', 70, 380);

    ctx.fillStyle = '#f8fafc';
    ctx.font = '900 32px "Courier New", monospace';
    ctx.fillText('8842  0912  5501  2026', 70, 420);

    ctx.fillStyle = '#94a3b8';
    ctx.font = '700 14px "Plus Jakarta Sans", sans-serif';
    ctx.fillText('MEMBER IDENTITY', 70, 490);
    ctx.fillStyle = '#ffffff';
    ctx.font = '900 24px "Plus Jakarta Sans", sans-serif';
    ctx.fillText('DPT ANGGOTA SAH', 70, 524);

    ctx.fillStyle = '#94a3b8';
    ctx.font = '700 14px "Plus Jakarta Sans", sans-serif';
    ctx.fillText('CHIP STANDARD', 460, 490);
    ctx.fillStyle = '#38bdf8';
    ctx.font = '900 20px "Plus Jakarta Sans", sans-serif';
    ctx.fillText('MIFARE CLASSIC 1K', 460, 524);

    // Bottom Decorative Bar
    const btmBarGrad = ctx.createLinearGradient(0, 626, 1024, 646);
    btmBarGrad.addColorStop(0, '#2563eb');
    btmBarGrad.addColorStop(0.5, '#4f46e5');
    btmBarGrad.addColorStop(1, '#06b6d4');
    ctx.fillStyle = btmBarGrad;
    ctx.fillRect(0, 630, 1024, 16);

    const texture = new THREE.CanvasTexture(canvas);
    texture.anisotropy = 8;
    return texture;
}

/**
 * Generate High-Resolution Back Card Texture using HTML5 Canvas (1024x646)
 */
function createBackCardTexture() {
    const canvas = document.createElement('canvas');
    canvas.width = 1024;
    canvas.height = 646;
    const ctx = canvas.getContext('2d');

    // 1. Dark Charcoal Background
    ctx.fillStyle = '#0f172a';
    ctx.fillRect(0, 0, 1024, 646);

    // 2. Black Magnetic Stripe (Top)
    ctx.fillStyle = '#020617';
    ctx.fillRect(0, 60, 1024, 110);

    // Fine metallic sheen in stripe
    ctx.fillStyle = 'rgba(255, 255, 255, 0.05)';
    ctx.fillRect(0, 105, 1024, 20);

    // 3. White Signature Strip
    ctx.fillStyle = '#f8fafc';
    ctx.fillRect(60, 220, 650, 75);

    // Guilloche security pattern simulation in signature panel
    ctx.strokeStyle = '#cbd5e1';
    ctx.lineWidth = 1;
    for (let i = 80; i < 680; i += 24) {
        ctx.beginPath();
        ctx.moveTo(i, 220);
        ctx.lineTo(i + 15, 295);
        ctx.stroke();
    }

    ctx.fillStyle = '#64748b';
    ctx.font = 'bold 15px "Courier New", monospace';
    ctx.fillText('CVC / NFC KEY: 894', 730, 265);

    // 4. Barcode Simulation (Lower Left)
    const barX = 60;
    const barY = 340;
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(barX - 10, barY - 10, 360, 100);

    ctx.fillStyle = '#000000';
    for (let x = barX; x < barX + 340; x += (Math.random() > 0.4 ? 6 : 3)) {
        ctx.fillRect(x, barY, Math.random() > 0.5 ? 4 : 2, 70);
    }
    ctx.font = 'bold 12px monospace';
    ctx.fillText('*TAPVOTE-2026-ENCRYPTED*', barX + 30, barY + 85);

    // 5. Encrypted NFC Loop Track Graphic (Right Side)
    ctx.strokeStyle = '#38bdf8';
    ctx.lineWidth = 2.5;
    for (let r = 20; r <= 80; r += 15) {
        ctx.beginPath();
        ctx.arc(840, 390, r, 0, Math.PI * 2);
        ctx.stroke();
    }
    ctx.fillStyle = '#38bdf8';
    ctx.font = 'bold 14px "Plus Jakarta Sans", sans-serif';
    ctx.fillText('NFC ANTENNA', 790, 395);

    // 6. Regulatory & Terms Notice Text
    ctx.fillStyle = '#94a3b8';
    ctx.font = '13px "Plus Jakarta Sans", sans-serif';
    ctx.fillText('Kartu ini adalah hak suara resmi pemilihan Ketua & Pengawas Koperasi.', 60, 485);
    ctx.fillText('Pegang kartu dan tempelkan ke sensor bilik suara atau punggung smartphone.', 60, 510);
    ctx.fillText('Dilindungi enkripsi AES-128 Mifare ISO 14443A. Hak cipta TapVote AI.', 60, 535);

    ctx.fillStyle = '#64748b';
    ctx.font = 'bold 12px monospace';
    ctx.fillText('SN: RFID-MIFARE-88A9-2026 • MADE FOR INDONESIA COOPERATIVE', 60, 590);

    const texture = new THREE.CanvasTexture(canvas);
    texture.anisotropy = 8;
    return texture;
}

// Utility: Canvas Rounded Rectangle
function roundRect(ctx, x, y, width, height, radius) {
    ctx.beginPath();
    ctx.moveTo(x + radius, y);
    ctx.lineTo(x + width - radius, y);
    ctx.quadraticCurveTo(x + width, y, x + width, y + radius);
    ctx.lineTo(x + width, y + height - radius);
    ctx.quadraticCurveTo(x + width, y + height, x + width - radius, y + height);
    ctx.lineTo(x + radius, y + height);
    ctx.quadraticCurveTo(x, y + height, x, y + height - radius);
    ctx.lineTo(x, y + radius);
    ctx.quadraticCurveTo(x, y, x + radius, y);
    ctx.closePath();
}

// Utility: Contactless Signal Waves Icon
function drawContactlessIcon(ctx, x, y, color) {
    ctx.strokeStyle = color;
    ctx.lineWidth = 5;
    ctx.lineCap = 'round';

    for (let i = 1; i <= 3; i++) {
        ctx.beginPath();
        ctx.arc(x, y, i * 16, -Math.PI * 0.35, Math.PI * 0.35);
        ctx.stroke();
    }
}
