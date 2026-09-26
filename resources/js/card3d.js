import * as THREE from 'three';

/**
 * TapVote AI - Exact 3D Replica of UBS GOLD ID Card (From ubs card.png)
 * Features:
 * - 1:1 Aspect ratio (768 x 1024 = 3:4) matching ubs card.png
 * - Pure spotless white PVC back surface ("dengan back warna putih kartunya")
 * - Highly responsive 3D tilt tracking cursor movement across the hero card
 * - Specular sheen and satisfying spring physics
 * - Public triggerSatisfyingClickAnimation() for smooth, comforting page transition
 */
export function initHero3DCard(containerId, options = {}) {
    const container = document.getElementById(containerId);
    if (!container) return null;

    container.innerHTML = '';

    const width = container.clientWidth || 120;
    const height = container.clientHeight || 160;

    // 1. Scene & Perspective Camera
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(38, width / height, 0.1, 50);
    camera.position.set(0, 0, 4.8);

    // 2. WebGL Renderer
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

    // 3. Card Textures:
    // Front: Load exact /images/ubs-card.png with canvas fallback
    const textureLoader = new THREE.TextureLoader();
    const frontTexture = textureLoader.load(
        '/images/ubs-card.png',
        () => {
            renderer.render(scene, camera);
        },
        undefined,
        () => {
            // Fallback texture if file cannot load
            const fallback = createUbsFallbackTexture();
            frontMesh.material.map = fallback;
            frontMesh.material.needsUpdate = true;
        }
    );
    frontTexture.colorSpace = THREE.SRGBColorSpace;

    // Back: Pure spotless white PVC texture as instructed ("dengan back warna putih kartunya")
    const backTexture = createPureWhiteBackTexture();
    backTexture.colorSpace = THREE.SRGBColorSpace;

    // 4. Card Mesh Geometry (Exact 3:4 aspect ratio matching 768x1024)
    const cardGroup = new THREE.Group();
    scene.add(cardGroup);

    const cardW = 1.95;
    const cardH = 2.60;
    const cardThickness = 0.04;

    // Core PVC Body (Crisp Pure White Edge)
    const coreGeom = new THREE.BoxGeometry(cardW, cardH, cardThickness);
    const coreMat = new THREE.MeshStandardMaterial({
        color: 0xffffff,
        metalness: 0.05,
        roughness: 0.28,
    });
    const coreMesh = new THREE.Mesh(coreGeom, coreMat);
    cardGroup.add(coreMesh);

    // Front Face (UBS Gold ID Card artwork)
    const frontGeom = new THREE.PlaneGeometry(cardW, cardH);
    const frontMat = new THREE.MeshPhysicalMaterial({
        map: frontTexture,
        metalness: 0.12,
        roughness: 0.22,
        clearcoat: 1.0,
        clearcoatRoughness: 0.08,
        reflectivity: 0.9,
    });
    const frontMesh = new THREE.Mesh(frontGeom, frontMat);
    frontMesh.position.z = cardThickness / 2 + 0.002;
    cardGroup.add(frontMesh);

    // Back Face (Pure Plain White PVC as requested: "dengan back warna putih kartunya")
    const backGeom = new THREE.PlaneGeometry(cardW, cardH);
    const backMat = new THREE.MeshPhysicalMaterial({
        map: backTexture,
        color: 0xffffff,
        metalness: 0.05,
        roughness: 0.25,
        clearcoat: 0.9,
    });
    const backMesh = new THREE.Mesh(backGeom, backMat);
    backMesh.position.z = -(cardThickness / 2 + 0.002);
    backMesh.rotation.y = Math.PI;
    cardGroup.add(backMesh);

    // 5. Lighting Setup
    const ambientLight = new THREE.AmbientLight(0xffffff, 1.8);
    scene.add(ambientLight);

    const dirFront = new THREE.DirectionalLight(0xffffff, 2.2);
    dirFront.position.set(2, 3, 5);
    scene.add(dirFront);

    // Gold specular rim light for the UBS Gold emblem
    const goldRim = new THREE.PointLight(0xf59e0b, 2.8, 10);
    goldRim.position.set(1.8, 2.2, 2.8);
    scene.add(goldRim);

    // Cyan / blue fill light matching the hero card aesthetic
    const cyanFill = new THREE.PointLight(0x38bdf8, 2.5, 10);
    cyanFill.position.set(-2.0, -1.8, 2.5);
    scene.add(cyanFill);

    // 6. Interactive Cursor Tracking (Directly Driven by Cursor Movement)
    let isHovered = false;
    let isClickAnimating = false;
    let targetRotX = 0.05;
    let targetRotY = -0.12;
    let currentRotX = 0.05;
    let currentRotY = -0.12;
    let targetPosZ = 0;
    let currentPosZ = 0;
    let targetScale = 1.0;
    let currentScale = 1.0;

    const parentCard = container.closest('a') || container;

    function onMouseMove(e) {
        if (isClickAnimating) return;
        isHovered = true;
        const rect = parentCard.getBoundingClientRect();
        const nx = ((e.clientX - rect.left) / rect.width) * 2 - 1;
        const ny = -(((e.clientY - rect.top) / rect.height) * 2 - 1);

        targetRotY = nx * 0.72;
        targetRotX = -ny * 0.52;
        targetPosZ = 0.32;
        targetScale = 1.06;
    }

    function onMouseEnter() {
        if (isClickAnimating) return;
        isHovered = true;
        targetPosZ = 0.32;
        targetScale = 1.06;
    }

    function onMouseLeave() {
        if (isClickAnimating) return;
        isHovered = false;
        targetRotX = 0.05;
        targetRotY = -0.12;
        targetPosZ = 0;
        targetScale = 1.0;
    }

    parentCard.addEventListener('mousemove', onMouseMove, { passive: true });
    parentCard.addEventListener('mouseenter', onMouseEnter);
    parentCard.addEventListener('mouseleave', onMouseLeave);

    parentCard.addEventListener('touchmove', (e) => {
        if (isClickAnimating) return;
        if (e.touches && e.touches[0]) {
            const touch = e.touches[0];
            const rect = parentCard.getBoundingClientRect();
            const nx = ((touch.clientX - rect.left) / rect.width) * 2 - 1;
            const ny = -(((touch.clientY - rect.top) / rect.height) * 2 - 1);
            targetRotY = nx * 0.65;
            targetRotX = -ny * 0.45;
        }
    }, { passive: true });

    // 7. Render Loop with Elastic Spring Lerp
    const clock = new THREE.Clock();
    let animId = null;

    function animate() {
        animId = requestAnimationFrame(animate);
        const elapsedTime = clock.getElapsedTime();

        if (!isClickAnimating) {
            let idleFloatY = 0;
            let idleFloatRot = 0;
            if (!isHovered) {
                idleFloatY = Math.sin(elapsedTime * 2.2) * 0.05;
                idleFloatRot = Math.sin(elapsedTime * 1.4) * 0.07;
            }

            currentRotX += (targetRotX - currentRotX) * 0.09;
            currentRotY += (targetRotY + idleFloatRot - currentRotY) * 0.09;
            currentPosZ += (targetPosZ - currentPosZ) * 0.08;
            currentScale += (targetScale - currentScale) * 0.08;

            cardGroup.rotation.x = currentRotX;
            cardGroup.rotation.y = currentRotY;
            cardGroup.position.y = idleFloatY;
            cardGroup.position.z = currentPosZ;
            cardGroup.scale.set(currentScale, currentScale, currentScale);

            // Shimmering specular highlights
            goldRim.position.x = 1.5 + Math.sin(elapsedTime * 1.5) * 0.8;
            goldRim.position.y = 1.8 + Math.cos(elapsedTime * 1.2) * 0.6;
        }

        renderer.render(scene, camera);
    }

    animate();

    // 8. Responsive Resize
    function handleResize() {
        if (!container) return;
        const newW = container.clientWidth || 120;
        const newH = container.clientHeight || 160;
        if (newW > 0 && newH > 0) {
            camera.aspect = newW / newH;
            camera.updateProjectionMatrix();
            renderer.setSize(newW, newH);
        }
    }

    window.addEventListener('resize', handleResize);

    // 9. Satisfying Smooth Boomer Click Animation
    function triggerSatisfyingClickAnimation(onComplete) {
        isClickAnimating = true;
        const startTime = performance.now();
        const duration = 750; // 750ms ultra-smooth boomer pop

        const startRotX = cardGroup.rotation.x;
        const startRotY = cardGroup.rotation.y;
        const startScale = cardGroup.scale.x;
        const startZ = cardGroup.position.z;

        function step(now) {
            const progress = Math.min(1, (now - startTime) / duration);
            // Smooth easeOutCubic
            const t = 1 - Math.pow(1 - progress, 3);

            cardGroup.rotation.y = startRotY + t * Math.PI * 2; // Full majestic 360 spin
            cardGroup.rotation.x = startRotX * (1 - t);
            cardGroup.position.z = startZ + t * 1.5; // Zoom toward camera
            const s = startScale + t * 0.35;
            cardGroup.scale.set(s, s, s);

            goldRim.intensity = 2.8 + t * 4.0; // Glowing light surge
            cyanFill.intensity = 2.5 + t * 3.5;

            if (progress < 1) {
                requestAnimationFrame(step);
            } else {
                if (typeof onComplete === 'function') onComplete();
            }
        }

        requestAnimationFrame(step);
    }

    return {
        triggerSatisfyingClickAnimation,
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
            renderer.dispose();
            container.innerHTML = '';
        }
    };
}

/**
 * Pure Plain White PVC Back Texture as instructed: "dengan back warna putih kartunya"
 */
function createPureWhiteBackTexture() {
    const canvas = document.createElement('canvas');
    canvas.width = 768;
    canvas.height = 1024;
    const ctx = canvas.getContext('2d');

    // Spotless pure white surface
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, 768, 1024);

    return new THREE.CanvasTexture(canvas);
}

/**
 * Fallback Texture accurately representing ubs card.png if offline
 */
function createUbsFallbackTexture() {
    const canvas = document.createElement('canvas');
    canvas.width = 768;
    canvas.height = 1024;
    const ctx = canvas.getContext('2d');

    // Deep blue background
    ctx.fillStyle = '#08254b';
    ctx.fillRect(0, 0, 768, 1024);

    // Subtle dark blue curves
    ctx.fillStyle = '#0d3263';
    ctx.beginPath();
    ctx.moveTo(768, 200);
    ctx.bezierCurveTo(400, 300, 300, 600, 768, 700);
    ctx.fill();

    // Gold UBS Emblem
    ctx.fillStyle = '#d4af37';
    ctx.font = 'bold 44px sans-serif';
    ctx.textAlign = 'center';
    ctx.fillText('UBS GOLD', 384, 180);
    ctx.font = '22px sans-serif';
    ctx.fillText('Trust In Gold', 384, 215);

    // Black name plate
    ctx.fillStyle = '#000000';
    ctx.fillRect(400, 580, 368, 140);
    ctx.fillStyle = '#ffffff';
    ctx.font = 'bold 50px sans-serif';
    ctx.textAlign = 'center';
    ctx.fillText('BUDI', 580, 670);

    // Barcode at bottom
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(150, 810, 468, 120);
    ctx.fillStyle = '#000000';
    for (let x = 165; x < 605; x += 10) {
        ctx.fillRect(x, 825, (x % 3 === 0 ? 5 : 2), 90);
    }

    return new THREE.CanvasTexture(canvas);
}
