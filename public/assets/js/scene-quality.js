/* ═══════════════════════════════════════════════════════════════════════════
   SCENE-QUALITY.JS
   Adaptive quality: monitors FPS, degrades/upgrades renderer settings,
   pauses off-screen canvases, reduces particles on low-end devices.
   ═══════════════════════════════════════════════════════════════════════════ */

const SceneQuality = (() => {
  let fps = 60;
  let frames = 0;
  let lastCheck = performance.now();
  let tier = "high"; // 'high' | 'medium' | 'low'
  let applied = false;

  // ── FPS monitor ───────────────────────────────────────────────────────
  function monitorFPS() {
    frames++;
    const now = performance.now();
    if (now - lastCheck >= 2000) {
      fps = Math.round((frames * 1000) / (now - lastCheck));
      frames = 0;
      lastCheck = now;
      _adjustTier();
    }
    requestAnimationFrame(monitorFPS);
  }

  function _adjustTier() {
    const state = ThreeScene?.getState?.();
    if (!state?.renderer) return;

    if (fps < 24 && tier !== "low") {
      tier = "low";
      _applyTier(state);
    } else if (fps < 40 && tier === "high") {
      tier = "medium";
      _applyTier(state);
    } else if (fps > 55 && tier !== "high" && !applied) {
      tier = "high";
      _applyTier(state);
      applied = true; // only upgrade once to avoid oscillation
    }
  }

  function _applyTier(state) {
    const { renderer } = state;
    switch (tier) {
      case "low":
        renderer.setPixelRatio(1);
        renderer.shadowMap.enabled = false;
        // Tell particle system to reduce count
        window.dispatchEvent(new CustomEvent("quality:low"));
        console.info("[3D] Quality: LOW (fps=" + fps + ")");
        break;
      case "medium":
        renderer.setPixelRatio(Math.min(window.devicePixelRatio, 1.5));
        renderer.shadowMap.enabled = false;
        window.dispatchEvent(new CustomEvent("quality:medium"));
        console.info("[3D] Quality: MEDIUM (fps=" + fps + ")");
        break;
      case "high":
        renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
        renderer.shadowMap.enabled = true;
        console.info("[3D] Quality: HIGH (fps=" + fps + ")");
        break;
    }
  }

  // ── Intersection observer: pause off-screen canvases ──────────────────
  function observeCanvases() {
    const canvases = document.querySelectorAll("canvas");
    if (!("IntersectionObserver" in window)) return;

    const io = new IntersectionObserver(
      (entries) => {
        entries.forEach((e) => {
          // Store visibility on the canvas element
          e.target._visible = e.isIntersecting;
        });
      },
      { threshold: 0.01 },
    );

    canvases.forEach((c) => io.observe(c));
  }

  // ── Device capability detect ──────────────────────────────────────────
  function detectCapabilities() {
    const mobile = window.innerWidth < 768;
    const lowRAM =
      navigator.deviceMemory !== undefined && navigator.deviceMemory < 4;
    const lowCPU =
      navigator.hardwareConcurrency !== undefined &&
      navigator.hardwareConcurrency <= 2;
    const prefersReduced = window.matchMedia(
      "(prefers-reduced-motion: reduce)",
    ).matches;

    if (prefersReduced) {
      window.dispatchEvent(new CustomEvent("quality:reduced-motion"));
    }

    return {
      mobile,
      lowRAM,
      lowCPU,
      prefersReduced,
      tier: lowRAM || lowCPU ? "low" : "high",
    };
  }

  // ── Dispose helper (prevent memory leaks on SPA navigation) ───────────
  function disposeScene(scene) {
    if (!scene) return;
    scene.traverse((obj) => {
      if (obj.isMesh) {
        obj.geometry?.dispose();
        if (Array.isArray(obj.material)) {
          obj.material.forEach((m) => m.dispose());
        } else {
          obj.material?.dispose();
        }
      }
    });
  }

  function init() {
    const caps = detectCapabilities();
    observeCanvases();

    // Start FPS monitor after scene is set up
    setTimeout(monitorFPS, 3000);

    // Reduced motion: disable heavy animations
    if (caps.prefersReduced) {
      document.documentElement.style.setProperty("--anim-duration", "0.01s");
    }

    return caps;
  }

  return { init, disposeScene, detectCapabilities };
})();
