import * as THREE from 'three';

/**
 * TapVote AI 3D RFID Card & Lanyard Interactive Showcase
 * Replicates the UBS-style Badge with Red Lanyard & Swivel Clasp from contoh.png
 * Features:
 * - Front: Procedural high-resolution canvas with crimson wave, portrait photo, black RIFKY plate, and barcode.
 * - Back: Pure spotless white PVC ("back itu putih polos").
 * - Realistic slot punch cutout, cord loop, swivel buckle clasp, and double-loop crimson satin lanyard.
 * - 360° rotation on X and Y axes with mouse drag, touch controls, and button triggers.
 */
export function initRfid3DCard(containerId, options = {}) {
    const container = document.getElementById(containerId);
    if (!container) return null;

    // Clear any previous instance
    container.innerHTML = '';

    const width = container.clientWidth || 480;
    const height = container.clientHeight || 360;

    // 1. Scene, Camera, Renderer
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(40, width / height, 0.1, 100);
    camera.position.set(0, 0.35, 5.4);

    const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
    renderer.setSize(width, height);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.shadowMap.enabled = true;
    renderer.shadowMap.type = THREE.PCFSoftShadowMap;
    container.appendChild(renderer.domElement);

    // 2. Textures: Procedural Front (from contoh.png) & Pure White Back
    const frontTexture = createProceduralFrontTexture();
    frontTexture.colorSpace = THREE.SRGBColorSpace;

    const backTexture = createPlainWhiteBackTexture();
    backTexture.colorSpace = THREE.SRGBColorSpace;

    // 3. Card Geometry & Group
    const cardGroup = new THREE.Group();
    cardGroup.position.set(0, -0.4, 0);
    scene.add(cardGroup);

    // Vertical Standard ID Badge Dimensions (54mm x 85.6mm)
    const cardWidth = 2.15;
    const cardHeight = 3.42;
    const cardThickness = 0.038;

    // A. Card Core Body (Smooth White PVC Edge)
    const coreGeometry = new THREE.BoxGeometry(cardWidth, cardHeight, cardThickness);
    const coreMaterial = new THREE.MeshStandardMaterial({
        color: 0xffffff,
        metalness: 0.05,
        roughness: 0.3,
    });
    const coreMesh = new THREE.Mesh(coreGeometry, coreMaterial);
    cardGroup.add(coreMesh);

    // B. Front Face (Procedural Design matching contoh.png)
    const frontGeometry = new THREE.PlaneGeometry(cardWidth, cardHeight);
    const frontMaterial = new THREE.MeshStandardMaterial({
        map: frontTexture,
        metalness: 0.1,
        roughness: 0.28,
    });
    const frontMesh = new THREE.Mesh(frontGeometry, frontMaterial);
    frontMesh.position.z = cardThickness / 2 + 0.002;
    cardGroup.add(frontMesh);

    // C. Back Face (Pure Plain White PVC as requested: "back itu putih polos")
    const backGeometry = new THREE.PlaneGeometry(cardWidth, cardHeight);
    const backMaterial = new THREE.MeshStandardMaterial({
        map: backTexture,
        color: 0xffffff,
        metalness: 0.05,
        roughness: 0.25,
    });
    const backMesh = new THREE.Mesh(backGeometry, backMaterial);
    backMesh.position.z = -(cardThickness / 2 + 0.002);
    backMesh.rotation.y = Math.PI;
    cardGroup.add(backMesh);

    // D. Oblong Punch Slot at Top of Card
    const slotGeom = new THREE.BoxGeometry(0.48, 0.12, cardThickness + 0.015);
    const slotMat = new THREE.MeshStandardMaterial({ color: 0x090d16, roughness: 0.95 });
    const slotMesh = new THREE.Mesh(slotGeom, slotMat);
    slotMesh.position.set(0, cardHeight / 2 - 0.18, 0);
    cardGroup.add(slotMesh);

    // E. Black Cord Loop through Slot
    const cordCurve = new THREE.CatmullRomCurve3([
        new THREE.Vector3(-0.06, cardHeight / 2 - 0.18, 0.02),
        new THREE.Vector3(-0.08, cardHeight / 2 + 0.15, 0.02),
        new THREE.Vector3(0, cardHeight / 2 + 0.32, 0),
        new THREE.Vector3(0.08, cardHeight / 2 + 0.15, -0.02),
        new THREE.Vector3(0.06, cardHeight / 2 - 0.18, -0.02)
    ]);
    const cordGeom = new THREE.TubeGeometry(cordCurve, 24, 0.016, 8, false);
    const cordMat = new THREE.MeshStandardMaterial({ color: 0x111827, roughness: 0.7 });
    const cordMesh = new THREE.Mesh(cordGeom, cordMat);
    cardGroup.add(cordMesh);

    // F. Black Buckle Clasp (Hardware connector)
    const claspGroup = new THREE.Group();
    claspGroup.position.set(0, cardHeight / 2 + 0.42, 0);

    const clampGeom = new THREE.BoxGeometry(0.32, 0.38, 0.12);
    const clampMat = new THREE.MeshStandardMaterial({
        color: 0x18181b,
        roughness: 0.4,
        metalness: 0.2,
    });
    const clampMesh = new THREE.Mesh(clampGeom, clampMat);
    claspGroup.add(clampMesh);
    cardGroup.add(claspGroup);

    // G. Shiny Crimson Red Lanyard Ribbon Loop (Double loop matching contoh.png)
    const topY = cardHeight / 2 + 0.58;
    const ribbonCurve = new THREE.CatmullRomCurve3([
        new THREE.Vector3(0, topY, 0),
        new THREE.Vector3(-0.45, topY + 0.7, -0.2),
        new THREE.Vector3(-0.35, topY + 1.45, -0.35),
        new THREE.Vector3(0.15, topY + 1.55, -0.3),
        new THREE.Vector3(0.48, topY + 0.85, -0.15),
        new THREE.Vector3(0, topY, 0)
    ]);
    const ribbonGeom = new THREE.TubeGeometry(ribbonCurve, 64, 0.075, 16, false);
    const ribbonMat = new THREE.MeshStandardMaterial({
        color: 0xb91c1c, // Crimson Red matching the satin ribbon in contoh.png
        roughness: 0.45,
        metalness: 0.25,
    });
    const ribbonMesh = new THREE.Mesh(ribbonGeom, ribbonMat);
    cardGroup.add(ribbonMesh);

    // 4. Lighting Rig
    const ambientLight = new THREE.AmbientLight(0xffffff, 1.6);
    scene.add(ambientLight);

    const dirLightFront = new THREE.DirectionalLight(0xffffff, 2.4);
    dirLightFront.position.set(3, 4, 6);
    scene.add(dirLightFront);

    const dirLightBack = new THREE.DirectionalLight(0xffffff, 2.0);
    dirLightBack.position.set(-3, -2, -5);
    scene.add(dirLightBack);

    const rimLight = new THREE.DirectionalLight(0x60a5fa, 1.2);
    rimLight.position.set(-5, 3, 3);
    scene.add(rimLight);

    const goldGlow = new THREE.PointLight(0xf59e0b, 1.2, 8);
    goldGlow.position.set(1.5, -1, 3);
    scene.add(goldGlow);

    // 5. Interactive Cursor & Touch Drag Controls (360° X & Y Free Rotation)
    let autoRotate = options.autoRotate !== undefined ? options.autoRotate : true;
    let isDragging = false;
    let previousPointerPosition = { x: 0, y: 0 };
    let targetRotationX = 0.08;
    let targetRotationY = -0.25;
    let currentRotationX = 0.08;
    let currentRotationY = -0.25;
    let targetFloatY = 0;

    // Mousemove for subtle parallax tilt when not dragging
    container.addEventListener('mousemove', (e) => {
        if (!isDragging) {
            const rect = container.getBoundingClientRect();
            const mouseX = ((e.clientX - rect.left) / rect.width) * 2 - 1;
            const mouseY = -(((e.clientY - rect.top) / rect.height) * 2 - 1);
            if (!autoRotate) {
                targetRotationY = mouseX * 1.1;
                targetRotationX = -mouseY * 0.7;
            }
        }
    });

    function onPointerDown(clientX, clientY) {
        isDragging = true;
        previousPointerPosition = { x: clientX, y: clientY };
    }

    function onPointerMove(clientX, clientY) {
        if (!isDragging) return;
        const deltaX = clientX - previousPointerPosition.x;
        const deltaY = clientY - previousPointerPosition.y;

        // 360° free rotation on both axes
        targetRotationY += deltaX * 0.015;
        targetRotationX += deltaY * 0.015;

        previousPointerPosition = { x: clientX, y: clientY };
    }

    function onPointerUp() {
        isDragging = false;
    }

    container.addEventListener('mousedown', (e) => onPointerDown(e.clientX, e.clientY));
    window.addEventListener('mousemove', (e) => onPointerMove(e.clientX, e.clientY));
    window.addEventListener('mouseup', onPointerUp);

    container.addEventListener('touchstart', (e) => {
        if (e.touches.length === 1) onPointerDown(e.touches[0].clientX, e.touches[0].clientY);
    }, { passive: true });
    window.addEventListener('touchmove', (e) => {
        if (e.touches.length === 1 && isDragging) onPointerMove(e.touches[0].clientX, e.touches[0].clientY);
    }, { passive: true });
    window.addEventListener('touchend', onPointerUp);

    // 6. Animation Loop
    const clock = new THREE.Clock();
    let animationFrameId = null;

    function animate() {
        animationFrameId = requestAnimationFrame(animate);
        const elapsedTime = clock.getElapsedTime();

        if (autoRotate && !isDragging) {
            targetRotationY += 0.012;
            targetRotationX = Math.sin(elapsedTime * 1.4) * 0.14;
        }

        currentRotationX += (targetRotationX - currentRotationX) * 0.08;
        currentRotationY += (targetRotationY - currentRotationY) * 0.08;

        cardGroup.rotation.x = currentRotationX;
        cardGroup.rotation.y = currentRotationY;

        // Subtle levitation
        targetFloatY = Math.sin(elapsedTime * 2.2) * 0.06;
        cardGroup.position.y = -0.3 + targetFloatY;

        renderer.render(scene, camera);
    }

    animate();

    // 7. Responsive Resize
    function handleResize() {
        const newWidth = container.clientWidth || 480;
        const newHeight = container.clientHeight || 360;
        camera.aspect = newWidth / newHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(newWidth, newHeight);
    }

    window.addEventListener('resize', handleResize);

    // 8. Public Control API
    return {
        toggleAutoRotate: (state) => {
            autoRotate = state !== undefined ? state : !autoRotate;
            return autoRotate;
        },
        flip: () => {
            targetRotationY += Math.PI;
        },
        rotate360X: () => {
            targetRotationX += Math.PI * 2;
        },
        rotate360Y: () => {
            targetRotationY += Math.PI * 2;
        },
        resetAngle: () => {
            targetRotationX = 0.08;
            targetRotationY = -0.25;
            autoRotate = true;
        },
        destroy: () => {
            if (animationFrameId) cancelAnimationFrame(animationFrameId);
            window.removeEventListener('resize', handleResize);
            window.removeEventListener('mousemove', onPointerMove);
            window.removeEventListener('mouseup', onPointerUp);
            renderer.dispose();
            container.innerHTML = '';
        }
    };
}

/**
 * Procedural Front Card Texture based on contoh.png
 * - Red gradient with signature dynamic white swoosh curve
 * - Professional photo on left (gentleman with blue collared shirt)
 * - Solid pitch black box with white "RIFKY" text
 * - Barcode at bottom
 * - Corporate emblem header without "PT UNTUNG BERSAMA SEJAHTERA"
 */
function createProceduralFrontTexture() {
    const canvas = document.createElement('canvas');
    canvas.width = 1000;
    canvas.height = 1580;
    const ctx = canvas.getContext('2d');

    // 1. Base Card Surface
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, 1000, 1580);

    // 2. Top-to-Mid Dynamic Crimson Wave Gradient (Matching contoh.png)
    const redGrad = ctx.createLinearGradient(0, 0, 1000, 1000);
    redGrad.addColorStop(0, '#f43f5e'); // Rose Coral
    redGrad.addColorStop(0.35, '#e11d48'); // Rich Crimson
    redGrad.addColorStop(0.7, '#be123c'); // Deep Ruby
    redGrad.addColorStop(1, '#9f1239');

    ctx.save();
    ctx.beginPath();
    ctx.moveTo(0, 0);
    ctx.lineTo(1000, 0);
    ctx.lineTo(1000, 1100);
    // Smooth swooping curve separating red from bottom light section
    ctx.bezierCurveTo(750, 1180, 450, 950, 0, 1180);
    ctx.closePath();
    ctx.fillStyle = redGrad;
    ctx.fill();
    ctx.restore();

    // 3. Dynamic White Translucent Wave Arc (The distinctive signature curve from contoh.png)
    ctx.save();
    ctx.beginPath();
    ctx.moveTo(1000, 600);
    ctx.bezierCurveTo(800, 750, 550, 920, 350, 1180);
    ctx.lineTo(460, 1180);
    ctx.bezierCurveTo(650, 960, 880, 800, 1000, 720);
    ctx.closePath();
    const swooshGrad = ctx.createLinearGradient(400, 1100, 900, 700);
    swooshGrad.addColorStop(0, 'rgba(255, 255, 255, 0.9)');
    swooshGrad.addColorStop(0.6, 'rgba(255, 255, 255, 0.45)');
    swooshGrad.addColorStop(1, 'rgba(255, 255, 255, 0.1)');
    ctx.fillStyle = swooshGrad;
    ctx.fill();
    ctx.restore();

    // Secondary subtle wave highlight
    ctx.save();
    ctx.beginPath();
    ctx.moveTo(1000, 450);
    ctx.bezierCurveTo(780, 620, 600, 800, 520, 1100);
    ctx.lineTo(580, 1100);
    ctx.bezierCurveTo(660, 850, 820, 680, 1000, 530);
    ctx.closePath();
    ctx.fillStyle = 'rgba(255, 255, 255, 0.25)';
    ctx.fill();
    ctx.restore();

    // 4. Slot Punch Hole Mask Indication (Top Center)
    ctx.fillStyle = '#0f172a';
    ctx.beginPath();
    ctx.roundRect(380, 60, 240, 55, 28);
    ctx.fill();

    // 5. Header Emblem & Typography (Corporate Clean, No PT UBS)
    // Diamond Logo Emblem
    ctx.save();
    ctx.translate(220, 220);
    ctx.strokeStyle = '#1e3a8a'; // Deep Navy
    ctx.lineWidth = 14;
    ctx.beginPath();
    ctx.moveTo(0, -38);
    ctx.lineTo(45, 0);
    ctx.lineTo(0, 45);
    ctx.lineTo(-45, 0);
    ctx.closePath();
    ctx.stroke();

    // Inner diamond accent
    ctx.fillStyle = '#dc2626'; // Vibrant Red
    ctx.beginPath();
    ctx.moveTo(0, -20);
    ctx.lineTo(24, 0);
    ctx.lineTo(0, 24);
    ctx.lineTo(-24, 0);
    ctx.closePath();
    ctx.fill();
    ctx.restore();

    // Title Text: "TAPVOTE" in Royal Navy Blue
    ctx.font = 'italic 900 88px "Plus Jakarta Sans", Impact, sans-serif';
    ctx.fillStyle = '#1e3a8a';
    ctx.textAlign = 'left';
    ctx.fillText('TAPVOTE', 300, 245);

    // Subtitle in Red: "MEMBER SMART PASS • 2026"
    ctx.font = 'bold 26px "Plus Jakarta Sans", sans-serif';
    ctx.fillStyle = '#b91c1c';
    ctx.letterSpacing = '2px';
    ctx.fillText('MEMBER SMART PASS • 2026', 302, 288);

    // 6. Member Photo (Left side, blue shirt like contoh.png)
    const photoX = 90;
    const photoY = 460;
    const photoW = 460;
    const photoH = 620;

    // Photo frame & shadow
    ctx.save();
    ctx.shadowColor = 'rgba(0, 0, 0, 0.25)';
    ctx.shadowBlur = 20;
    ctx.shadowOffsetX = 4;
    ctx.shadowOffsetY = 8;
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(photoX - 6, photoY - 6, photoW + 12, photoH + 12);
    ctx.restore();

    // Studio portrait drawing inside photo bounds
    ctx.save();
    ctx.beginPath();
    ctx.rect(photoX, photoY, photoW, photoH);
    ctx.clip();

    // Studio Background Gradient
    const photoBg = ctx.createLinearGradient(photoX, photoY, photoX + photoW, photoY + photoH);
    photoBg.addColorStop(0, '#f1f5f9');
    photoBg.addColorStop(0.5, '#e2e8f0');
    photoBg.addColorStop(1, '#cbd5e1');
    ctx.fillStyle = photoBg;
    ctx.fillRect(photoX, photoY, photoW, photoH);

    // Light Blue Button-Down Collared Shirt (as in contoh.png)
    ctx.fillStyle = '#60a5fa';
    ctx.beginPath();
    ctx.moveTo(photoX, photoY + photoH);
    ctx.lineTo(photoX, photoY + 410);
    ctx.bezierCurveTo(photoX + 80, photoY + 360, photoX + 160, photoY + 350, photoX + 230, photoY + 350);
    ctx.bezierCurveTo(photoX + 300, photoY + 350, photoX + 380, photoY + 360, photoX + photoW, photoY + 410);
    ctx.lineTo(photoX + photoW, photoY + photoH);
    ctx.closePath();
    ctx.fill();

    // Shirt collar detail
    ctx.fillStyle = '#93c5fd';
    ctx.beginPath();
    ctx.moveTo(photoX + 165, photoY + 355);
    ctx.lineTo(photoX + 230, photoY + 430);
    ctx.lineTo(photoX + 230, photoY + 355);
    ctx.closePath();
    ctx.fill();

    ctx.beginPath();
    ctx.moveTo(photoX + 295, photoY + 355);
    ctx.lineTo(photoX + 230, photoY + 430);
    ctx.lineTo(photoX + 230, photoY + 355);
    ctx.closePath();
    ctx.fill();

    // Placket line & buttons
    ctx.strokeStyle = '#3b82f6';
    ctx.lineWidth = 3;
    ctx.beginPath();
    ctx.moveTo(photoX + 230, photoY + 430);
    ctx.lineTo(photoX + 230, photoY + photoH);
    ctx.stroke();

    ctx.fillStyle = '#ffffff';
    ctx.beginPath();
    ctx.arc(photoX + 230, photoY + 470, 5, 0, Math.PI * 2);
    ctx.arc(photoX + 230, photoY + 520, 5, 0, Math.PI * 2);
    ctx.arc(photoX + 230, photoY + 570, 5, 0, Math.PI * 2);
    ctx.fill();

    // Neck
    ctx.fillStyle = '#fde2ce'; // Smooth warm skin tone
    ctx.beginPath();
    ctx.moveTo(photoX + 195, photoY + 280);
    ctx.lineTo(photoX + 265, photoY + 280);
    ctx.lineTo(photoX + 250, photoY + 375);
    ctx.lineTo(photoX + 210, photoY + 375);
    ctx.closePath();
    ctx.fill();

    // Head / Face (Faceless Silhouette per user instruction: "tanpa wajah ya")
    ctx.fillStyle = '#fde2ce';
    ctx.beginPath();
    ctx.ellipse(photoX + 230, photoY + 235, 78, 102, 0, 0, Math.PI * 2);
    ctx.fill();

    // Subtle jawline contour shadow for modern 3D depth without facial features
    ctx.fillStyle = 'rgba(217, 119, 6, 0.08)';
    ctx.beginPath();
    ctx.ellipse(photoX + 230, photoY + 265, 52, 60, 0, 0, Math.PI);
    ctx.fill();

    // Hair - Modern Professional Cut Contour
    ctx.fillStyle = '#0f172a';
    ctx.beginPath();
    ctx.ellipse(photoX + 230, photoY + 165, 88, 70, 0, Math.PI, Math.PI * 2);
    ctx.bezierCurveTo(photoX + 325, photoY + 210, photoX + 310, photoY + 260, photoX + 295, photoY + 270);
    ctx.lineTo(photoX + 285, photoY + 200);
    ctx.bezierCurveTo(photoX + 250, photoY + 180, photoX + 210, photoY + 180, photoX + 175, photoY + 200);
    ctx.lineTo(photoX + 165, photoY + 270);
    ctx.bezierCurveTo(photoX + 150, photoY + 260, photoX + 135, photoY + 210, photoX + 142, photoY + 165);
    ctx.closePath();
    ctx.fill();

    // Sideburns
    ctx.fillStyle = '#0f172a';
    ctx.fillRect(photoX + 145, photoY + 215, 12, 38);
    ctx.fillRect(photoX + 303, photoY + 215, 12, 38);

    ctx.restore(); // End photo clip

    // 7. Right Side Pitch Black Name Bar (Matching contoh.png)
    const nameBarX = 520;
    const nameBarY = 880;
    const nameBarW = 480;
    const nameBarH = 175;

    ctx.fillStyle = '#09090b'; // Solid rich black
    ctx.fillRect(nameBarX, nameBarY, nameBarW, nameBarH);

    // Name text: "RIFKY" in large, crisp white bold font
    ctx.fillStyle = '#ffffff';
    ctx.font = '900 76px "Plus Jakarta Sans", sans-serif';
    ctx.textAlign = 'left';
    ctx.fillText('RIFKY', nameBarX + 45, nameBarY + 95);

    // Member metadata below name
    ctx.fillStyle = '#94a3b8';
    ctx.font = 'bold 24px "Plus Jakarta Sans", monospace';
    ctx.fillText('018513 • ICT DIV.', nameBarX + 48, nameBarY + 142);

    // 8. Bottom Light Section with Barcode (Exact replicate of contoh.png)
    const barcodeY = 1190;
    ctx.fillStyle = '#ffffff';
    ctx.beginPath();
    ctx.roundRect(80, barcodeY, 840, 270, 24);
    ctx.fill();

    // Barcode border
    ctx.strokeStyle = '#e2e8f0';
    ctx.lineWidth = 2;
    ctx.stroke();

    // Barcode vertical stripes
    ctx.fillStyle = '#000000';
    const barStartX = 130;
    const barEndX = 870;
    const barHeight = 150;
    const barTop = barcodeY + 35;

    for (let x = barStartX; x < barEndX; x += 10) {
        let w = 3;
        const seed = (x * 17 + 3) % 19;
        if (seed < 4) w = 6;
        else if (seed < 8) w = 2;
        else if (seed < 12) w = 8;
        else if (seed < 16) w = 4;
        else w = 3;
        ctx.fillRect(x, barTop, w, barHeight);
    }

    // Barcode number text
    ctx.font = 'bold 28px monospace';
    ctx.fillStyle = '#1e293b';
    ctx.textAlign = 'center';
    ctx.fillText('*018513-0549936289-ICT*', 500, barcodeY + 235);

    // Bottom subtitle
    ctx.font = 'bold 18px "Plus Jakarta Sans", sans-serif';
    ctx.fillStyle = '#94a3b8';
    ctx.fillText('KOPERASI MANDIRI SEJAHTERA • VERIFIED MIFARE ISO 14443A', 500, 1515);

    return new THREE.CanvasTexture(canvas);
}

/**
 * Plain Spotless White PVC Back Texture as requested: "back itu putih polos"
 */
function createPlainWhiteBackTexture() {
    const canvas = document.createElement('canvas');
    canvas.width = 1000;
    canvas.height = 1580;
    const ctx = canvas.getContext('2d');

    // Pure white, spotless gloss PVC surface
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, 1000, 1580);

    // Top slot punch hole outline
    ctx.fillStyle = '#0f172a';
    ctx.beginPath();
    ctx.roundRect(380, 60, 240, 55, 28);
    ctx.fill();

    return new THREE.CanvasTexture(canvas);
}
