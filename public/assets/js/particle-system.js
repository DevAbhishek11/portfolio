/* ═══════════════════════════════════════════════════════════════════════════
   PARTICLE SYSTEM — Anime sparkle & sakura effects
   ═══════════════════════════════════════════════════════════════════════════ */

const ParticleSystem = (() => {
  // ── Floating sparkle canvas ───────────────────────────────────────────
  function initSparkleCanvas() {
    const canvas = document.getElementById("sparkle-canvas");
    if (!canvas) return;

    const ctx = canvas.getContext("2d");
    let W, H;
    const count = window.innerWidth < 768 ? 60 : 140;
    const particles = [];
    let mouseX = -999,
      mouseY = -999;

    function resize() {
      W = canvas.width = window.innerWidth;
      H = canvas.height = window.innerHeight;
    }
    resize();
    window.addEventListener("resize", resize);

    class Particle {
      constructor() {
        this.reset();
      }
      reset() {
        this.x = Math.random() * W;
        this.y = Math.random() * H;
        this.size = Math.random() * 2.5 + 0.5;
        this.vx = (Math.random() - 0.5) * 0.4;
        this.vy = -(Math.random() * 0.5 + 0.1);
        this.alpha = Math.random() * 0.6 + 0.2;
        this.hue = [280, 195, 345][Math.floor(Math.random() * 3)]; // purple, cyan, pink
        this.flicker = Math.random() * Math.PI * 2;
      }
      update() {
        this.flicker += 0.05;
        this.x += this.vx;
        this.y += this.vy;

        // Mouse repulsion
        const dx = this.x - mouseX;
        const dy = this.y - mouseY;
        const dist = Math.sqrt(dx * dx + dy * dy);
        if (dist < 80) {
          this.x += (dx / dist) * 1.5;
          this.y += (dy / dist) * 1.5;
        }

        if (this.y < -10 || this.x < -10 || this.x > W + 10) this.reset();
        this.currentAlpha = this.alpha * (0.7 + 0.3 * Math.sin(this.flicker));
      }
      draw() {
        ctx.save();
        ctx.globalAlpha = this.currentAlpha;
        ctx.fillStyle = `hsl(${this.hue}, 85%, 70%)`;
        // Star shape
        ctx.beginPath();
        for (let i = 0; i < 4; i++) {
          const angle = (i / 4) * Math.PI * 2;
          const inner = this.size * 0.4;
          ctx.lineTo(
            this.x + Math.cos(angle) * this.size,
            this.y + Math.sin(angle) * this.size,
          );
          ctx.lineTo(
            this.x + Math.cos(angle + Math.PI / 4) * inner,
            this.y + Math.sin(angle + Math.PI / 4) * inner,
          );
        }
        ctx.closePath();
        ctx.fill();
        ctx.restore();
      }
    }

    for (let i = 0; i < count; i++) particles.push(new Particle());

    window.addEventListener("mousemove", (e) => {
      mouseX = e.clientX;
      mouseY = e.clientY;
    });

    function loop() {
      ctx.clearRect(0, 0, W, H);
      particles.forEach((p) => {
        p.update();
        p.draw();
      });
      requestAnimationFrame(loop);
    }
    loop();
  }

  // ── Sakura petals ─────────────────────────────────────────────────────
  function initSakura() {
    const canvas = document.getElementById("sakura-canvas");
    if (!canvas) return;

    const ctx = canvas.getContext("2d");
    let W, H;
    const petals = [];
    const MAX = window.innerWidth < 768 ? 15 : 30;

    function resize() {
      W = canvas.width = window.innerWidth;
      H = canvas.height = window.innerHeight;
    }
    resize();
    window.addEventListener("resize", resize);

    class Petal {
      constructor() {
        this.reset(true);
      }
      reset(init = false) {
        this.x = Math.random() * W;
        this.y = init ? Math.random() * H : -20;
        this.size = Math.random() * 6 + 4;
        this.vx = (Math.random() - 0.5) * 1.2;
        this.vy = Math.random() * 0.8 + 0.3;
        this.rot = Math.random() * Math.PI * 2;
        this.rotV = (Math.random() - 0.5) * 0.04;
        this.alpha = Math.random() * 0.5 + 0.3;
        this.sway = Math.random() * Math.PI * 2;
      }
      update() {
        this.sway += 0.02;
        this.x += this.vx + Math.sin(this.sway) * 0.5;
        this.y += this.vy;
        this.rot += this.rotV;
        if (this.y > H + 20) this.reset();
      }
      draw() {
        ctx.save();
        ctx.translate(this.x, this.y);
        ctx.rotate(this.rot);
        ctx.globalAlpha = this.alpha;

        // Petal shape
        ctx.beginPath();
        ctx.moveTo(0, -this.size);
        ctx.bezierCurveTo(
          this.size * 0.8,
          -this.size * 0.5,
          this.size,
          this.size * 0.5,
          0,
          this.size,
        );
        ctx.bezierCurveTo(
          -this.size,
          this.size * 0.5,
          -this.size * 0.8,
          -this.size * 0.5,
          0,
          -this.size,
        );
        ctx.fillStyle = "rgba(255, 180, 200, 0.8)";
        ctx.fill();
        ctx.strokeStyle = "rgba(255,140,170,0.4)";
        ctx.lineWidth = 0.5;
        ctx.stroke();
        ctx.restore();
      }
    }

    for (let i = 0; i < MAX; i++) petals.push(new Petal());

    function loop() {
      ctx.clearRect(0, 0, W, H);
      petals.forEach((p) => {
        p.update();
        p.draw();
      });
      requestAnimationFrame(loop);
    }
    loop();
  }

  // ── Click burst ───────────────────────────────────────────────────────
  function initClickBurst() {
    document.addEventListener("click", (e) => {
      if (e.target.closest("button, a, input, textarea")) return;
      burstAt(e.clientX, e.clientY, 5);
    });
  }

  function burstAt(x, y, count = 8) {
    const colors = [
      "#8b5cf6",
      "#06b6d4",
      "#f43f5e",
      "#eab308",
      "#22c55e",
      "#a78bfa",
    ];
    for (let i = 0; i < count; i++) {
      const dot = document.createElement("span");
      dot.className = "sparkle";
      const angle = (i / count) * Math.PI * 2 + Math.random() * 0.5;
      const r = 20 + Math.random() * 40;
      dot.style.cssText = `
                left:${x}px; top:${y}px;
                background:${colors[i % colors.length]};
                width:${4 + Math.random() * 4}px;
                height:${4 + Math.random() * 4}px;
                --tx:${Math.cos(angle) * r}px;
                --ty:${Math.sin(angle) * r}px;
            `;
      document.body.appendChild(dot);
      setTimeout(() => dot.remove(), 800);
    }
  }

  function init() {
    initSparkleCanvas();
    initSakura();
    initClickBurst();
  }

  return { init, burstAt };
})();

ParticleSystem.init();
