/* ═══════════════════════════════════════════════════════════════════════════
   ANIME-CHARACTER.JS
   Procedural anime-style character built entirely from THREE primitives.
   No external model file required — looks great and loads instantly.
   ═══════════════════════════════════════════════════════════════════════════ */

const AnimeCharacter = (() => {
  // ── Palette ───────────────────────────────────────────────────────────
  const C = {
    skin: 0xffd5b8,
    hair: 0x1a0a2e,
    eye: 0x8b5cf6,
    sclera: 0xffffff,
    cloth1: 0x8b5cf6,
    cloth2: 0x06b6d4,
    accent: 0xf43f5e,
    outline: 0x1a0a2e,
  };

  const mat = (color, emissive = 0, roughness = 0.6, metalness = 0) =>
    new THREE.MeshStandardMaterial({ color, emissive, roughness, metalness });

  const cel = (color) => new THREE.MeshToonMaterial({ color });

  // ── Build character group ─────────────────────────────────────────────
  function build() {
    const root = new THREE.Group();
    root.name = "animeChar";

    // ── Body ─────────────────────────────────────────────────────────
    const torso = new THREE.Mesh(
      new THREE.CylinderGeometry(0.38, 0.32, 0.9, 12),
      cel(C.cloth1),
    );
    torso.position.y = 0;
    root.add(torso);

    // Collar detail
    const collar = new THREE.Mesh(
      new THREE.TorusGeometry(0.36, 0.05, 8, 24),
      cel(C.cloth2),
    );
    collar.position.y = 0.42;
    collar.rotation.x = Math.PI / 2;
    root.add(collar);

    // ── Hips ─────────────────────────────────────────────────────────
    const hips = new THREE.Mesh(
      new THREE.CylinderGeometry(0.35, 0.4, 0.4, 12),
      cel(C.cloth1),
    );
    hips.position.y = -0.65;
    root.add(hips);

    // ── Legs ─────────────────────────────────────────────────────────
    const legGeo = new THREE.CylinderGeometry(0.14, 0.12, 0.85, 10);
    const bootGeo = new THREE.CapsuleGeometry(0.13, 0.25, 4, 8);

    [-0.2, 0.2].forEach((xOff, i) => {
      const leg = new THREE.Mesh(legGeo, cel(C.cloth2));
      leg.position.set(xOff, -1.08, 0);
      root.add(leg);

      const boot = new THREE.Mesh(bootGeo, cel(C.accent));
      boot.position.set(xOff, -1.6, 0.06);
      boot.rotation.x = -0.2;
      root.add(boot);
    });

    // ── Arms ─────────────────────────────────────────────────────────
    const armGeo = new THREE.CylinderGeometry(0.1, 0.09, 0.75, 8);
    const forearmGeo = new THREE.CylinderGeometry(0.09, 0.08, 0.65, 8);
    const handGeo = new THREE.SphereGeometry(0.1, 8, 6);

    const arms = [];
    [
      [-0.55, 1],
      [0.55, -1],
    ].forEach(([xOff, side]) => {
      const shoulder = new THREE.Group();
      shoulder.position.set(xOff, 0.25, 0);

      const upper = new THREE.Mesh(armGeo, cel(C.cloth1));
      upper.position.set(0, -0.3, 0);
      upper.rotation.z = side * 0.25;
      shoulder.add(upper);

      const elbow = new THREE.Group();
      elbow.position.set(0, -0.75, 0);
      shoulder.add(elbow);

      const fore = new THREE.Mesh(forearmGeo, cel(C.skin));
      fore.position.set(0, -0.3, 0);
      fore.rotation.z = side * 0.15;
      elbow.add(fore);

      const hand = new THREE.Mesh(handGeo, cel(C.skin));
      hand.position.set(side * 0.05, -0.65, 0);
      elbow.add(hand);

      root.add(shoulder);
      arms.push({ shoulder, elbow });
    });

    // ── Neck ─────────────────────────────────────────────────────────
    const neck = new THREE.Mesh(
      new THREE.CylinderGeometry(0.14, 0.16, 0.25, 10),
      cel(C.skin),
    );
    neck.position.y = 0.54;
    root.add(neck);

    // ── Head ─────────────────────────────────────────────────────────
    const head = new THREE.Group();
    head.position.y = 1.05;
    root.add(head);

    // Skull
    const skull = new THREE.Mesh(
      new THREE.SphereGeometry(0.42, 16, 12),
      cel(C.skin),
    );
    skull.scale.set(1, 1.08, 0.95);
    head.add(skull);

    // Jaw / chin
    const jaw = new THREE.Mesh(
      new THREE.ConeGeometry(0.28, 0.32, 12),
      cel(C.skin),
    );
    jaw.position.y = -0.33;
    jaw.rotation.x = Math.PI;
    head.add(jaw);

    // ── Eyes ─────────────────────────────────────────────────────────
    const eyeData = [
      { x: -0.17, side: -1 },
      { x: 0.17, side: 1 },
    ];
    const eyes = [];

    eyeData.forEach(({ x }) => {
      const eyeGroup = new THREE.Group();
      eyeGroup.position.set(x, 0.06, 0.36);
      head.add(eyeGroup);

      // Sclera
      const sclera = new THREE.Mesh(
        new THREE.SphereGeometry(0.1, 12, 10),
        cel(C.sclera),
      );
      sclera.scale.set(1, 1.1, 0.6);
      eyeGroup.add(sclera);

      // Iris
      const iris = new THREE.Mesh(
        new THREE.CircleGeometry(0.065, 20),
        cel(C.eye),
      );
      iris.position.z = 0.06;
      eyeGroup.add(iris);

      // Pupil
      const pupil = new THREE.Mesh(
        new THREE.CircleGeometry(0.032, 16),
        new THREE.MeshBasicMaterial({ color: 0x0a0010 }),
      );
      pupil.position.z = 0.065;
      eyeGroup.add(pupil);

      // Highlight
      const highlight = new THREE.Mesh(
        new THREE.CircleGeometry(0.016, 8),
        new THREE.MeshBasicMaterial({ color: 0xffffff }),
      );
      highlight.position.set(0.02, 0.025, 0.07);
      eyeGroup.add(highlight);

      // Upper eyelid
      const lid = new THREE.Mesh(
        new THREE.CylinderGeometry(0.1, 0.1, 0.015, 12, 1, false, 0, Math.PI),
        new THREE.MeshBasicMaterial({ color: C.outline }),
      );
      lid.position.set(0, 0.08, 0.04);
      lid.rotation.x = Math.PI / 2;
      eyeGroup.add(lid);

      eyes.push(eyeGroup);
    });

    // ── Eyebrows ──────────────────────────────────────────────────────
    [-0.17, 0.17].forEach((x) => {
      const brow = new THREE.Mesh(
        new THREE.BoxGeometry(0.14, 0.018, 0.015),
        new THREE.MeshBasicMaterial({ color: C.outline }),
      );
      brow.position.set(x, 0.21, 0.37);
      brow.rotation.z = x < 0 ? 0.12 : -0.12;
      head.add(brow);
    });

    // ── Mouth ─────────────────────────────────────────────────────────
    const mouth = new THREE.Mesh(
      new THREE.TorusGeometry(0.07, 0.012, 6, 12, Math.PI),
      new THREE.MeshBasicMaterial({ color: 0xc06060 }),
    );
    mouth.position.set(0, -0.15, 0.38);
    mouth.rotation.z = Math.PI;
    head.add(mouth);

    // ── Hair ─────────────────────────────────────────────────────────
    // Main hair cap
    const hairCap = new THREE.Mesh(
      new THREE.SphereGeometry(0.44, 14, 10, 0, Math.PI * 2, 0, Math.PI * 0.55),
      cel(C.hair),
    );
    hairCap.position.y = 0.12;
    head.add(hairCap);

    // Side hair strands
    const strandGeo = new THREE.ConeGeometry(0.07, 0.55, 6);
    const strandPositions = [
      { pos: [-0.38, -0.1, 0.1], rot: [0.3, 0, 0.5] },
      { pos: [0.38, -0.1, 0.1], rot: [0.3, 0, -0.5] },
      { pos: [-0.3, -0.15, -0.2], rot: [0.1, 0.3, 0.4] },
      { pos: [0.3, -0.15, -0.2], rot: [0.1, -0.3, -0.4] },
    ];
    strandPositions.forEach(({ pos, rot }) => {
      const s = new THREE.Mesh(strandGeo, cel(C.hair));
      s.position.set(...pos);
      s.rotation.set(...rot);
      head.add(s);
    });

    // Front hair spikes
    const spikeGeo = new THREE.ConeGeometry(0.055, 0.4, 5);
    [
      [-0.15, 0.45, 0.22],
      [0, 0.52, 0.18],
      [0.15, 0.45, 0.22],
    ].forEach(([x, y, z], i) => {
      const spike = new THREE.Mesh(spikeGeo, cel(C.hair));
      spike.position.set(x, y, z);
      spike.rotation.set(-0.4 + i * 0.1, 0, x * -1.5);
      head.add(spike);
    });

    // ── Accessories ───────────────────────────────────────────────────
    // Headband
    const band = new THREE.Mesh(
      new THREE.TorusGeometry(0.44, 0.03, 8, 28),
      cel(C.cloth2),
    );
    band.position.y = 0.18;
    band.rotation.x = Math.PI / 2;
    head.add(band);

    // Earrings
    [-0.43, 0.43].forEach((x) => {
      const ring = new THREE.Mesh(
        new THREE.TorusGeometry(0.05, 0.012, 6, 12),
        new THREE.MeshStandardMaterial({
          color: 0xffd700,
          metalness: 0.9,
          roughness: 0.1,
        }),
      );
      ring.position.set(x, -0.1, 0);
      ring.rotation.y = Math.PI / 2;
      head.add(ring);
    });

    // ── Outfit details ────────────────────────────────────────────────
    // Belt
    const belt = new THREE.Mesh(
      new THREE.CylinderGeometry(0.37, 0.37, 0.1, 16),
      cel(C.accent),
    );
    belt.position.y = -0.5;
    root.add(belt);

    // Buckle
    const buckle = new THREE.Mesh(
      new THREE.BoxGeometry(0.14, 0.1, 0.06),
      new THREE.MeshStandardMaterial({
        color: 0xffd700,
        metalness: 0.9,
        roughness: 0.1,
      }),
    );
    buckle.position.set(0, -0.5, 0.38);
    root.add(buckle);

    // Chest emblem
    const emblem = new THREE.Mesh(
      new THREE.CircleGeometry(0.1, 6),
      new THREE.MeshStandardMaterial({
        color: C.accent,
        emissive: 0xf43f5e,
        emissiveIntensity: 0.4,
      }),
    );
    emblem.position.set(0, 0.1, 0.4);
    root.add(emblem);

    // ── Aura ring (animated) ──────────────────────────────────────────
    const auraGeo = new THREE.RingGeometry(0.6, 0.65, 32);
    const auraMat = new THREE.MeshBasicMaterial({
      color: C.eye,
      transparent: true,
      opacity: 0.3,
      side: THREE.DoubleSide,
    });
    const aura = new THREE.Mesh(auraGeo, auraMat);
    aura.name = "aura";
    aura.position.y = -0.3;
    aura.rotation.x = Math.PI / 2;
    root.add(aura);

    const aura2 = aura.clone();
    aura2.scale.set(1.5, 1.5, 1.5);
    aura2.material = auraMat.clone();
    aura2.material.opacity = 0.12;
    root.add(aura2);

    return { root, head, eyes, arms, aura, aura2 };
  }

  // ── Module interface ──────────────────────────────────────────────────
  let parts = null;
  let blinkTimer = 0;
  let blinkState = "open";
  let blinkNext = 3;
  let breathPhase = 0;
  let moodAngle = { x: 0, y: 0 }; // current head angle
  let targetMood = { x: 0, y: 0 }; // target head angle
  let scrollLean = 0;
  let keyTiltX = 0,
    keyTiltY = 0;
  let pulseScale = 1;
  let wavePhase = 0;
  let waving = false;
  let waveCooldown = 0;
  const LERP = 0.06;

  function lerp(a, b, t) {
    return a + (b - a) * t;
  }

  function init(sceneState) {
    if (!sceneState.scene || sceneState.mobile) return;
    parts = build();

    // Position character right-of-centre
    parts.root.position.set(1.8, -0.5, 0);
    parts.root.scale.setScalar(0.9);
    sceneState.scene.add(parts.root);
  }

  function update(elapsed, delta, sceneState) {
    if (!parts) return;

    const { mouse, scroll } = sceneState;

    // ── Breathing ────────────────────────────────────────────────────
    breathPhase += delta * 0.9;
    const breathY = Math.sin(breathPhase) * 0.018;
    parts.root.position.y = -0.5 + breathY;

    // ── Head tracks mouse ─────────────────────────────────────────────
    // Convert mouse normalised coords to head angle
    targetMood.x = lerp(targetMood.x, -mouse.ny * 0.35 + scrollLean, 0.08);
    targetMood.y = lerp(targetMood.y, mouse.nx * 0.45, 0.08);

    // Apply key tilt on top
    moodAngle.x = lerp(moodAngle.x, targetMood.x + keyTiltX, LERP);
    moodAngle.y = lerp(moodAngle.y, targetMood.y + keyTiltY, LERP);

    parts.head.rotation.x = moodAngle.x;
    parts.head.rotation.y = moodAngle.y;

    // ── Scroll lean ───────────────────────────────────────────────────
    const normalScroll = Math.min(scroll.y / 2000, 1);
    scrollLean = normalScroll * -0.4;
    parts.root.rotation.x = lerp(parts.root.rotation.x, scrollLean * 0.3, 0.05);

    // ── Eye tracking ──────────────────────────────────────────────────
    parts.eyes.forEach((eye) => {
      eye.rotation.y = lerp(eye.rotation.y, mouse.nx * 0.12, 0.08);
      eye.rotation.x = lerp(eye.rotation.x, -mouse.ny * 0.1, 0.08);
    });

    // ── Blink cycle ───────────────────────────────────────────────────
    blinkTimer += delta;
    if (blinkState === "open" && blinkTimer > blinkNext) {
      blinkState = "closing";
      blinkTimer = 0;
      blinkNext = 2.5 + Math.random() * 4;
    }
    if (blinkState === "closing") {
      parts.eyes.forEach((eye) => {
        eye.scale.y = lerp(eye.scale.y, 0.05, 0.25);
      });
      if (blinkTimer > 0.12) {
        blinkState = "opening";
        blinkTimer = 0;
      }
    }
    if (blinkState === "opening") {
      parts.eyes.forEach((eye) => {
        eye.scale.y = lerp(eye.scale.y, 1, 0.2);
      });
      if (blinkTimer > 0.15) {
        blinkState = "open";
      }
    }

    // ── Wave animation ────────────────────────────────────────────────
    waveCooldown = Math.max(0, waveCooldown - delta);

    if (waving) {
      wavePhase += delta * 6;
      const waveAmt = Math.sin(wavePhase) * 0.8;
      // Right arm (index 1) waves
      if (parts.arms[1]) {
        parts.arms[1].shoulder.rotation.z = -0.5 + waveAmt;
        parts.arms[1].shoulder.rotation.x = -0.3;
      }
      if (wavePhase > Math.PI * 4) {
        waving = false;
        wavePhase = 0;
        if (parts.arms[1]) {
          parts.arms[1].shoulder.rotation.z = -0.25;
          parts.arms[1].shoulder.rotation.x = 0;
        }
      }
    } else {
      // Idle arm sway
      if (parts.arms[0]) {
        parts.arms[0].shoulder.rotation.z = lerp(
          parts.arms[0].shoulder.rotation.z,
          0.3 + Math.sin(elapsed * 0.7) * 0.04,
          0.05,
        );
      }
      if (parts.arms[1]) {
        parts.arms[1].shoulder.rotation.z = lerp(
          parts.arms[1].shoulder.rotation.z,
          -0.3 + Math.sin(elapsed * 0.7 + 1) * 0.04,
          0.05,
        );
      }
    }

    // ── Aura pulse ────────────────────────────────────────────────────
    const auraPulse = 0.25 + Math.sin(elapsed * 1.5) * 0.08;
    parts.aura.material.opacity = auraPulse;
    parts.aura2.material.opacity = auraPulse * 0.45;
    parts.aura.rotation.z += delta * 0.3;
    parts.aura2.rotation.z -= delta * 0.18;

    // ── Pulse reaction ────────────────────────────────────────────────
    pulseScale = lerp(pulseScale, 1, 0.08);
    parts.root.scale.setScalar(lerp(parts.root.scale.x, 0.9 * pulseScale, 0.1));

    // ── Decay key tilt ────────────────────────────────────────────────
    keyTiltX = lerp(keyTiltX, 0, 0.04);
    keyTiltY = lerp(keyTiltY, 0, 0.04);
  }

  function onKeyTilt(key) {
    const amt = 0.35;
    if (key === "ArrowLeft") keyTiltY = lerp(keyTiltY, -amt, 0.5);
    if (key === "ArrowRight") keyTiltY = lerp(keyTiltY, amt, 0.5);
    if (key === "ArrowUp") keyTiltX = lerp(keyTiltX, -amt * 0.6, 0.5);
    if (key === "ArrowDown") keyTiltX = lerp(keyTiltX, amt * 0.6, 0.5);
  }

  function onPulse() {
    pulseScale = 1.15;
    if (parts) {
      // Jump
      const startY = parts.root.position.y;
      let t = 0;
      const jumpFn = () => {
        t += 0.05;
        parts.root.position.y = startY + Math.sin(t * Math.PI) * 0.8;
        if (t < 1) requestAnimationFrame(jumpFn);
        else parts.root.position.y = startY;
      };
      requestAnimationFrame(jumpFn);
    }
  }

  function triggerWave() {
    if (waving || waveCooldown > 0) return;
    waving = true;
    waveCooldown = 4;
  }

  return { init, update, onKeyTilt, onPulse, triggerWave };
})();