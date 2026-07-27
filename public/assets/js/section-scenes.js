/* ═══════════════════════════════════════════════════════════════════════════
   SECTION-SCENES.JS
   Page-specific 3D enhancements: about page orb, skills sphere, contact
   floating mailbox — each uses its own canvas, lightweight and independent.
   ═══════════════════════════════════════════════════════════════════════════ */

const SectionScenes = (() => {
  // ── Generic mini-scene factory ────────────────────────────────────────
  function createMiniScene(canvasId, buildFn) {
    const canvas = document.getElementById(canvasId);
    if (!canvas || typeof THREE === "undefined") return;

    const renderer = new THREE.WebGLRenderer({
      canvas,
      alpha: true,
      antialias: true,
    });
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 1.5));
    renderer.setSize(
      canvas.clientWidth || 300,
      canvas.clientHeight || 300,
      false,
    );

    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(50, 1, 0.1, 50);
    camera.position.z = 5;

    scene.add(new THREE.AmbientLight(0xffffff, 0.5));
    const dl = new THREE.DirectionalLight(0x8b5cf6, 1.5);
    dl.position.set(3, 5, 4);
    scene.add(dl);
    scene.add(
      Object.assign(new THREE.PointLight(0x06b6d4, 0.8), {
        position: new THREE.Vector3(-3, -2, 2),
      }),
    );

    const objects = buildFn(scene);

    const clock = new THREE.Clock();
    let paused = false;
    document.addEventListener("visibilitychange", () => {
      paused = document.hidden;
    });

    window.addEventListener("resize", () => {
      const w = canvas.clientWidth || 300;
      const h = canvas.clientHeight || 300;
      renderer.setSize(w, h, false);
      camera.aspect = w / h;
      camera.updateProjectionMatrix();
    });

    function loop() {
      requestAnimationFrame(loop);
      if (paused) return;
      const elapsed = clock.getElapsedTime();
      objects?.forEach?.((o) => o.tick?.(elapsed));
      renderer.render(scene, camera);
    }
    loop();
  }

  // ── About page — DNA / knowledge orb ─────────────────────────────────
  function initAboutOrb() {
    createMiniScene("about-canvas", (scene) => {
      const group = new THREE.Group();
      const colors = [0x8b5cf6, 0x06b6d4, 0xf43f5e, 0xeab308, 0x22c55e];
      const items = [];

      // Inner sphere
      const core = new THREE.Mesh(
        new THREE.SphereGeometry(0.6, 24, 18),
        new THREE.MeshToonMaterial({
          color: 0x8b5cf6,
          transparent: true,
          opacity: 0.15,
        }),
      );
      group.add(core);

      // Orbiting nodes on 3 rings
      for (let ring = 0; ring < 3; ring++) {
        const radius = 1.2 + ring * 0.6;
        const count = 6 + ring * 4;
        const tilt = ring * 0.4;

        for (let i = 0; i < count; i++) {
          const angle = (i / count) * Math.PI * 2;
          const sphere = new THREE.Mesh(
            new THREE.SphereGeometry(0.07 + Math.random() * 0.05, 8, 6),
            new THREE.MeshToonMaterial({
              color: colors[(ring + i) % colors.length],
            }),
          );
          sphere.userData = {
            radius,
            angle,
            tilt,
            ring,
            speed: 0.3 + ring * 0.15 + Math.random() * 0.1,
          };
          group.add(sphere);
          items.push(sphere);

          // Orbit trail (thin ring)
          if (i === 0) {
            const trailGeo = new THREE.TorusGeometry(radius, 0.008, 4, 64);
            const trail = new THREE.Mesh(
              trailGeo,
              new THREE.MeshBasicMaterial({
                color: colors[ring % colors.length],
                transparent: true,
                opacity: 0.15,
              }),
            );
            trail.rotation.x = Math.PI / 2 + tilt;
            group.add(trail);
          }
        }
      }

      scene.add(group);

      return [
        {
          tick(elapsed) {
            group.rotation.y = elapsed * 0.12;
            group.rotation.x = Math.sin(elapsed * 0.2) * 0.15;

            items.forEach((s) => {
              const { radius, angle, tilt, speed } = s.userData;
              const a = angle + elapsed * speed;
              s.position.set(
                Math.cos(a) * radius,
                Math.sin(a * 0.4 + tilt) * radius * 0.3,
                Math.sin(a) * radius,
              );
            });
          },
        },
      ];
    });
  }

  // ── Skills page — rotating tech globe ─────────────────────────────────
  function initSkillsGlobe() {
    createMiniScene("skills-canvas", (scene) => {
      const group = new THREE.Group();
      scene.add(group);

      // Wire sphere
      scene.add(
        new THREE.Mesh(
          new THREE.SphereGeometry(1.4, 20, 14),
          new THREE.MeshBasicMaterial({
            color: 0x8b5cf6,
            wireframe: true,
            transparent: true,
            opacity: 0.08,
          }),
        ),
      );

      // Fibonacci distribution of skill nodes
      const techColors = [
        0x8b5cf6, 0x06b6d4, 0xf43f5e, 0xeab308, 0x22c55e, 0xa78bfa,
      ];
      const nodes = [];
      const golden = Math.PI * (3 - Math.sqrt(5));
      const count = 24;

      for (let i = 0; i < count; i++) {
        const y = 1 - (i / (count - 1)) * 2;
        const r = Math.sqrt(1 - y * y) * 1.5;
        const theta = golden * i;
        const size = 0.06 + Math.random() * 0.07;

        const node = new THREE.Mesh(
          new THREE.SphereGeometry(size, 8, 6),
          new THREE.MeshToonMaterial({
            color: techColors[i % techColors.length],
          }),
        );
        node.position.set(r * Math.cos(theta), y * 1.5, r * Math.sin(theta));
        group.add(node);
        nodes.push(node);

        // Tiny connecting lines between nearby nodes
        if (i > 0 && i % 3 === 0) {
          const prev = nodes[i - 3];
          const pts = [node.position.clone(), prev.position.clone()];
          const line = new THREE.Line(
            new THREE.BufferGeometry().setFromPoints(pts),
            new THREE.LineBasicMaterial({
              color: 0x8b5cf6,
              transparent: true,
              opacity: 0.12,
            }),
          );
          group.add(line);
        }
      }

      // Center core
      group.add(
        new THREE.Mesh(
          new THREE.IcosahedronGeometry(0.3, 1),
          new THREE.MeshToonMaterial({ color: 0x8b5cf6 }),
        ),
      );

      return [
        {
          tick(e) {
            group.rotation.y = e * 0.18;
            group.rotation.x = Math.sin(e * 0.14) * 0.25;
            nodes.forEach((n, i) => {
              n.scale.setScalar(0.9 + Math.sin(e * 1.2 + i * 0.4) * 0.15);
            });
          },
        },
      ];
    });
  }

  // ── Contact page — floating envelope ─────────────────────────────────
  function initContactScene() {
    createMiniScene("contact-canvas", (scene) => {
      const group = new THREE.Group();
      scene.add(group);

      // Envelope body
      const body = new THREE.Mesh(
        new THREE.BoxGeometry(2, 1.3, 0.08),
        new THREE.MeshToonMaterial({ color: 0x1a1a2e }),
      );
      group.add(body);

      // Envelope flap (triangular look with 2 triangles)
      const flapShape = new THREE.Shape();
      flapShape.moveTo(-1, 0.65);
      flapShape.lineTo(0, 0);
      flapShape.lineTo(1, 0.65);
      flapShape.lineTo(-1, 0.65);
      const flap = new THREE.Mesh(
        new THREE.ShapeGeometry(flapShape),
        new THREE.MeshToonMaterial({ color: 0x8b5cf6, side: THREE.DoubleSide }),
      );
      flap.position.z = 0.045;
      group.add(flap);

      // Bottom V fold
      const foldShape = new THREE.Shape();
      foldShape.moveTo(-1, -0.65);
      foldShape.lineTo(0, -0.1);
      foldShape.lineTo(1, -0.65);
      foldShape.lineTo(-1, -0.65);
      const fold = new THREE.Mesh(
        new THREE.ShapeGeometry(foldShape),
        new THREE.MeshToonMaterial({ color: 0x06b6d4, side: THREE.DoubleSide }),
      );
      fold.position.z = 0.045;
      group.add(fold);

      // Left fold
      const lfShape = new THREE.Shape();
      lfShape.moveTo(-1, -0.65);
      lfShape.lineTo(-0.2, 0);
      lfShape.lineTo(-1, 0.65);
      const lf = new THREE.Mesh(
        new THREE.ShapeGeometry(lfShape),
        new THREE.MeshToonMaterial({ color: 0x12121a, side: THREE.DoubleSide }),
      );
      lf.position.z = 0.044;
      group.add(lf);

      // Right fold
      const rfShape = new THREE.Shape();
      rfShape.moveTo(1, -0.65);
      rfShape.lineTo(0.2, 0);
      rfShape.lineTo(1, 0.65);
      const rf = new THREE.Mesh(
        new THREE.ShapeGeometry(rfShape),
        new THREE.MeshToonMaterial({ color: 0x12121a, side: THREE.DoubleSide }),
      );
      rf.position.z = 0.044;
      group.add(rf);

      // Border
      const border = new THREE.LineSegments(
        new THREE.EdgesGeometry(new THREE.BoxGeometry(2, 1.3, 0.08)),
        new THREE.LineBasicMaterial({
          color: 0x8b5cf6,
          transparent: true,
          opacity: 0.5,
        }),
      );
      group.add(border);

      // Orbiting sparkle particles
      const sparkleMat = new THREE.PointsMaterial({
        color: 0x8b5cf6,
        size: 0.06,
        transparent: true,
        opacity: 0.7,
      });
      const sparkleGeo = new THREE.BufferGeometry();
      const sp = new Float32Array(30 * 3);
      for (let i = 0; i < 30; i++) {
        const a = (i / 30) * Math.PI * 2;
        const r = 1.5 + Math.random() * 0.5;
        sp[i * 3] = Math.cos(a) * r;
        sp[i * 3 + 1] = (Math.random() - 0.5) * 1.5;
        sp[i * 3 + 2] = Math.sin(a) * r;
      }
      sparkleGeo.setAttribute("position", new THREE.BufferAttribute(sp, 3));
      const sparkles = new THREE.Points(sparkleGeo, sparkleMat);
      scene.add(sparkles);

      return [
        {
          tick(e) {
            group.rotation.y = Math.sin(e * 0.5) * 0.4;
            group.rotation.x = Math.sin(e * 0.3) * 0.15;
            group.position.y = Math.sin(e * 0.8) * 0.15;
            sparkles.rotation.y = e * 0.3;
            sparkleMat.opacity = 0.5 + Math.sin(e * 2) * 0.2;
          },
        },
      ];
    });
  }

  // ── Init all detected canvases ────────────────────────────────────────
  function init() {
    if (document.getElementById("about-canvas")) initAboutOrb();
    if (document.getElementById("skills-canvas")) initSkillsGlobe();
    if (document.getElementById("contact-canvas")) initContactScene();
  }

  return { init };
})();
