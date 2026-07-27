/* ═══════════════════════════════════════════════════════════════════════════
   LOCOMOTIVE SCROLL — Smooth scroll initialisation + GSAP proxy
   ═══════════════════════════════════════════════════════════════════════════ */

const LocoScroll = (() => {
  let scroll = null;

  function init() {
    const container = document.querySelector("[data-scroll-container]");
    if (!container || typeof LocomotiveScroll === "undefined") {
      initFallback();
      return;
    }

    scroll = new LocomotiveScroll({
      el: container,
      smooth: true,
      multiplier: 0.85,
      lerp: 0.08,
      smartphone: { smooth: true, multiplier: 0.6 },
      tablet: { smooth: true, multiplier: 0.7 },
      reloadOnContextChange: true,
    });

    // ── GSAP ScrollTrigger proxy ──────────────────────────────────────
    if (typeof ScrollTrigger !== "undefined") {
      ScrollTrigger.scrollerProxy(container, {
        scrollTop(value) {
          return arguments.length
            ? scroll.scrollTo(value, { duration: 0, disableLerp: true })
            : scroll.scroll.instance.scroll.y;
        },
        getBoundingClientRect() {
          return {
            top: 0,
            left: 0,
            width: window.innerWidth,
            height: window.innerHeight,
          };
        },
        pinType: container.style.transform ? "transform" : "fixed",
      });

      scroll.on("scroll", ScrollTrigger.update);
      ScrollTrigger.addEventListener("refresh", () => scroll.update());
      ScrollTrigger.refresh();
    }

    // ── Expose to window for other scripts ────────────────────────────
    window.locoScroll = scroll;

    // ── Navbar scroll state ───────────────────────────────────────────
    scroll.on("scroll", ({ scroll: { y } }) => {
      const nav = document.getElementById("navbar");
      if (nav) nav.classList.toggle("scrolled", y > 60);

      const rp = document.getElementById("read-progress");
      if (rp) {
        const doc = document.documentElement;
        const pct = (y / (container.scrollHeight - window.innerHeight)) * 100;
        rp.style.width = Math.min(pct, 100) + "%";
      }
    });

    // ── Re-init on anchor links ───────────────────────────────────────
    document.querySelectorAll('a[href^="#"]').forEach((link) => {
      link.addEventListener("click", (e) => {
        e.preventDefault();
        const target = document.querySelector(link.getAttribute("href"));
        if (target) scroll.scrollTo(target);
      });
    });

    // ── Update on image load ──────────────────────────────────────────
    window.addEventListener("load", () => {
      scroll.update();
      setTimeout(() => scroll.update(), 500);
    });
  }

  // Fallback: native smooth scroll when LocomotiveScroll unavailable
  function initFallback() {
    window.addEventListener(
      "scroll",
      () => {
        const nav = document.getElementById("navbar");
        if (nav) nav.classList.toggle("scrolled", window.scrollY > 60);
      },
      { passive: true },
    );
  }

  function update() {
    scroll?.update();
  }
  function destroy() {
    scroll?.destroy();
  }
  function scrollTo(target, opts) {
    scroll?.scrollTo(target, opts);
  }

  return { init, update, destroy, scrollTo };
})();

// Init after preloader done (or immediately if skipped)
window.addEventListener("preloader:done", () => LocoScroll.init());
if (sessionStorage.getItem("visited")) LocoScroll.init();
