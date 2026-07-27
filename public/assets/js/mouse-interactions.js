/* ═══════════════════════════════════════════════════════════════════════════
   MOUSE / KEYBOARD / TILT INTERACTIONS
   ═══════════════════════════════════════════════════════════════════════════ */

const MouseInteractions = (() => {
  // ── 3D card tilt ─────────────────────────────────────────────────────
  function initTilt() {
    document
      .querySelectorAll(".tilt-card, .anime-card[data-tilt]")
      .forEach((card) => {
        card.addEventListener("mousemove", (e) => {
          const rect = card.getBoundingClientRect();
          const x = e.clientX - rect.left;
          const y = e.clientY - rect.top;
          const cx = rect.width / 2;
          const cy = rect.height / 2;
          const rotX = ((y - cy) / cy) * -10;
          const rotY = ((x - cx) / cx) * 10;

          card.style.transform = `
                    perspective(800px)
                    rotateX(${rotX}deg)
                    rotateY(${rotY}deg)
                    translateZ(8px)
                `;

          // Dynamic light reflection
          const shine =
            card.querySelector(".card-shine") ||
            (() => {
              const s = document.createElement("div");
              s.className = "card-shine";
              s.style.cssText =
                "position:absolute;inset:0;border-radius:inherit;pointer-events:none;z-index:10;";
              card.appendChild(s);
              return s;
            })();
          const pctX = (x / rect.width) * 100;
          const pctY = (y / rect.height) * 100;
          shine.style.background = `radial-gradient(circle at ${pctX}% ${pctY}%, rgba(255,255,255,0.08), transparent 60%)`;
        });

        card.addEventListener("mouseleave", () => {
          card.style.transform =
            "perspective(800px) rotateX(0) rotateY(0) translateZ(0)";
          card.style.transition = "transform 0.5s cubic-bezier(0.16,1,0.3,1)";
          const shine = card.querySelector(".card-shine");
          if (shine) shine.style.background = "none";
        });

        card.addEventListener("mouseenter", () => {
          card.style.transition = "none";
        });
      });
  }

  // ── Magnetic buttons ──────────────────────────────────────────────────
  function initMagnetic() {
    document.querySelectorAll(".magnetic, .btn-anime").forEach((btn) => {
      btn.addEventListener("mousemove", (e) => {
        const rect = btn.getBoundingClientRect();
        const x = e.clientX - (rect.left + rect.width / 2);
        const y = e.clientY - (rect.top + rect.height / 2);
        btn.style.transform = `translate(${x * 0.25}px, ${y * 0.25}px)`;
      });
      btn.addEventListener("mouseleave", () => {
        btn.style.transform = "";
        btn.style.transition = "transform 0.4s cubic-bezier(0.16,1,0.3,1)";
      });
      btn.addEventListener("mouseenter", () => {
        btn.style.transition = "none";
      });
    });
  }

  // ── Mouse parallax hero ───────────────────────────────────────────────
  function initHeroParallax() {
    const layers = document.querySelectorAll("[data-mouse-depth]");
    if (!layers.length) return;

    let mx = 0,
      my = 0;
    window.addEventListener("mousemove", (e) => {
      mx = e.clientX / window.innerWidth - 0.5;
      my = e.clientY / window.innerHeight - 0.5;
    });

    function tick() {
      layers.forEach((layer) => {
        const depth = parseFloat(layer.dataset.mouseDepth) || 1;
        const tx = mx * depth * 30;
        const ty = my * depth * 20;
        layer.style.transform = `translate(${tx}px, ${ty}px)`;
      });
      requestAnimationFrame(tick);
    }
    tick();
  }

  // ── Keyboard interactions ─────────────────────────────────────────────
  function initKeyboard() {
    const keys = new Set();

    // Konami code easter egg
    const konami = [
      "ArrowUp",
      "ArrowUp",
      "ArrowDown",
      "ArrowDown",
      "ArrowLeft",
      "ArrowRight",
      "ArrowLeft",
      "ArrowRight",
      "b",
      "a",
    ];
    let konamiIdx = 0;

    window.addEventListener("keydown", (e) => {
      keys.add(e.key);

      // Konami code
      if (e.key === konami[konamiIdx]) {
        konamiIdx++;
        if (konamiIdx === konami.length) {
          konamiIdx = 0;
          triggerKonami();
        }
      } else {
        konamiIdx = 0;
      }

      // Arrow key scene tilt (dispatched to Three.js via event)
      if (["ArrowLeft", "ArrowRight", "ArrowUp", "ArrowDown"].includes(e.key)) {
        window.dispatchEvent(
          new CustomEvent("scene:tilt", { detail: { key: e.key } }),
        );
      }

      // Space — scene pulse
      if (
        e.key === " " &&
        !["INPUT", "TEXTAREA"].includes(document.activeElement.tagName)
      ) {
        e.preventDefault();
        window.dispatchEvent(new CustomEvent("scene:pulse"));
      }
    });

    window.addEventListener("keyup", (e) => keys.delete(e.key));
  }

  function triggerKonami() {
    const msg = document.createElement("div");
    msg.style.cssText = `
            position:fixed; top:50%; left:50%; transform:translate(-50%,-50%);
            z-index:99999; text-align:center; pointer-events:none;
        `;
    msg.innerHTML = `
            <div style="font-family:'Playfair Display',serif;font-size:3rem;font-weight:900;
                        background:linear-gradient(135deg,#8b5cf6,#06b6d4,#f43f5e);
                        -webkit-background-clip:text;-webkit-text-fill-color:transparent;
                        animation:onoma-pop 2s ease forwards;">
                🎉 コナミコード！
            </div>
            <p style="color:#a78bfa;font-size:1rem;margin-top:0.5rem;">Konami Code Activated!</p>
        `;
    document.body.appendChild(msg);
    // Screen flash
    const flash = document.createElement("div");
    flash.className = "impact-flash";
    document.body.appendChild(flash);
    setTimeout(() => {
      flash.remove();
      msg.remove();
    }, 2500);

    // Burst particles everywhere
    for (let i = 0; i < 30; i++) {
      setTimeout(() => {
        const s = document.createElement("span");
        s.className = "sparkle";
        const colors = ["#8b5cf6", "#06b6d4", "#f43f5e", "#eab308", "#22c55e"];
        const angle = Math.random() * Math.PI * 2;
        const r = 80 + Math.random() * 200;
        s.style.cssText = `
                    left:${window.innerWidth / 2}px;
                    top:${window.innerHeight / 2}px;
                    --tx:${Math.cos(angle) * r}px;
                    --ty:${Math.sin(angle) * r}px;
                    background:${colors[Math.floor(Math.random() * colors.length)]};
                    width:${6 + Math.random() * 8}px;
                    height:${6 + Math.random() * 8}px;
                `;
        document.body.appendChild(s);
        setTimeout(() => s.remove(), 700);
      }, i * 50);
    }
  }

  // ── Hover ripple on images ────────────────────────────────────────────
  function initImageHover() {
    document
      .querySelectorAll(".anime-card img, [data-hover-ripple]")
      .forEach((img) => {
        img.parentElement.addEventListener("mouseenter", () => {
          img.style.transform = "scale(1.05)";
          img.style.transition = "transform 0.5s cubic-bezier(0.16,1,0.3,1)";
        });
        img.parentElement.addEventListener("mouseleave", () => {
          img.style.transform = "scale(1)";
        });
      });
  }

  // ── Scroll velocity effect ─────────────────────────────────────────────
  function initScrollVelocity() {
    let lastY = 0;
    let velocity = 0;

    window.addEventListener(
      "scroll",
      () => {
        velocity = window.scrollY - lastY;
        lastY = window.scrollY;

        // Dispatch velocity to Three.js
        window.dispatchEvent(
          new CustomEvent("scroll:velocity", { detail: { v: velocity } }),
        );
      },
      { passive: true },
    );
  }

  function init() {
    initTilt();
    initMagnetic();
    initHeroParallax();
    initKeyboard();
    initImageHover();
    initScrollVelocity();
  }

  return { init };
})();

MouseInteractions.init();
