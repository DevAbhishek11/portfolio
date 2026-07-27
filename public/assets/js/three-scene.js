/* ═══════════════════════════════════════════════════════════════════════════
   THREE-SCENE.JS — Central 3D scene manager
   Coordinates: renderer, camera, lights, resize, RAF loop, visibility
   ═══════════════════════════════════════════════════════════════════════════ */

const ThreeScene = (() => {
  // ── State ─────────────────────────────────────────────────────────────
  const state = {
    renderer: null,
    scene: null,
    camera: null,
    canvas: null,
    clock: null,
    modules: [], // registered sub-modules
    paused: false,
    mobile: window.innerWidth < 768,
    pixelRatio: Math.min(window.devicePixelRatio, 2),
    mouse: { x: 0, y: 0, nx: 0, ny: 0 }, // raw + normalised
    scroll: { y: 0, velocity: 0 },
    size: { w: 0, h: 0 },
  };

  // ── Init ──────────────────────────────────────────────────────────────
  function init(canvasId = "hero-canvas") {
    state.canvas = document.getElementById(canvasId);
    if (!state.canvas || typeof THREE === "undefined") return;

    _buildRenderer();
    _buildScene();
    _buildCamera();
    _buildLights();
    _bindEvents();
    _loop();

    // Signal ready to sub-modules
    window.dispatchEvent(new CustomEvent("three:ready", { detail: state }));
  }

  // ── Renderer ──────────────────────────────────────────────────────────
  function _buildRenderer() {
    const { canvas } = state;
    state.renderer = new THREE.WebGLRenderer({
      canvas,
      alpha: true,
      antialias: !state.mobile,
      powerPreference: "high-performance",
    });
    state.renderer.setPixelRatio(state.mobile ? 1 : state.pixelRatio);
    state.renderer.shadowMap.enabled = !state.mobile;
    state.renderer.shadowMap.type = THREE.PCFSoftShadowMap;
    state.renderer.toneMapping = THREE.ACESFilmicToneMapping;
    state.renderer.toneMappingExposure = 1.1;
    _setSize();
  }

  function _setSize() {
    const { canvas, renderer, camera } = state;
    if (!canvas) return;

    // Use the canvas's parent (the section) for dimensions
    const parent = canvas.parentElement;
    const w = parent ? parent.clientWidth : window.innerWidth;
    const h = parent ? parent.clientHeight : window.innerHeight;

    state.size = { w, h };
    renderer.setSize(w, h, false);

    // Also set canvas style dimensions explicitly
    canvas.style.width = w + "px";
    canvas.style.height = h + "px";

    if (camera) {
      camera.aspect = w / h;
      camera.updateProjectionMatrix();
    }
  }

  // ── Scene ─────────────────────────────────────────────────────────────
  function _buildScene() {
    state.scene = new THREE.Scene();
    state.clock = new THREE.Clock();
  }

  // ── Camera ────────────────────────────────────────────────────────────
  function _buildCamera() {
    const { w, h } = state.size;
    state.camera = new THREE.PerspectiveCamera(60, w / h, 0.1, 200);
    state.camera.position.set(0, 0, 7);
  }

  // ── Lights ────────────────────────────────────────────────────────────
  function _buildLights() {
    const { scene } = state;

    // Ambient
    scene.add(new THREE.AmbientLight(0xffffff, 0.4));

    // Key light (purple)
    const key = new THREE.DirectionalLight(0x8b5cf6, 1.8);
    key.position.set(5, 8, 5);
    key.castShadow = true;
    key.shadow.mapSize.set(1024, 1024);
    key.name = "keyLight";
    scene.add(key);

    // Fill light (cyan)
    const fill = new THREE.PointLight(0x06b6d4, 1.2, 30);
    fill.position.set(-6, 2, 4);
    fill.name = "fillLight";
    scene.add(fill);

    // Rim light (pink)
    const rim = new THREE.PointLight(0xf43f5e, 0.8, 20);
    rim.position.set(0, -4, -4);
    rim.name = "rimLight";
    scene.add(rim);

    // Mouse-reactive light
    const mouse = new THREE.PointLight(0x8b5cf6, 0.6, 15);
    mouse.name = "mouseLight";
    scene.add(mouse);
    state.mouseLight = mouse;
  }

  // ── Events ────────────────────────────────────────────────────────────
  function _bindEvents() {
    // Mouse
    window.addEventListener("mousemove", (e) => {
      state.mouse.x = e.clientX;
      state.mouse.y = e.clientY;
      state.mouse.nx = (e.clientX / window.innerWidth - 0.5) * 2;
      state.mouse.ny = (e.clientY / window.innerHeight - 0.5) * 2;

      // Move mouse-reactive light
      if (state.mouseLight) {
        state.mouseLight.position.set(
          state.mouse.nx * 8,
          -state.mouse.ny * 6,
          4,
        );
      }
    });

    // Scroll velocity
    window.addEventListener("scroll:velocity", (e) => {
      state.scroll.velocity = e.detail.v;
    });

    // Locomotive scroll position
    window.addEventListener("loco:scroll", (e) => {
      state.scroll.y = e.detail.y;
    });

    // Keyboard scene tilt
    window.addEventListener("scene:tilt", (e) => {
      state.modules.forEach((m) => m.onKeyTilt?.(e.detail.key));
    });

    // Space pulse
    window.addEventListener("scene:pulse", () => {
      state.modules.forEach((m) => m.onPulse?.());
    });

    // Resize
    window.addEventListener(
      "resize",
      _debounce(() => {
        state.mobile = window.innerWidth < 768;
        _setSize();
        state.modules.forEach((m) => m.onResize?.(state));
      }, 200),
    );

    // Page visibility (pause when hidden)
    document.addEventListener("visibilitychange", () => {
      state.paused = document.hidden;
      if (!state.paused) state.clock.getDelta(); // reset delta
    });
  }

  // ── RAF Loop ──────────────────────────────────────────────────────────
  function _loop() {
    requestAnimationFrame(_loop);
    if (state.paused) return;

    const delta = state.clock.getDelta();
    const elapsed = state.clock.elapsedTime;

    // Update all modules
    state.modules.forEach((m) => m.update?.(elapsed, delta, state));

    state.renderer.render(state.scene, state.camera);
  }

  // ── Public API ────────────────────────────────────────────────────────
  function register(module) {
    state.modules.push(module);
    // Init immediately if scene already ready
    module.init?.(state);
  }

  function _debounce(fn, ms) {
    let t;
    return (...args) => {
      clearTimeout(t);
      t = setTimeout(() => fn(...args), ms);
    };
  }

  // Expose state read-only
  function getState() {
    return state;
  }

  return { init, register, getState };
})();
