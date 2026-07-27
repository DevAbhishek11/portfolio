/* ═══════════════════════════════════════════════════════════════════════════
   FLOATING-OBJECTS.JS
   Animated 3D geometric objects with toon shading, mouse proximity,
   scroll reactions, and per-object personality.
   ═══════════════════════════════════════════════════════════════════════════ */

const FloatingObjects = (() => {
  // ── Object definitions ────────────────────────────────────────────────
  const DEFS = [
    // [geometry, color, wireframe, x, y, z, scale, rotSpeedX, rotSpeedY]
    {
      type: "torus",
      color: 0x8b5cf6,
      wire: false,
      pos: [-4.5, 2.2, -2],
      s: 0.55,
      rx: 0.008,
      ry: 0.012,
    },
    {
      type: "icosa",
      color: 0x06b6d4,
      wire: true,
      pos: [4.2, 1.8, -1.5],
      s: 0.5,
      rx: 0.01,
      ry: 0.008,
    },
    {
      type: "octa",
      color: 0xf43f5e,
      wire: false,
      pos: [-3.2, -2.0, -2.5],
      s: 0.48,
      rx: 0.006,
      ry: 0.015,
    },
    {
      type: "box",
      color: 0xeab308,
      wire: true,
      pos: [3.8, -1.5, -3],
      s: 0.52,
      rx: 0.012,
      ry: 0.007,
    },
    {
      type: "torusKnot",
      color: 0x8b5cf6,
      wire: false,
      pos: [-5.5, -0.5, -4],
      s: 0.38,
      rx: 0.007,
      ry: 0.014,
    },
    {
      type: "sphere",
      color: 0x06b6d4,
      wire: false,
      pos: [5.0, 0.5, -3.5],
      s: 0.4,
      rx: 0.005,
      ry: 0.01,
    },
    {
      type: "cone",
      color: 0xf43f5e,
      wire: true,
      pos: [-1.5, 3.5, -4],
      s: 0.44,
      rx: 0.009,
      ry: 0.006,
    },
    {
      type: "dodeca",
      color: 0x22c55e,
      wire: false,
      pos: [1.8, -3.2, -3],
      s: 0.45,
      rx: 0.011,
      ry: 0.009,
    },
    // Code symbols
    {
      type: "torus",
      color: 0xa78bfa,
      wire: true,
      pos: [6.5, 3.0, -5],
      s: 0.35,
      rx: 0.006,
      ry: 0.016,
    },
    {
      type: "icosa",
      color: 0xfb7185,
      wire: false,
      pos: [-6.0, 2.8, -5],
      s: 0.3,
      rx: 0.014,
      ry: 0.008,
    },
    {
      type: "box",
      color: 0x22d3ee,
      wire: false,
      pos: [0.5, 4.5, -6],
      s: 0.3,
      rx: 0.007,
      ry: 0.012,
    },
    {
      type: "sphere",
      color: 0xfacc15,
      wire: true,
      pos: [-7.0, -1.0, -6],
      s: 0.28,
      rx: 0.01,
      ry: 0.01,
    },
  ];

  function makeGeo(type) {
    switch (type) {
      case "torus":
        return new THREE.TorusGeometry(0.5, 0.15, 12, 40);
      case "icosa":
        return new THREE.IcosahedronGeometry(0.5, 0);
      case "octa":
        return new THREE.OctahedronGeometry(0.5, 0);
      case "box":
        return new THREE.BoxGeometry(0.7, 0.7, 0.7);
      case "torusKnot":
        return new THREE.TorusKnotGeometry(0.38, 0.1, 80, 12);
      case "sphere":
        return new THREE.SphereGeometry(0.45, 14, 10);
      case "cone":
        return new THREE.ConeGeometry(0.4, 0.8, 8);
      case "dodeca":
        return new THREE.DodecahedronGeometry(0.48);
      default:
        return new THREE.IcosahedronGeometry(0.5, 0);
    }
  }

  // ── State ─────────────────────────────────────────────────────────────
  let objects = [];
  let sceneRef = null;

  function lerp(a, b, t) {
    return a + (b - a) * t;
  }

  // ── Module API ────────────────────────────────────────────────────────
  function init(sceneState) {
    sceneRef = sceneState;
    const { scene, mobile } = sceneState;
    if (!scene) return;

    const defSlice = mobile ? DEFS.slice(0, 6) : DEFS;

    defSlice.forEach((def) => {
      const geo = makeGeo(def.type);
      const mat = def.wire
        ? new THREE.MeshBasicMaterial({ color: def.color, wireframe: true })
        : new THREE.MeshToonMaterial({ color: def.color });

      const mesh = new THREE.Mesh(geo, mat);
      mesh.position.set(...def.pos);
      mesh.scale.setScalar(def.s);

      // Emissive glow for solid meshes
      if (!def.wire) {
        mat.emissive = new THREE.Color(def.color);
        mat.emissiveIntensity = 0.08;
      }

      // Per-object personality
      mesh.userData = {
        basePos: new THREE.Vector3(...def.pos),
        rotSpeedX: def.rx,
        rotSpeedY: def.ry,
        floatAmp: 0.08 + Math.random() * 0.14,
        floatSpeed: 0.3 + Math.random() * 0.5,
        floatOff: Math.random() * Math.PI * 2,
        scrollMult: (Math.random() - 0.5) * 0.004,
        pullRadius: 2.5,
        pulled: false,
      };

      scene.add(mesh);
      objects.push(mesh);
    });
  }

  function update(elapsed, delta, sceneState) {
    if (!objects.length) return;

    const { mouse, scroll } = sceneState;

    // Build mouse 3D position (approximate world coords)
    const mouseWorld = new THREE.Vector3(mouse.nx * 8, -mouse.ny * 5, 2);

    objects.forEach((obj) => {
      const ud = obj.userData;

      // Continuous rotation
      obj.rotation.x += ud.rotSpeedX;
      obj.rotation.y += ud.rotSpeedY;

      // Float
      const floatY =
        Math.sin(elapsed * ud.floatSpeed + ud.floatOff) * ud.floatAmp;
      const floatX =
        Math.cos(elapsed * ud.floatSpeed * 0.7 + ud.floatOff) *
        ud.floatAmp *
        0.4;

      // Scroll displacement
      const scrollOffset = scroll.y * ud.scrollMult;

      // Mouse proximity pull / repel
      const dist = obj.position.distanceTo(mouseWorld);
      if (dist < ud.pullRadius) {
        const force = (1 - dist / ud.pullRadius) * 0.4;
        const dir = new THREE.Vector3()
          .subVectors(obj.position, mouseWorld)
          .normalize();
        obj.position.lerp(
          new THREE.Vector3(
            ud.basePos.x + dir.x * force * 1.2,
            ud.basePos.y + dir.y * force * 0.8 + floatY,
            ud.basePos.z,
          ),
          0.08,
        );
      } else {
        // Return to base + float + scroll
        obj.position.lerp(
          new THREE.Vector3(
            ud.basePos.x + floatX,
            ud.basePos.y + floatY + scrollOffset,
            ud.basePos.z,
          ),
          0.04,
        );
      }

      // Scale pulse on scroll velocity
      const velPulse = 1 + Math.abs(scroll.velocity) * 0.001;
      obj.scale.setScalar(
        lerp(obj.scale.x, sceneRef.mobile ? ud.s * 0.7 : ud.s, 0.05) * velPulse,
      );
    });
  }

  function onPulse() {
    objects.forEach((obj) => {
      // Scatter outward
      const dir = new THREE.Vector3(
        (Math.random() - 0.5) * 2,
        (Math.random() - 0.5) * 2,
        0,
      ).normalize();
      const ud = obj.userData;
      const orig = ud.basePos.clone();
      obj.position.add(dir.multiplyScalar(1.5));
      // They'll lerp back naturally in update
    });
  }

  function onResize(sceneState) {
    sceneRef = sceneState;
  }

  return { init, update, onPulse, onResize };
})();
