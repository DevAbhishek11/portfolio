/* ═══════════════════════════════════════════════════════════════════════════
   APP.JS — Main initialisation & coordination
   ═══════════════════════════════════════════════════════════════════════════ */

// ── Typewriter effect ─────────────────────────────────────────────────────
function initTypewriter() {
  document.querySelectorAll("[data-typewriter]").forEach((el) => {
    const words = el.dataset.typewriter.split("|");
    const delay = parseInt(el.dataset.twDelay || 80);
    const pause = parseInt(el.dataset.twPause || 1800);
    let wi = 0,
      ci = 0,
      deleting = false;

    // Add cursor
    const cursor = document.createElement("span");
    cursor.className = "typewriter-cursor";
    el.parentElement.appendChild(cursor);

    function tick() {
      const word = words[wi];
      const current = el.textContent;

      if (!deleting) {
        el.textContent = word.slice(0, ci + 1);
        ci++;
        if (ci === word.length) {
          deleting = true;
          setTimeout(tick, pause);
          return;
        }
      } else {
        el.textContent = word.slice(0, ci - 1);
        ci--;
        if (ci === 0) {
          deleting = false;
          wi = (wi + 1) % words.length;
        }
      }
      setTimeout(tick, deleting ? delay / 2 : delay);
    }
    tick();
  });
}

// ── Smooth anchor links ───────────────────────────────────────────────────
function initSmoothAnchors() {
  document.querySelectorAll('a[href^="#"]').forEach((link) => {
    link.addEventListener("click", (e) => {
      const target = document.querySelector(link.getAttribute("href"));
      if (!target) return;
      e.preventDefault();
      if (window.locoScroll) {
        window.locoScroll.scrollTo(target);
      } else {
        target.scrollIntoView({ behavior: "smooth" });
      }
    });
  });
}

// ── Lazy images ───────────────────────────────────────────────────────────
function initLazyImages() {
  if (!("IntersectionObserver" in window)) return;
  const io = new IntersectionObserver(
    (entries) => {
      entries.forEach((e) => {
        if (e.isIntersecting) {
          const img = e.target;
          if (img.dataset.src) {
            img.src = img.dataset.src;
            img.removeAttribute("data-src");
          }
          io.unobserve(img);
        }
      });
    },
    { rootMargin: "200px" },
  );

  document.querySelectorAll("img[data-src]").forEach((img) => io.observe(img));
}

// ── Copy to clipboard ─────────────────────────────────────────────────────
function initClipboard() {
  document.querySelectorAll("[data-copy]").forEach((btn) => {
    btn.addEventListener("click", async () => {
      const text = btn.dataset.copy;
      try {
        await navigator.clipboard.writeText(text);
        const orig = btn.textContent;
        btn.textContent = "Copied!";
        btn.style.color = "#4ade80";
        setTimeout(() => {
          btn.textContent = orig;
          btn.style.color = "";
        }, 2000);
      } catch {}
    });
  });
}

// ── Section highlight observer ────────────────────────────────────────────
function initActiveNav() {
  const sections = document.querySelectorAll("section[id]");
  if (!sections.length) return;

  const io = new IntersectionObserver(
    (entries) => {
      entries.forEach((e) => {
        if (e.isIntersecting) {
          document.querySelectorAll(".nav-link").forEach((link) => {
            link.classList.toggle(
              "active",
              link.getAttribute("href") === "#" + e.target.id,
            );
          });
        }
      });
    },
    { threshold: 0.4 },
  );

  sections.forEach((s) => io.observe(s));
}

// ── Page transition panels ────────────────────────────────────────────────
function initPageTransition() {
  // Inject panels
  const pt = document.createElement("div");
  pt.id = "page-transition";
  for (let i = 0; i < 5; i++) {
    const panel = document.createElement("div");
    panel.className = "pt-panel";
    pt.appendChild(panel);
  }
  document.body.appendChild(pt);

  // Exit animation on navigation
  document.querySelectorAll("a[href]").forEach((link) => {
    const href = link.getAttribute("href");
    if (
      !href ||
      href.startsWith("#") ||
      href.startsWith("http") ||
      href.startsWith("mailto") ||
      href.startsWith("tel") ||
      link.target === "_blank"
    )
      return;

    link.addEventListener("click", (e) => {
      e.preventDefault();
      const panels = pt.querySelectorAll(".pt-panel");

      if (typeof gsap !== "undefined") {
        gsap.fromTo(
          panels,
          { scaleY: 0, transformOrigin: "bottom" },
          {
            scaleY: 1,
            duration: 0.5,
            stagger: 0.06,
            ease: "power3.inOut",
            onComplete: () => {
              window.location = href;
            },
          },
        );
      } else {
        window.location = href;
      }
    });
  });
}

// ── Boot ──────────────────────────────────────────────────────────────────
document.addEventListener("DOMContentLoaded", () => {
  initTypewriter();
  initSmoothAnchors();
  initLazyImages();
  initClipboard();
  initActiveNav();
  initPageTransition();
});
