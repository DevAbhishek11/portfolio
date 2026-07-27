/* ═══════════════════════════════════════════════════════════════════════════
   SCENE-ENVIRONMENT.JS
   Background: animated star field, grid plane, energy rings,
   screen-space glow lines — all in Three.js primitives.
   ═══════════════════════════════════════════════════════════════════════════ */

const SceneEnvironment = (() => {
  let starPoints = null;
  let gridMesh = null;
  let rings = [];
  let energyLines = [];

  // ── Stars ─────────────────────────────────────────────────────────────
  function buildStars(scene, count = 600) {
    const geo = new THREE.BufferGeometry();
    const pos = new Float32Array(count * 3);
    const sizes = new Float32Array(count);
    const colors = new Float32Array(count * 3);

    const palette = [
      [0.55, 0.36, 0.97], // purple
      [0.02, 0.71, 0.83], // cyan
      [0.96, 0.25, 0.37], // pink
      [0.92, 0.71, 0.1], // amber
      [1.0, 1.0, 1.0], // white
    ];

    for (let i = 0; i < count; i++) {
      const r = 30 + Math.random() * 50;
      const theta = Math.random() * Math.PI * 2;
      const phi = Math.acos(2 * Math.random() - 1);
      pos[i * 3] = r * Math.sin(phi) * Math.cos(theta);
      pos[i * 3 + 1] = r * Math.sin(phi) * Math.sin(theta);
      pos[i * 3 + 2] = r * Math.cos(phi);

      sizes[i] = 0.04 + Math.random() * 0.12;

      const c = palette[Math.floor(Math.random() * palette.length)];
      colors[i * 3] = c[0];
      colors[i * 3 + 1] = c[1];
      colors[i * 3 + 2] = c[2];
    }

    geo.setAttribute("position", new THREE.BufferAttribute(pos, 3));
    geo.setAttribute("size", new THREE.BufferAttribute(sizes, 1));
    geo.setAttribute("color", new THREE.BufferAttribute(colors, 3));

    const mat = new THREE.PointsMaterial({
      size: 0.08,
      vertexColors: true,
      transparent: true,
      opacity: 0.75,
      sizeAttenuation: true,
    });

    starPoints = new THREE.Points(geo, mat);
    scene.add(starPoints);
  }

  // ── Ground grid ───────────────────────────────────────────────────────
  function buildGrid(scene) {
    const geo = new THREE.PlaneGeometry(40, 40, 20, 20);
    const mat = new THREE.MeshBasicMaterial({
      color: 0x8b5cf6,
      wireframe: true,
      transparent: true,
      opacity: 0.06,
    });
    gridMesh = new THREE.Mesh(geo, mat);
    gridMesh.rotation.x = -Math.PI / 2;
    gridMesh.position.y = -4;
    scene.add(gridMesh);
  }

  // ── Energy rings ──────────────────────────────────────────────────────
  function buildRings(scene) {
    const ringData = [
      {
        r: 3.5,
        tube: 0.012,
        color: 0x8b5cf6,
        opacity: 0.25,
        y: -0.5,
        rotX: 1.2,
        speedY: 0.004,
      },
      {
        r: 5.0,
        tube: 0.008,
        color: 0x06b6d4,
        opacity: 0.18,
        y: -0.5,
        rotX: 0.6,
        speedY: -0.003,
      },
      {
        r: 7.0,
        tube: 0.006,
        color: 0xf43f5e,
        opacity: 0.12,
        y: -0.5,
        rotX: 0.3,
        speedY: 0.002,
      },
    ];

    ringData.forEach((r) => {
      const geo = new THREE.TorusGeometry(r.r, r.tube, 8, 80);
      const mat = new THREE.MeshBasicMaterial({
        color: r.color,
        transparent: true,
        opacity: r.opacity,
      });
      const ring = new THREE.Mesh(geo, mat);
      ring.rotation.x = r.rotX;
      ring.position.y = r.y;
      ring.userData = { speedY: r.speedY };
      scene.add(ring);
      rings.push(ring);
    });
  }

  // ── Energy lines (radiating from origin) ─────────────────────────────
  function buildEnergyLines(scene, count = 12) {
    for (let i = 0; i < count; i++) {
      const angle = (i / count) * Math.PI * 2;
      const length = 4 + Math.random() * 4;
      const points = [
        new THREE.Vector3(0, 0, 0),
        new THREE.Vector3(
          Math.cos(angle) * length,
          Math.sin(angle) * length * 0.5,
          -2,
        ),
      ];
      const geo = new THREE.BufferGeometry().setFromPoints(points);
      const mat = new THREE.LineBasicMaterial({
        color: 0x8b5cf6,
        transparent: true,
        opacity: 0.0,
      });
      const line = new THREE.Line(geo, mat);
      line.userData = {
        angle,
        length,
        phase: Math.random() * Math.PI * 2,
        speed: 0.4 + Math.random() * 0.8,
      };
      scene.add(line);
      energyLines.push(line);
    }
  }

  // ── Module API ────────────────────────────────────────────────────────
  function init(sceneState) {
    const { scene, mobile } = sceneState;
    if (!scene) return;

    buildStars(scene, mobile ? 200 : 600);
    buildGrid(scene);
    buildRings(scene);
    if (!mobile) buildEnergyLines(scene);
  }

  function update(elapsed, delta, sceneState) {
    const { scroll } = sceneState;

    // Stars slow rotation
    if (starPoints) {
      starPoints.rotation.y += delta * 0.015;
      starPoints.rotation.x = Math.sin(elapsed * 0.05) * 0.03;
    }

    // Grid scroll perspective
    if (gridMesh) {
      gridMesh.position.y = -4 + scroll.y * 0.001;
      gridMesh.material.opacity = Math.max(0.02, 0.06 - scroll.y * 0.00005);
    }

    // Rings
    rings.forEach((ring) => {
      ring.rotation.y += ring.userData.speedY;
      ring.rotation.z += ring.userData.speedY * 0.5;
    });

    // Energy lines — pulse on/off
    energyLines.forEach((line) => {
      line.userData.phase += delta * line.userData.speed;
      const pulse = Math.max(0, Math.sin(line.userData.phase));
      line.material.opacity = pulse * 0.3;
    });
  }

  function onPulse() {
    // Burst all energy lines
    energyLines.forEach((line) => {
      line.userData.phase = 0;
    });
    rings.forEach((ring) => {
      const orig = ring.userData.speedY;
      ring.userData.speedY *= 8;
      setTimeout(() => {
        ring.userData.speedY = orig;
      }, 500);
    });
  }

  return { init, update, onPulse };
})();
