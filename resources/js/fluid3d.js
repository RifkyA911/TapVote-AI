import * as THREE from 'three';

/**
 * TapVote AI - Satisfying 3D Fluid Liquid Orb with Three.js
 * Features:
 * - Real-time procedural multi-harmonic vertex wave deformation (molten fluid effect)
 * - Refractive iridescent physical material reflecting purple, blue, and cyan light
 * - Floating glowing orbital rings and micro-particle drift
 * - Mouse parallax tilt and hover speed acceleration
 */
export function initHero3DFluid(containerId, options = {}) {
    const container = document.getElementById(containerId);
    if (!container) return null;

    // Clear previous elements
    container.innerHTML = '';

    const width = container.clientWidth || 112;
    const height = container.clientHeight || 112;

    // 1. Scene & Camera
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(45, width / height, 0.1, 50);
    camera.position.set(0, 0, 4.4);

    // 2. Renderer with transparent background & antialiasing
    const renderer = new THREE.WebGLRenderer({
        antialias: true,
        alpha: true,
        powerPreference: 'high-performance'
    });
    renderer.setSize(width, height);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.toneMapping = THREE.ACESFilmicToneMapping;
    renderer.toneMappingExposure = 1.1;
    container.appendChild(renderer.domElement);

    // 3. Fluid Morphing Mesh Geometry
    const geometry = new THREE.IcosahedronGeometry(1.3, 26);
    const posAttr = geometry.attributes.position;
    const originalPositions = new Float32Array(posAttr.array);

    // 4. Iridescent Glass/Liquid Material
    const material = new THREE.MeshPhysicalMaterial({
        color: new THREE.Color(0x38bdf8), // Cyan-blue base
        emissive: new THREE.Color(0x4338ca), // Deep indigo/violet glow
        emissiveIntensity: 0.38,
        roughness: 0.12,
        metalness: 0.18,
        clearcoat: 1.0,
        clearcoatRoughness: 0.08,
        transmission: 0.28,
        ior: 1.42,
        reflectivity: 0.9,
    });

    const fluidMesh = new THREE.Mesh(geometry, material);
    scene.add(fluidMesh);

    // 5. Glowing Orbital Rings (Purple & Cyan Accents)
    const ring1Geom = new THREE.TorusGeometry(1.75, 0.032, 16, 64);
    const ring1Mat = new THREE.MeshBasicMaterial({
        color: 0x22d3ee, // Bright Cyan
        transparent: true,
        opacity: 0.7,
    });
    const ring1 = new THREE.Mesh(ring1Geom, ring1Mat);
    ring1.rotation.x = Math.PI * 0.35;
    scene.add(ring1);

    const ring2Geom = new THREE.TorusGeometry(1.95, 0.024, 16, 64);
    const ring2Mat = new THREE.MeshBasicMaterial({
        color: 0xc084fc, // Bright Violet
        transparent: true,
        opacity: 0.6,
    });
    const ring2 = new THREE.Mesh(ring2Geom, ring2Mat);
    ring2.rotation.y = Math.PI * 0.45;
    scene.add(ring2);

    // 6. Floating Ambient Micro-Particles
    const particleCount = 20;
    const particlePositions = new Float32Array(particleCount * 3);
    for (let i = 0; i < particleCount * 3; i += 3) {
        particlePositions[i] = (Math.random() - 0.5) * 3.8;
        particlePositions[i + 1] = (Math.random() - 0.5) * 3.8;
        particlePositions[i + 2] = (Math.random() - 0.5) * 2.5;
    }
    const particleGeom = new THREE.BufferGeometry();
    particleGeom.setAttribute('position', new THREE.BufferAttribute(particlePositions, 3));
    const particleMat = new THREE.PointsMaterial({
        size: 0.08,
        color: 0xa5f3fc,
        transparent: true,
        opacity: 0.75,
    });
    const particles = new THREE.Points(particleGeom, particleMat);
    scene.add(particles);

    // 7. Lighting System (Cyan & Purple Highlights)
    const ambientLight = new THREE.AmbientLight(0xffffff, 1.4);
    scene.add(ambientLight);

    const cyanLight = new THREE.PointLight(0x06b6d4, 3.8, 12);
    cyanLight.position.set(2.5, 2.0, 3.0);
    scene.add(cyanLight);

    const purpleLight = new THREE.PointLight(0xa855f7, 3.5, 12);
    purpleLight.position.set(-2.5, -1.8, 2.5);
    scene.add(purpleLight);

    const dirLight = new THREE.DirectionalLight(0xffffff, 1.2);
    dirLight.position.set(0, 4, 3);
    scene.add(dirLight);

    // 8. Animation & Interactive Physics
    const clock = new THREE.Clock();
    let animId = null;
    let targetSpeed = 1.0;
    let currentSpeed = 1.0;
    let targetTiltX = 0;
    let targetTiltY = 0;
    let currentTiltX = 0;
    let currentTiltY = 0;

    // Hover listeners on the parent card or container
    const parentCard = container.closest('a') || container;
    
    function onMouseEnter() {
        targetSpeed = 1.8;
    }

    function onMouseLeave() {
        targetSpeed = 1.0;
        targetTiltX = 0;
        targetTiltY = 0;
    }

    function onMouseMove(e) {
        const rect = parentCard.getBoundingClientRect();
        const nx = ((e.clientX - rect.left) / rect.width) * 2 - 1;
        const ny = -(((e.clientY - rect.top) / rect.height) * 2 - 1);
        targetTiltY = nx * 0.5;
        targetTiltX = -ny * 0.4;
    }

    parentCard.addEventListener('mouseenter', onMouseEnter);
    parentCard.addEventListener('mouseleave', onMouseLeave);
    parentCard.addEventListener('mousemove', onMouseMove, { passive: true });

    // Render loop
    function animate() {
        animId = requestAnimationFrame(animate);
        const delta = clock.getDelta();
        const elapsedTime = clock.getElapsedTime();

        // Speed interpolation
        currentSpeed += (targetSpeed - currentSpeed) * 0.06;
        const flowTime = elapsedTime * currentSpeed;

        // Orbit lights
        cyanLight.position.x = Math.sin(flowTime * 0.9) * 2.8;
        cyanLight.position.y = Math.cos(flowTime * 0.7) * 2.8;
        purpleLight.position.x = -Math.sin(flowTime * 0.8) * 2.8;
        purpleLight.position.y = -Math.cos(flowTime * 0.6) * 2.8;

        // Multi-frequency harmonic wave deformation for organic molten fluid feel
        const positions = posAttr.array;
        for (let i = 0; i < positions.length; i += 3) {
            const ox = originalPositions[i];
            const oy = originalPositions[i + 1];
            const oz = originalPositions[i + 2];

            const len = Math.sqrt(ox * ox + oy * oy + oz * oz);
            if (len > 0.001) {
                const nx = ox / len;
                const ny = oy / len;
                const nz = oz / len;

                const wave1 = Math.sin(nx * 3.4 + flowTime * 1.6) * 0.14;
                const wave2 = Math.cos(ny * 4.0 - flowTime * 1.3) * 0.12;
                const wave3 = Math.sin(nz * 2.8 + flowTime * 1.8) * 0.10;
                const wave4 = Math.sin((nx + ny + nz) * 2.2 + flowTime * 1.1) * 0.07;

                const displacement = 1.0 + wave1 + wave2 + wave3 + wave4;
                positions[i] = ox * displacement;
                positions[i + 1] = oy * displacement;
                positions[i + 2] = oz * displacement;
            }
        }
        posAttr.needsUpdate = true;
        geometry.computeVertexNormals();

        // Smooth tilt damping (spring feel)
        currentTiltX += (targetTiltX - currentTiltX) * 0.08;
        currentTiltY += (targetTiltY - currentTiltY) * 0.08;

        // Rotation & motion
        fluidMesh.rotation.y = flowTime * 0.35 + currentTiltY;
        fluidMesh.rotation.x = flowTime * 0.22 + currentTiltX;

        ring1.rotation.z = flowTime * 0.45;
        ring1.rotation.x = Math.PI * 0.35 + Math.sin(flowTime * 0.6) * 0.15 + currentTiltX * 0.5;

        ring2.rotation.z = -flowTime * 0.35;
        ring2.rotation.y = Math.PI * 0.45 + Math.cos(flowTime * 0.5) * 0.15 + currentTiltY * 0.5;

        particles.rotation.y = -flowTime * 0.12;
        particles.rotation.x = flowTime * 0.08;

        renderer.render(scene, camera);
    }

    animate();

    // 9. Resize Handling
    function handleResize() {
        if (!container) return;
        const newW = container.clientWidth || 112;
        const newH = container.clientHeight || 112;
        if (newW > 0 && newH > 0) {
            camera.aspect = newW / newH;
            camera.updateProjectionMatrix();
            renderer.setSize(newW, newH);
        }
    }

    window.addEventListener('resize', handleResize);

    // 10. Destroy cleanup
    return {
        destroy: () => {
            if (animId) cancelAnimationFrame(animId);
            window.removeEventListener('resize', handleResize);
            parentCard.removeEventListener('mouseenter', onMouseEnter);
            parentCard.removeEventListener('mouseleave', onMouseLeave);
            parentCard.removeEventListener('mousemove', onMouseMove);
            geometry.dispose();
            material.dispose();
            ring1Geom.dispose();
            ring1Mat.dispose();
            ring2Geom.dispose();
            ring2Mat.dispose();
            particleGeom.dispose();
            particleMat.dispose();
            renderer.dispose();
            container.innerHTML = '';
        }
    };
}
