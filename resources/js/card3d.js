import * as THREE from 'three';

/**
 * TapVote AI - Interactive 3D Rectangular ID Card Pass with Three.js
 * Features:
 * - Crisp rectangular ID badge geometry with procedural high-res texture (RFID chip, contactless waves, barcode, photo placeholder)
 * - Highly interactive 3D rotation driven by cursor movement across the parent hero card
 * - Tactile spring-physics damping, idle floating levitation, and dynamic specular reflections
 */
export function initHero3DCard(containerId, options = {}) {
    const container = document.getElementById(containerId);
    if (!container) return null;

    // Clear any previous elements
    container.innerHTML = '';

    const width = container.clientWidth || 120;
    const height = container.clientHeight || 150;

    // 1. Scene & Perspective Camera
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(38, width / height, 0.1, 50);
    camera.position.set(0, 0, 5.0);

    // 2. WebGL Renderer with Alpha & Antialias
    const renderer = new THREE.WebGLRenderer({
        antialias: true,
        alpha: true,
        powerPreference: 'high-performance'
    });
    renderer.setSize(width, height);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.toneMapping = THREE.ACESFilmicToneMapping;
    renderer.toneMappingExposure = 1.15;
    container.appendChild(renderer.domElement);

    // 3. Card Textures (Procedural High-Res Canvas)
    const frontTexture = createCardFrontTexture();
    frontTexture.colorSpace = THREE.SRGBColorSpace;

    const backTexture = createCardBackTexture();
    backTexture.colorSpace = THREE.SRGBColorSpace;

    // 4. Card Mesh Assembly (Rectangular Boxy ID Card)
    const cardGroup = new THREE.Group();
    scene.add(cardGroup);

    // Rectangular ID Card Dimensions: 1.8w x 2.7h x 0.05d (Standard ISO 7810 ID-1 proportions)
    const cardW = 1.85;
    const cardH = 2.75;
    const cardThickness = 0.045;

    // A. Card Core Body (Smooth White PVC Edge)
    const coreGeom = new THREE.BoxGeometry(cardW, cardH, cardThickness);
    const coreMat = new THREE.MeshStandardMaterial({
        color: 0xffffff,
        metalness: 0.1,
        roughness: 0.25,
    });
    const coreMesh = new THREE.Mesh(coreGeom, coreMat);
    cardGroup.add(coreMesh);

    // B. Front Face with ID Pass Artwork & Glossy Physical Finish
    const frontGeom = new THREE.PlaneGeometry(cardW, cardH);
    const frontMat = new THREE.MeshPhysicalMaterial({
        map: frontTexture,
        metalness: 0.15,
        roughness: 0.2,
        clearcoat: 1.0,
        clearcoatRoughness: 0.08,
        reflectivity: 0.9,
    });
    const frontMesh = new THREE.Mesh(frontGeom, frontMat);
    frontMesh.position.z = cardThickness / 2 + 0.002;
    cardGroup.add(frontMesh);

    // C. Back Face (Clean White PVC with Magnetic Stripe & Details)
    const backGeom = new THREE.PlaneGeometry(cardW, cardH);
    const backMat = new THREE.MeshPhysicalMaterial({
        map: backTexture,
        metalness: 0.1,
        roughness: 0.25,
        clearcoat: 0.8,
    });
    const backMesh = new THREE.Mesh(backGeom, backMat);
    backMesh.position.z = -(cardThickness / 2 + 0.002);
    backMesh.rotation.y = Math.PI;
    cardGroup.add(backMesh);

    // D. Oblong Punch Slot at Top of Card
    const slotGeom = new THREE.BoxGeometry(0.42, 0.09, cardThickness + 0.01);
    const slotMat = new THREE.MeshStandardMaterial({ color: 0x090d16, roughness: 0.9 });
    const slotMesh = new THREE.Mesh(slotGeom, slotMat);
    slotMesh.position.set(0, cardH / 2 - 0.14, 0);
    cardGroup.add(slotMesh);

    // 5. Lighting Setup (Cyan, Purple & White Glint for Glossy Shimmer)
    const ambientLight = new THREE.AmbientLight(0xffffff, 1.6);
    scene.add(ambientLight);

    const dirFront = new THREE.DirectionalLight(0xffffff, 2.2);
    dirFront.position.set(2, 3, 5);
    scene.add(dirFront);

    const cyanRim = new THREE.PointLight(0x06b6d4, 3.5, 10);
    cyanRim.position.set(2.5, 2.0, 3.0);
    scene.add(cyanRim);

    const purpleFill = new THREE.PointLight(0xa855f7, 3.0, 10);
    purpleFill.position.set(-2.5, -2.0, 2.5);
    scene.add(purpleFill);

    // 6. Interactive Cursor Tracking (Directly Driven by Cursor Movement)
    let isHovered = false;
    let targetRotX = 0.05;
    let targetRotY = -0.15;
    let currentRotX = 0.05;
    let currentRotY = -0.15;
    let targetPosZ = 0;
    let currentPosZ = 0;
    let targetScale = 1.0;
    let currentScale = 1.0;

    const parentCard = container.closest('a') || container;

    function onMouseMove(e) {
        isHovered = true;
        const rect = parentCard.getBoundingClientRect();
        // Calculate normalized cursor position from -1 to 1
        const nx = ((e.clientX - rect.left) / rect.width) * 2 - 1;
        const ny = -(((e.clientY - rect.top) / rect.height) * 2 - 1);

        // Smooth interactive 3D perspective tilt
        targetRotY = nx * 0.75;
        targetRotX = -ny * 0.55;
        targetPosZ = 0.35;
        targetScale = 1.06;
    }

    function onMouseEnter() {
        isHovered = true;
        targetPosZ = 0.35;
        targetScale = 1.06;
    }

    function onMouseLeave() {
        isHovered = false;
        targetRotX = 0.05;
        targetRotY = -0.15;
        targetPosZ = 0;
        targetScale = 1.0;
    }

    parentCard.addEventListener('mousemove', onMouseMove, { passive: true });
    parentCard.addEventListener('mouseenter', onMouseEnter);
    parentCard.addEventListener('mouseleave', onMouseLeave);

    // Touch support for mobile devices
    parentCard.addEventListener('touchmove', (e) => {
        if (e.touches && e.touches[0]) {
            const touch = e.touches[0];
            const rect = parentCard.getBoundingClientRect();
            const nx = ((touch.clientX - rect.left) / rect.width) * 2 - 1;
            const ny = -(((touch.clientY - rect.top) / rect.height) * 2 - 1);
            targetRotY = nx * 0.65;
            targetRotX = -ny * 0.45;
        }
    }, { passive: true });

    // 7. Render Loop with Elastic Spring Physics
    const clock = new THREE.Clock();
    let animId = null;

    function animate() {
        animId = requestAnimationFrame(animate);
        const elapsedTime = clock.getElapsedTime();

        // Idle gentle breathing when not hovering
        let idleFloatY = 0;
        let idleFloatRot = 0;
        if (!isHovered) {
            idleFloatY = Math.sin(elapsedTime * 2.2) * 0.06;
            idleFloatRot = Math.sin(elapsedTime * 1.5) * 0.08;
        }

        // Interpolate rotations smoothly (spring lerp)
        currentRotX += (targetRotX - currentRotX) * 0.09;
        currentRotY += (targetRotY + idleFloatRot - currentRotY) * 0.09;
        currentPosZ += (targetPosZ - currentPosZ) * 0.08;
        currentScale += (targetScale - currentScale) * 0.08;

        cardGroup.rotation.x = currentRotX;
        cardGroup.rotation.y = currentRotY;
        cardGroup.position.y = idleFloatY;
        cardGroup.position.z = currentPosZ;
        cardGroup.scale.set(currentScale, currentScale, currentScale);

        // Move rim light slightly to shimmer along card surface
        cyanRim.position.x = 2.0 + Math.sin(elapsedTime * 1.2) * 0.8;
        cyanRim.position.y = 2.0 + Math.cos(elapsedTime * 1.4) * 0.8;

        renderer.render(scene, camera);
    }

    animate();

    // 8. Responsive Resize
    function handleResize() {
        if (!container) return;
        const newW = container.clientWidth || 120;
        const newH = container.clientHeight || 150;
        if (newW > 0 && newH > 0) {
            camera.aspect = newW / newH;
            camera.updateProjectionMatrix();
            renderer.setSize(newW, newH);
        }
    }

    window.addEventListener('resize', handleResize);

    // 9. Destroy Method
    return {
        destroy: () => {
            if (animId) cancelAnimationFrame(animId);
            window.removeEventListener('resize', handleResize);
            parentCard.removeEventListener('mousemove', onMouseMove);
            parentCard.removeEventListener('mouseenter', onMouseEnter);
            parentCard.removeEventListener('mouseleave', onMouseLeave);
            coreGeom.dispose();
            coreMat.dispose();
            frontGeom.dispose();
            frontMat.dispose();
            backGeom.dispose();
            backMat.dispose();
            slotGeom.dispose();
            slotMat.dispose();
            renderer.dispose();
            container.innerHTML = '';
        }
    };
}

/**
 * Procedural Front ID Card Texture (Boxy Rectangular Pass)
 */
function createCardFrontTexture() {
    const canvas = document.createElement('canvas');
    canvas.width = 740;
    canvas.height = 1100;
    const ctx = canvas.getContext('2d');

    // 1. Dark Gradient Base
    const bg = ctx.createLinearGradient(0, 0, 740, 1100);
    bg.addColorStop(0, '#0f172a'); // Slate 900
    bg.addColorStop(0.4, '#1e1b4b'); // Deep Indigo
    bg.addColorStop(0.7, '#1e293b');
    bg.addColorStop(1, '#090d16');
    ctx.fillStyle = bg;
    ctx.fillRect(0, 0, 740, 1100);

    // 2. Dynamic Cyan & Purple Glowing Wave Ribbons
    ctx.save();
    const waveGrad = ctx.createLinearGradient(0, 200, 740, 600);
    waveGrad.addColorStop(0, 'rgba(6, 182, 212, 0.45)');
    waveGrad.addColorStop(0.5, 'rgba(99, 102, 241, 0.35)');
    waveGrad.addColorStop(1, 'rgba(168, 85, 247, 0.5)');
    ctx.fillStyle = waveGrad;
    ctx.beginPath();
    ctx.moveTo(0, 320);
    ctx.bezierCurveTo(240, 240, 500, 420, 740, 300);
    ctx.lineTo(740, 520);
    ctx.bezierCurveTo(480, 640, 200, 460, 0, 560);
    ctx.closePath();
    ctx.fill();
    ctx.restore();

    // 3. Header: "TAPVOTE" + "SMART VOTER PASS"
    ctx.fillStyle = '#ffffff';
    ctx.font = 'italic 900 52px "Plus Jakarta Sans", sans-serif';
    ctx.fillText('TAPVOTE', 50, 140);

    ctx.font = 'bold 20px "Plus Jakarta Sans", sans-serif';
    ctx.fillStyle = '#38bdf8'; // Sky cyan
    ctx.letterSpacing = '2px';
    ctx.fillText('OFFICIAL VOTER PASS • 2026', 52, 175);

    // 4. Gold RFID Microchip (Square contact pad with internal circuits)
    const chipX = 50;
    const chipY = 220;
    const chipW = 120;
    const chipH = 95;

    // Chip base
    const chipGrad = ctx.createLinearGradient(chipX, chipY, chipX + chipW, chipY + chipH);
    chipGrad.addColorStop(0, '#fde047');
    chipGrad.addColorStop(0.5, '#eab308');
    chipGrad.addColorStop(1, '#ca8a04');
    ctx.fillStyle = chipGrad;
    ctx.beginPath();
    ctx.roundRect(chipX, chipY, chipW, chipH, 12);
    ctx.fill();

    // Chip contact grid lines
    ctx.strokeStyle = '#854d0e';
    ctx.lineWidth = 2.5;
    ctx.beginPath();
    ctx.moveTo(chipX + 38, chipY);
    ctx.lineTo(chipX + 38, chipY + chipH);
    ctx.moveTo(chipX + 82, chipY);
    ctx.lineTo(chipX + 82, chipY + chipH);
    ctx.moveTo(chipX, chipY + 48);
    ctx.lineTo(chipX + chipW, chipY + 48);
    ctx.stroke();

    // 5. Contactless NFC Wave Symbol
    ctx.strokeStyle = '#22d3ee';
    ctx.lineWidth = 4;
    ctx.lineCap = 'round';
    for (let r = 0; r < 3; r++) {
        ctx.beginPath();
        ctx.arc(230, 268, 16 + r * 14, -Math.PI * 0.35, Math.PI * 0.35);
        ctx.stroke();
    }

    // 6. Member Photo Placeholder Frame (Modern Studio Silhouette)
    const photoX = 50;
    const photoY = 360;
    const photoW = 280;
    const photoH = 360;

    // Photo border
    ctx.save();
    ctx.fillStyle = '#1e293b';
    ctx.beginPath();
    ctx.roundRect(photoX, photoY, photoW, photoH, 20);
    ctx.fill();
    ctx.strokeStyle = 'rgba(255, 255, 255, 0.3)';
    ctx.lineWidth = 3;
    ctx.stroke();

    // Silhouette inside photo
    ctx.beginPath();
    ctx.rect(photoX + 2, photoY + 2, photoW - 4, photoH - 4);
    ctx.clip();
    
    // Gradient inside photo
    const pb = ctx.createLinearGradient(photoX, photoY, photoX + photoW, photoY + photoH);
    pb.addColorStop(0, '#334155');
    pb.addColorStop(1, '#0f172a');
    ctx.fillStyle = pb;
    ctx.fillRect(photoX, photoY, photoW, photoH);

    // Head
    ctx.fillStyle = '#94a3b8';
    ctx.beginPath();
    ctx.arc(photoX + photoW / 2, photoY + 130, 52, 0, Math.PI * 2);
    ctx.fill();

    // Shoulders
    ctx.beginPath();
    ctx.ellipse(photoX + photoW / 2, photoY + 310, 110, 120, 0, 0, Math.PI * 2);
    ctx.fill();
    ctx.restore();

    // 7. Member Info Block (Right side of photo)
    ctx.fillStyle = '#ffffff';
    ctx.font = '900 36px "Plus Jakarta Sans", sans-serif';
    ctx.fillText('VERIFIED', 360, 420);
    ctx.fillText('MEMBER', 360, 465);

    ctx.fillStyle = '#38bdf8';
    ctx.font = 'bold 20px "Plus Jakarta Sans", monospace';
    ctx.fillText('ID: 08849-ICT', 360, 520);
    ctx.fillText('SEC: A-01', 360, 555);

    // Verified Stamp Pill
    ctx.fillStyle = 'rgba(16, 185, 129, 0.2)';
    ctx.beginPath();
    ctx.roundRect(360, 585, 190, 44, 12);
    ctx.fill();
    ctx.strokeStyle = '#10b981';
    ctx.lineWidth = 2;
    ctx.stroke();

    ctx.fillStyle = '#34d399';
    ctx.font = '900 18px "Plus Jakarta Sans", sans-serif';
    ctx.fillText('✓ VOTE READY', 380, 614);

    // 8. Barcode at Bottom
    const barY = 780;
    ctx.fillStyle = '#ffffff';
    ctx.beginPath();
    ctx.roundRect(50, barY, 640, 180, 18);
    ctx.fill();

    ctx.fillStyle = '#000000';
    for (let x = 90; x < 650; x += 8) {
        let w = (x * 13 + 5) % 17 < 7 ? 4 : 2;
        ctx.fillRect(x, barY + 25, w, 95);
    }

    ctx.fillStyle = '#1e293b';
    ctx.font = 'bold 22px monospace';
    ctx.textAlign = 'center';
    ctx.fillText('*TAPVOTE-MIFARE-ISO14443A*', 370, barY + 152);

    // 9. Bottom Footer Micro-text
    ctx.fillStyle = '#64748b';
    ctx.font = 'bold 16px "Plus Jakarta Sans", sans-serif';
    ctx.fillText('SECURE COOPERATIVE VOTING ENCLAVE', 370, 1020);

    return new THREE.CanvasTexture(canvas);
}

/**
 * Procedural Back ID Card Texture
 */
function createCardBackTexture() {
    const canvas = document.createElement('canvas');
    canvas.width = 740;
    canvas.height = 1100;
    const ctx = canvas.getContext('2d');

    // Clean white PVC
    ctx.fillStyle = '#f8fafc';
    ctx.fillRect(0, 0, 740, 1100);

    // Magnetic Stripe (Solid Black top band)
    ctx.fillStyle = '#0f172a';
    ctx.fillRect(0, 80, 740, 140);

    // Signature Panel
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(50, 280, 520, 80);
    ctx.strokeStyle = '#cbd5e1';
    ctx.lineWidth = 2;
    ctx.strokeRect(50, 280, 520, 80);

    ctx.fillStyle = '#94a3b8';
    ctx.font = 'italic bold 22px cursive';
    ctx.fillText('Authorized Signature', 70, 330);

    // Security Info Lines
    ctx.fillStyle = '#64748b';
    ctx.font = 'bold 16px "Plus Jakarta Sans", sans-serif';
    ctx.textAlign = 'left';
    ctx.fillText('• This electronic smart pass is non-transferable.', 50, 420);
    ctx.fillText('• Present this badge at the automated touchscreen terminal.', 50, 455);
    ctx.fillText('• Tap card firmly on scanner to open your voting ballot.', 50, 490);
    ctx.fillText('• System will automatically invalidate card once vote is cast.', 50, 525);

    // QR Code / Enclave Seal Placeholder
    ctx.fillStyle = '#e2e8f0';
    ctx.beginPath();
    ctx.roundRect(50, 600, 200, 200, 16);
    ctx.fill();

    ctx.fillStyle = '#0f172a';
    ctx.font = 'bold 20px monospace';
    ctx.fillText('SECURITY SEAL', 70, 710);

    return new THREE.CanvasTexture(canvas);
}
