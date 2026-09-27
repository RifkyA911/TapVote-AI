import * as THREE from 'three';

/**
 * TapVote AI - Exact 3D Replica of UBS GOLD ID Card (From ubs card.png)
 * Features:
 * - Rounded Square / Rounded Rectangle geometry matching physical PVC card corners
 * - Exact 3:4 aspect ratio (width: 1.95, height: 2.60) matching 768x1024 PNG dimensions
 * - Spotless pure white PVC back surface ("dengan back warna putih kartunya")
 * - Interactive cursor-driven 3D tilt with elastic spring damping
 * - triggerSwipeOut() for rapid, fluid card departure
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

    // 2. WebGL Renderer with Alpha & Antialias
    const renderer = new THREE.WebGLRenderer({
        antialias: true,
        alpha: true,
        powerPreference: 'high-performance'
    });
    renderer.setSize(width, height);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.toneMapping = THREE.ACESFilmicToneMapping;
    renderer.toneMappingExposure = 1.18;
    container.appendChild(renderer.domElement);

    // 3. Card Textures:
    // Front: Load exact /images/ubs-card.png
    const textureLoader = new THREE.TextureLoader();
    const frontTexture = textureLoader.load(
        '/images/ubs-card.png?v=3',
        (tex) => {
            tex.colorSpace = THREE.SRGBColorSpace;
            tex.minFilter = THREE.LinearFilter;
            tex.magFilter = THREE.LinearFilter;
            tex.needsUpdate = true;
            if (frontMat) {
                frontMat.map = tex;
                frontMat.needsUpdate = true;
            }
            renderer.render(scene, camera);
        },
        undefined,
        () => {
            const fallback = createUbsFallbackTexture();
            if (frontMat) {
                frontMat.map = fallback;
                frontMat.needsUpdate = true;
            }
        }
    );
    frontTexture.colorSpace = THREE.SRGBColorSpace;

    // Back: Pure spotless white PVC texture ("dengan back warna putih kartunya")
    const backTexture = createPureWhiteBackTexture();
    backTexture.colorSpace = THREE.SRGBColorSpace;

    // 4. Card Mesh Assembly with Physical Rounded Corners (Rounded Square)
    const cardGroup = new THREE.Group();
    scene.add(cardGroup);

    // Exact Dimensions (matching w 610px x h 988px from ubs card.png, ratio 0.6174)
    const cardW = 1.68;
    const cardH = 2.72;
    const cardCornerRadius = 0.16; // Physical rounded corners matching credit/ID card
    const cardThickness = 0.04;
    const cardBevelThickness = 0.006;

    // A. Core PVC Body with Extruded Rounded Edges
    const coreGeom = createRoundedCoreGeometry(cardW, cardH, cardCornerRadius, cardThickness);
    const coreMat = new THREE.MeshStandardMaterial({
        color: 0xffffff,
        metalness: 0.05,
        roughness: 0.3,
    });
    const coreMesh = new THREE.Mesh(coreGeom, coreMat);
    cardGroup.add(coreMesh);

    // B. Front Face Plate (Vivid UBS Gold Card Texture placed strictly above core bevel)
    const frontGeom = createRoundedPlaneGeometry(cardW, cardH, cardCornerRadius);
    const frontMat = new THREE.MeshBasicMaterial({
        map: frontTexture,
        transparent: false,
    });
    const frontMesh = new THREE.Mesh(frontGeom, frontMat);
    // Bevel reaches cardThickness / 2 + cardBevelThickness = 0.026. Place frontMesh at 0.028 to prevent occlusion!
    frontMesh.position.z = cardThickness / 2 + cardBevelThickness + 0.002;
    cardGroup.add(frontMesh);

    // C. Back Face Plate (Rounded Square Pure Spotless White PVC)
    const backGeom = createRoundedPlaneGeometry(cardW, cardH, cardCornerRadius);
    const backMat = new THREE.MeshStandardMaterial({
        map: backTexture,
        color: 0xffffff,
        metalness: 0.02,
        roughness: 0.25,
    });
    const backMesh = new THREE.Mesh(backGeom, backMat);
    backMesh.position.z = -(cardThickness / 2 + cardBevelThickness + 0.002);
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

    // Cyan / blue fill light matching hero card aesthetic
    const cyanFill = new THREE.PointLight(0x38bdf8, 2.5, 10);
    cyanFill.position.set(-2.0, -1.8, 2.5);
    scene.add(cyanFill);

    // 6. Interactive Cursor Tracking & 360 Flip on Hover
    let isHovered = false;
    let isSwipingOut = false;
    let isSpinning = false;
    let spinAngleY = 0;
    let targetRotX = 0.05;
    let targetRotY = -0.12;
    let currentRotX = 0.05;
    let currentRotY = -0.12;
    let targetPosZ = 0;
    let currentPosZ = 0;
    let targetScale = 1.0;
    let currentScale = 1.0;

    const parentCard = container.closest('a') || container;

    // Smooth 360° Flip Animation so the user can inspect the white PVC back
    function trigger360Flip() {
        if (isSwipingOut || isSpinning) return;
        isSpinning = true;

        if (window.SoundEffects && typeof window.SoundEffects.click === 'function') {
            window.SoundEffects.click();
        }

        const spinObj = { angle: 0 };
        if (window.gsap) {
            window.gsap.to(spinObj, {
                angle: Math.PI * 2,
                duration: 1.25,
                ease: 'power2.inOut',
                onUpdate: () => {
                    spinAngleY = spinObj.angle;
                },
                onComplete: () => {
                    spinAngleY = 0;
                    isSpinning = false;
                }
            });
        } else {
            const startT = performance.now();
            const dur = 1250;
            function stepSpin(now) {
                const p = Math.min(1, (now - startT) / dur);
                const ease = p < 0.5 ? 2 * p * p : 1 - Math.pow(-2 * p + 2, 2) / 2;
                spinAngleY = ease * Math.PI * 2;
                if (p < 1) {
                    requestAnimationFrame(stepSpin);
                } else {
                    spinAngleY = 0;
                    isSpinning = false;
                }
            }
            requestAnimationFrame(stepSpin);
        }
    }

    function onMouseMove(e) {
        if (isSwipingOut) return;
        isHovered = true;
        const rect = parentCard.getBoundingClientRect();
        const nx = ((e.clientX - rect.left) / rect.width) * 2 - 1;
        const ny = -(((e.clientY - rect.top) / rect.height) * 2 - 1);

        targetRotY = nx * 0.65;
        targetRotX = -ny * 0.45;
        targetPosZ = 0.35;
        targetScale = 1.08;
    }

    function onMouseEnter() {
        if (isSwipingOut) return;
        isHovered = true;
        targetPosZ = 0.35;
        targetScale = 1.08;
        trigger360Flip();
    }

    function onMouseLeave() {
        if (isSwipingOut) return;
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
        if (isSwipingOut) return;
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

        if (options.autoRotate) {
            const rotSpeed = options.rotationSpeed || 1.8;
            cardGroup.rotation.y = elapsedTime * rotSpeed;
            cardGroup.rotation.x = Math.sin(elapsedTime * 1.6) * 0.12;
            cardGroup.position.y = Math.sin(elapsedTime * 2.2) * 0.08;
            cardGroup.position.z = 0.15;
            goldRim.position.x = 1.5 + Math.sin(elapsedTime * 1.5) * 0.8;
            goldRim.position.y = 1.8 + Math.cos(elapsedTime * 1.2) * 0.6;
        } else if (!isSwipingOut) {
            let idleFloatY = 0;
            let idleFloatRot = 0;
            if (!isHovered && !isSpinning) {
                idleFloatY = Math.sin(elapsedTime * 2.2) * 0.05;
                idleFloatRot = Math.sin(elapsedTime * 1.4) * 0.07;
            }

            currentRotX += (targetRotX - currentRotX) * 0.09;
            currentRotY += (targetRotY + idleFloatRot - currentRotY) * 0.09;
            currentPosZ += (targetPosZ - currentPosZ) * 0.08;
            currentScale += (targetScale - currentScale) * 0.08;

            cardGroup.rotation.x = currentRotX;
            cardGroup.rotation.y = currentRotY + spinAngleY;
            cardGroup.position.y = idleFloatY;
            cardGroup.position.z = currentPosZ;
            cardGroup.scale.set(currentScale, currentScale, currentScale);

            // Shimmering light
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

    // 9. Satisfying 2-Second Swipe Out Animation on Click
    function triggerSwipeOut(duration = 2000, onComplete) {
        if (typeof duration === 'function') {
            onComplete = duration;
            duration = 2000;
        }
        isSwipingOut = true;
        const startTime = performance.now();
        const startX = cardGroup.position.x;
        const startRotY = cardGroup.rotation.y;
        const startRotZ = cardGroup.rotation.z;

        function step(now) {
            const progress = Math.min(1, (now - startTime) / duration);
            const t = Math.pow(progress, 1.8); // Smooth fluid glide

            cardGroup.position.x = startX + t * 1.5; // Controlled glide, never clipped by canvas edge
            cardGroup.position.z = currentPosZ + t * 0.8;
            cardGroup.rotation.y = startRotY + t * 0.9;
            cardGroup.rotation.z = startRotZ - t * 0.2;
            const s = Math.max(0.05, 1 - t * 0.4);
            cardGroup.scale.set(s, s, s);

            if (progress < 1) {
                requestAnimationFrame(step);
            } else {
                if (typeof onComplete === 'function') onComplete();
            }
        }

        requestAnimationFrame(step);
    }

    return {
        triggerSwipeOut,
        trigger360Flip,
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
 * Creates 2D Plane Geometry with Smooth Rounded Corners and Normalized UVs [0, 1]
 */
function createRoundedPlaneGeometry(width, height, radius) {
    const shape = new THREE.Shape();
    const x = -width / 2;
    const y = -height / 2;
    const w = width;
    const h = height;
    const r = Math.min(radius, w / 2, h / 2);

    shape.moveTo(x + r, y);
    shape.lineTo(x + w - r, y);
    shape.quadraticCurveTo(x + w, y, x + w, y + r);
    shape.lineTo(x + w, y + h - r);
    shape.quadraticCurveTo(x + w, y + h, x + w - r, y + h);
    shape.lineTo(x + r, y + h);
    shape.quadraticCurveTo(x, y + h, x, y + h - r);
    shape.lineTo(x, y + r);
    shape.quadraticCurveTo(x, y, x + r, y);

    const geom = new THREE.ShapeGeometry(shape, 32);

    // Compute accurate UV coordinates from [0, 0] to [1, 1]
    const pos = geom.attributes.position;
    const uvs = new Float32Array(pos.count * 2);
    for (let i = 0; i < pos.count; i++) {
        uvs[i * 2] = (pos.getX(i) + w / 2) / w;
        uvs[i * 2 + 1] = (pos.getY(i) + h / 2) / h;
    }
    geom.setAttribute('uv', new THREE.BufferAttribute(uvs, 2));
    geom.computeVertexNormals();
    return geom;
}

/**
 * Creates Extruded 3D Core Body with Smooth Rounded Corners and Bevel
 */
function createRoundedCoreGeometry(width, height, radius, depth) {
    const shape = new THREE.Shape();
    const x = -width / 2;
    const y = -height / 2;
    const w = width;
    const h = height;
    const r = Math.min(radius, w / 2, h / 2);

    shape.moveTo(x + r, y);
    shape.lineTo(x + w - r, y);
    shape.quadraticCurveTo(x + w, y, x + w, y + r);
    shape.lineTo(x + w, y + h - r);
    shape.quadraticCurveTo(x + w, y + h, x + w - r, y + h);
    shape.lineTo(x + r, y + h);
    shape.quadraticCurveTo(x, y + h, x, y + h - r);
    shape.lineTo(x, y + r);
    shape.quadraticCurveTo(x, y, x + r, y);

    const geom = new THREE.ExtrudeGeometry(shape, {
        depth: depth,
        bevelEnabled: true,
        bevelSegments: 3,
        bevelSize: 0.006,
        bevelThickness: 0.006,
        steps: 1
    });
    geom.center();
    geom.computeVertexNormals();
    return geom;
}

/**
 * Pure Spotless White PVC Back Texture as instructed: "dengan back warna putih kartunya"
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

    ctx.fillStyle = '#08254b';
    ctx.fillRect(0, 0, 768, 1024);

    ctx.fillStyle = '#0d3263';
    ctx.beginPath();
    ctx.moveTo(768, 200);
    ctx.bezierCurveTo(400, 300, 300, 600, 768, 700);
    ctx.fill();

    ctx.fillStyle = '#d4af37';
    ctx.font = 'bold 44px sans-serif';
    ctx.textAlign = 'center';
    ctx.fillText('UBS GOLD', 384, 180);
    ctx.font = '22px sans-serif';
    ctx.fillText('Trust In Gold', 384, 215);

    ctx.fillStyle = '#000000';
    ctx.fillRect(400, 580, 368, 140);
    ctx.fillStyle = '#ffffff';
    ctx.font = 'bold 50px sans-serif';
    ctx.textAlign = 'center';
    ctx.fillText('BUDI', 580, 670);

    ctx.fillStyle = '#ffffff';
    ctx.fillRect(150, 810, 468, 120);
    ctx.fillStyle = '#000000';
    for (let x = 165; x < 605; x += 10) {
        ctx.fillRect(x, 825, (x % 3 === 0 ? 5 : 2), 90);
    }

    return new THREE.CanvasTexture(canvas);
}
