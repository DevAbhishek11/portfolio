/* ═══════════════════════════════════════════════════════════════════════════
   THREE-BOOT.JS — Boots the 3D system in the correct order
   ═══════════════════════════════════════════════════════════════════════════ */

(function boot() {
  // Only run on the hero canvas page
  const heroCanvas = document.getElementById("hero-canvas");
  if (!heroCanvas || typeof THREE === "undefined") {
    // Still try section scenes on other pages
    document.addEventListener("DOMContentLoaded", () =>
      SectionScenes?.init?.(),
    );
    return;
  }

  const caps = SceneQuality.init();

  function startScene() {
    // 1. Boot central scene
    ThreeScene.init("hero-canvas");

    // 2. Register modules (order matters — environment first, then objects, then character)
    ThreeScene.register(SceneEnvironment);
    ThreeScene.register(FloatingObjects);
    if (!caps.mobile) {
      ThreeScene.register(AnimeCharacter);
    }

    // 3. Locomotive scroll → pass Y to scene state
    window.addEventListener("loco:scroll", (e) => {
      ThreeScene.getState().scroll.y = e.detail.y;
    });

    // Native scroll fallback
    window.addEventListener(
      "scroll",
      () => {
        ThreeScene.getState().scroll.y = window.scrollY;
      },
      { passive: true },
    );

    // 4. Section-specific mini-scenes
    SectionScenes.init();

    // 5. Click on character triggers wave
    heroCanvas.addEventListener("click", (e) => {
      AnimeCharacter?.triggerWave?.();
    });
  }

  // Wait for preloader to finish (smooth start)
  if (sessionStorage.getItem("visited")) {
    document.addEventListener("DOMContentLoaded", startScene);
  } else {
    window.addEventListener("preloader:done", startScene);
  }

  // ── Locomotive scroll bridge ──────────────────────────────────────────
  // Patch LocoScroll to emit loco:scroll events
  window.addEventListener("three:ready", () => {
    const origInit = LocoScroll?.init?.bind(LocoScroll);
    if (!origInit) return;

    // If loco already running, patch its scroll event
    setTimeout(() => {
      if (window.locoScroll) {
        window.locoScroll.on("scroll", ({ scroll }) => {
          window.dispatchEvent(
            new CustomEvent("loco:scroll", {
              detail: { y: scroll.y },
            }),
          );
        });
      }
    }, 1000);
  });
})();
