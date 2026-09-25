import * as THREE from 'three';

/**
 * TapVote AI 3D RFID Card & Lanyard Interactive Showcase
 * Replicates the UBS Keplek ID Badge with Red Lanyard & Swivel Clasp
 * Powered by Three.js with full 360° rotation on X and Y axes.
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
    // Adjusted camera to view vertical card and lanyard loop
    camera.position.set(0, 0.35, 5.4);

    const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
    renderer.setSize(width, height);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.shadowMap.enabled = true;
    renderer.shadowMap.type = THREE.PCFSoftShadowMap;
    container.appendChild(renderer.domElement);

    // 2. Texture Loading (Front: Reference Image UBS ID Card, Back: Corporate RFID Back)
    const textureLoader = new THREE.TextureLoader();
    const frontTexture = textureLoader.load(
        options.frontImage || '/images/id_card_ref.jpeg',
        () => { renderer.render(scene, camera); },
        undefined,
        () => {
            // Fallback to procedural texture if image fails
            frontMesh.material.map = createFallbackFrontTexture();
            frontMesh.material.needsUpdate = true;
        }
    );
    frontTexture.colorSpace = THREE.SRGBColorSpace;

    const backTexture = createBackCardTexture();
    backTexture.colorSpace = THREE.SRGBColorSpace;

    // 3. Card Geometry & Group
    const cardGroup = new THREE.Group();
    // Center card vertically
    cardGroup.position.set(0, -0.3, 0);
    scene.add(cardGroup);

    // Vertical Standard ID Badge Dimensions (54mm x 85.6mm)
    const cardWidth = 2.15;
    const cardHeight = 3.42;
    const cardThickness = 0.038;

    // A. Card Core Body (Smooth White Plastic Edge)
    const coreGeometry = new THREE.BoxGeometry(cardWidth, cardHeight, cardThickness);
    const coreMaterial = new THREE.MeshStandardMaterial({
        color: 0xf8fafc,
        metalness: 0.1,
        roughness: 0.35,
    });
    const coreMesh = new THREE.Mesh(coreGeometry, coreMaterial);
    cardGroup.add(coreMesh);

    // B. Front Face (Official UBS ID Card Texture)
    const frontGeometry = new THREE.PlaneGeometry(cardWidth, cardHeight);
    const frontMaterial = new THREE.MeshStandardMaterial({
        map: frontTexture,
        metalness: 0.25,
        roughness: 0.28,
    });
    const frontMesh = new THREE.Mesh(frontGeometry, frontMaterial);
    frontMesh.position.z = cardThickness / 2 + 0.002;
    cardGroup.add(frontMesh);

    // C. Back Face (Rotated 180° around Y)
    const backGeometry = new THREE.PlaneGeometry(cardWidth, cardHeight);
    const backMaterial = new THREE.MeshStandardMaterial({
        map: backTexture,
        metalness: 0.2,
        roughness: 0.35,
    });
    const backMesh = new THREE.Mesh(backGeometry, backMaterial);
    backMesh.position.z = -(cardThickness / 2 + 0.002);
    backMesh.rotation.y = Math.PI;
    cardGroup.add(backMesh);

    // D. Transparent Protective Acrylic Sleeve with Top Punch Slot
    const sleeveWidth = cardWidth + 0.12;
    const sleeveHeight = cardHeight + 0.32;
    const sleeveThickness = cardThickness + 0.03;
    const sleeveGeom = new THREE.BoxGeometry(sleeveWidth, sleeveHeight, sleeveThickness);
    const sleeveMat = new THREE.MeshPhysicalMaterial({
        color: 0xffffff,
        transparent: true,
        opacity: 0.22,
        roughness: 0.1,
        transmission: 0.85,
        thickness: 0.05,
        ior: 1.45,
    });
    const sleeveMesh = new THREE.Mesh(sleeveGeom, sleeveMat);
    sleeveMesh.position.y = 0.1;
    cardGroup.add(sleeveMesh);

    // Top Punch Slot (Visual hole cutout on sleeve)
    const slotGeom = new THREE.BoxGeometry(0.42, 0.1, sleeveThickness + 0.01);
    const slotMat = new THREE.MeshStandardMaterial({ color: 0x090d16, roughness: 0.9 });
    const slotMesh = new THREE.Mesh(slotGeom, slotMat);
    slotMesh.position.set(0, cardHeight / 2 + 0.18, 0);
    cardGroup.add(slotMesh);

    // E. Hardware Clasp / Black Plastic Clamp & Swivel Ring
    const claspGroup = new THREE.Group();
    claspGroup.position.set(0, cardHeight / 2 + 0.22, 0);

    // Clamp Body
    const clampGeom = new THREE.BoxGeometry(0.48, 0.26, 0.12);
    const clampMat = new THREE.MeshStandardMaterial({
        color: 0x18181b,
        roughness: 0.35,
        metalness: 0.25,
    });
    const clampMesh = new THREE.Mesh(clampGeom, clampMat);
    clampMesh.position.y = 0.06;
    claspGroup.add(clampMesh);

    // Metallic Swivel Hook / Ring
    const ringGeom = new THREE.TorusGeometry(0.12, 0.026, 16, 32);
    const ringMat = new THREE.MeshStandardMaterial({
        color: 0xd4d4d8,
        metalness: 0.92,
        roughness: 0.18,
    });
    const ringMesh = new THREE.Mesh(ringGeom, ringMat);
    ringMesh.position.y = -0.06;
    claspGroup.add(ringMesh);

    cardGroup.add(claspGroup);

    // F. Red Woven Fabric Lanyard Ribbon Loop (UBS Red Ribbon)
    const topY = cardHeight / 2 + 0.32;
    const ribbonCurve = new THREE.CatmullRomCurve3([
        new THREE.Vector3(0, topY, 0.02),
        new THREE.Vector3(-0.35, topY + 0.65, -0.15),
        new THREE.Vector3(-0.25, topY + 1.35, -0.32),
        new THREE.Vector3(0.25, topY + 1.35, -0.32),
        new THREE.Vector3(0.35, topY + 0.65, -0.15),
        new THREE.Vector3(0, topY, 0.02)
    ]);
    const ribbonGeom = new THREE.TubeGeometry(ribbonCurve, 64, 0.075, 16, false);
    const ribbonMat = new THREE.MeshStandardMaterial({
        color: 0xdc2626, // Vivid Red matching the UBS lanyard
        roughness: 0.65,
        metalness: 0.08,
    });
    const ribbonMesh = new THREE.Mesh(ribbonGeom, ribbonMat);
    cardGroup.add(ribbonMesh);

    // 4. Lighting Rig
    const ambientLight = new THREE.AmbientLight(0xffffff, 1.5);
    scene.add(ambientLight);

    const dirLightFront = new THREE.DirectionalLight(0xffffff, 2.4);
    dirLightFront.position.set(3, 4, 6);
    scene.add(dirLightFront);

    const dirLightBack = new THREE.DirectionalLight(0xffffff, 1.8);
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

    // Controller Methods
    return {
        toggleAutoRotate: function() {
            autoRotate = !autoRotate;
            return autoRotate;
        },
        setAutoRotate: function(val) {
            autoRotate = !!val;
        },
        resetAngle: function() {
            targetRotationX = 0.08;
            targetRotationY = -0.25;
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
 * Generate Vertical Back Card Texture (646x1024)
 */
function createBackCardTexture() {
    const canvas = document.createElement('canvas');
    canvas.width = 646;
    canvas.height = 1024;
    const ctx = canvas.getContext('2d');

    // Deep slate background
    const bgGrad = ctx.createLinearGradient(0, 0, 646, 1024);
    bgGrad.addColorStop(0, '#090d16');
    bgGrad.addColorStop(0.5, '#0f172a');
    bgGrad.addColorStop(1, '#020617');
    ctx.fillStyle = bgGrad;
    ctx.fillRect(0, 0, 646, 1024);

    // Top magnetic stripe
    ctx.fillStyle = '#1e293b';
    ctx.fillRect(0, 80, 646, 120);

    // Golden watermark diamond
    ctx.strokeStyle = 'rgba(217, 119, 6, 0.25)';
    ctx.lineWidth = 4;
    ctx.strokeRect(80, 240, 486, 320);

    // Title & Organization
    ctx.fillStyle = '#f8fafc';
    ctx.font = 'bold 28px Plus Jakarta Sans, sans-serif';
    ctx.textAlign = 'center';
    ctx.fillText('PT UNTUNG BERSAMA SEJAHTERA', 323, 290);

    ctx.font = 'bold 22px Plus Jakarta Sans, sans-serif';
    ctx.fillStyle = '#94a3b8';
    ctx.fillText('KARTU IDENTITAS KARYAWAN & ANGGOTA', 323, 330);

    // RFID Security Specs
    ctx.fillStyle = '#38bdf8';
    ctx.font = 'bold 20px monospace';
    ctx.fillText('RFID MIFARE CLASSIC 1K • ISO 14443A', 323, 390);

    // Signature Panel
    ctx.fillStyle = '#f1f5f9';
    ctx.fillRect(80, 440, 486, 90);
    ctx.fillStyle = '#0f172a';
    ctx.font = 'italic 20px serif';
    ctx.textAlign = 'left';
    ctx.fillText('Authorized Signature • Otorisasi Resmi', 100, 495);

    // Instructions
    ctx.fillStyle = '#94a3b8';
    ctx.font = '15px Plus Jakarta Sans, sans-serif';
    ctx.textAlign = 'center';
    ctx.fillText('1. Kartu ini milik PT Untung Bersama Sejahtera.', 323, 600);
    ctx.fillText('2. Wajib dibawa dan ditunjukkan selama berada di area perusahaan.', 323, 630);
    ctx.fillText('3. Jika menemukan kartu ini, harap hubungi HRD / Security.', 323, 660);

    // Barcode Simulation at bottom
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(100, 740, 446, 140);
    ctx.fillStyle = '#000000';
    for (let x = 120; x < 526; x += 6) {
        const barWidth = ((x % 11 === 0 || x % 7 === 0) ? 4 : 2);
        ctx.fillRect(x, 755, barWidth, 90);
    }
    ctx.fillStyle = '#000000';
    ctx.font = 'bold 18px monospace';
    ctx.textAlign = 'center';
    ctx.fillText('*UBS-00897-TAPVOTE*', 323, 870);

    // Footer Watermark
    ctx.fillStyle = '#475569';
    ctx.font = 'bold 16px Plus Jakarta Sans, sans-serif';
    ctx.fillText('TapVote AI Verified Credential', 323, 950);

    const texture = new THREE.CanvasTexture(canvas);
    return texture;
}

/**
 * Procedural Fallback Texture (if external image fails to load)
 */
function createFallbackFrontTexture() {
    const canvas = document.createElement('canvas');
    canvas.width = 646;
    canvas.height = 1024;
    const ctx = canvas.getContext('2d');

    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, 646, 1024);

    // Red swirl gradient top
    const grad = ctx.createLinearGradient(0, 0, 646, 400);
    grad.addColorStop(0, '#dc2626');
    grad.addColorStop(1, '#991b1b');
    ctx.fillStyle = grad;
    ctx.fillRect(0, 0, 646, 320);

    ctx.fillStyle = '#ffffff';
    ctx.font = 'bold 36px Plus Jakarta Sans, sans-serif';
    ctx.textAlign = 'center';
    ctx.fillText('UBS GOLD', 323, 100);

    // Photo Box Placeholder
    ctx.fillStyle = '#e2e8f0';
    ctx.fillRect(173, 360, 300, 380);

    // Name Banner
    ctx.fillStyle = '#090d16';
    ctx.fillRect(80, 780, 486, 80);
    ctx.fillStyle = '#ffffff';
    ctx.font = 'black 32px Plus Jakarta Sans, sans-serif';
    ctx.fillText('RIFKY', 323, 832);

    const texture = new THREE.CanvasTexture(canvas);
    return texture;
}
