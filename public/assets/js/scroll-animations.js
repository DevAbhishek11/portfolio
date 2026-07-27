/* ═══════════════════════════════════════════════════════════════════════════
   GSAP SCROLL ANIMATIONS — All scroll-triggered effects
   ═══════════════════════════════════════════════════════════════════════════ */

const ScrollAnimations = (() => {
  function init() {
    if (typeof gsap === "undefined" || typeof ScrollTrigger === "undefined")
      return;

    gsap.registerPlugin(ScrollTrigger);

    const scroller =
      document.querySelector("[data-scroll-container]") || window;

    const scrollerConfig = {
      scroller,
      toggleActions: "play none none none",
    };

    // ── Helper: create ScrollTrigger with scroller ────────────────────
    function st(trigger, extra = {}) {
      return { ...scrollerConfig, trigger, ...extra };
    }

    // ── Hero heading: word-by-word (NOT char-by-char, preserves inner spans) ──
    const heroTitle = document.querySelector(".hero-title");
    if (heroTitle) {
      gsap.fromTo(
        heroTitle,
        { opacity: 0, y: 40 },
        {
          opacity: 1,
          y: 0,
          duration: 1,
          ease: "power3.out",
          delay: 0.4,
        },
      );
      // Animate the gradient span separately for extra pop
      const gradSpan = heroTitle.querySelector(".grad-text");
      if (gradSpan) {
        gsap.fromTo(
          gradSpan,
          { opacity: 0, y: 20 },
          {
            opacity: 1,
            y: 0,
            duration: 0.9,
            ease: "power3.out",
            delay: 0.7,
          },
        );
      }
    }

    // ── 2. Fade-up elements ───────────────────────────────────────────
    document.querySelectorAll('[data-anime="fade-up"]').forEach((el) => {
      gsap.fromTo(
        el,
        { opacity: 0, y: 60 },
        {
          opacity: 1,
          y: 0,
          duration: 0.85,
          ease: "power3.out",
          delay: parseFloat(el.dataset.delay || 0) / 1000,
          scrollTrigger: st(el),
        },
      );
    });

    // ── 3. Fade-left / fade-right ─────────────────────────────────────
    document.querySelectorAll('[data-anime="fade-left"]').forEach((el) => {
      gsap.fromTo(
        el,
        { opacity: 0, x: -70 },
        {
          opacity: 1,
          x: 0,
          duration: 0.9,
          ease: "power3.out",
          scrollTrigger: st(el),
        },
      );
    });
    document.querySelectorAll('[data-anime="fade-right"]').forEach((el) => {
      gsap.fromTo(
        el,
        { opacity: 0, x: 70 },
        {
          opacity: 1,
          x: 0,
          duration: 0.9,
          ease: "power3.out",
          scrollTrigger: st(el),
        },
      );
    });

    // ── 4. Scale up ───────────────────────────────────────────────────
    document.querySelectorAll('[data-anime="scale-up"]').forEach((el) => {
      gsap.fromTo(
        el,
        { opacity: 0, scale: 0.82 },
        {
          opacity: 1,
          scale: 1,
          duration: 0.8,
          ease: "back.out(1.4)",
          scrollTrigger: st(el),
        },
      );
    });

    // ── 5. Clip-path reveal (image wipe) ─────────────────────────────
    document.querySelectorAll('[data-anime="clip-left"]').forEach((el) => {
      gsap.fromTo(
        el,
        { clipPath: "inset(0 100% 0 0)" },
        {
          clipPath: "inset(0 0% 0 0)",
          duration: 1.1,
          ease: "power3.inOut",
          scrollTrigger: st(el),
        },
      );
    });
    document.querySelectorAll('[data-anime="clip-bottom"]').forEach((el) => {
      gsap.fromTo(
        el,
        { clipPath: "inset(100% 0 0 0)" },
        {
          clipPath: "inset(0% 0 0 0)",
          duration: 1,
          ease: "power3.out",
          scrollTrigger: st(el),
        },
      );
    });

    // ── 6. Blur in ────────────────────────────────────────────────────
    document.querySelectorAll('[data-anime="blur-in"]').forEach((el) => {
      gsap.fromTo(
        el,
        { opacity: 0, filter: "blur(14px)" },
        {
          opacity: 1,
          filter: "blur(0px)",
          duration: 1,
          ease: "power2.out",
          scrollTrigger: st(el),
        },
      );
    });

    // ── 7. Section headers — char-by-char (safe version) ──────────────────
    document.querySelectorAll('[data-split="chars"]').forEach((el) => {
      // Collect segments: plain text nodes and preserved HTML children
      const segments = [];
      el.childNodes.forEach((node) => {
        if (node.nodeType === Node.TEXT_NODE) {
          // Split text nodes into individual characters
          [...node.textContent].forEach((char) => {
            segments.push({
              type: "char",
              char: char === " " ? "\u00A0" : char,
            });
          });
        } else if (node.nodeType === Node.ELEMENT_NODE) {
          // Preserve the entire element (e.g. <span class="grad-text">)
          // but also split its text into chars
          const innerText = node.textContent;
          const className = node.className;
          const tagName = node.tagName.toLowerCase();
          [...innerText].forEach((char, i) => {
            segments.push({
              type: "html-char",
              char: char === " " ? "\u00A0" : char,
              className: className,
              tagName: tagName,
              isFirst: i === 0,
              isLast: i === innerText.length - 1,
            });
          });
        }
      });

      // Store accessible label
      el.setAttribute("aria-label", el.textContent);
      el.innerHTML = "";

      // Rebuild with wrapped chars
      segments.forEach((seg) => {
        const outer = document.createElement("span");
        const inner = document.createElement("span");
        outer.style.cssText =
          "display:inline-block;overflow:hidden;vertical-align:bottom;";
        inner.style.cssText = "display:inline-block;";
        inner.textContent = seg.char;

        // Apply the original element's class to inner span
        if (seg.type === "html-char" && seg.className) {
          inner.className = seg.className;
        }

        outer.appendChild(inner);
        el.appendChild(outer);
      });

      // Animate
      gsap.from(el.querySelectorAll("span > span"), {
        yPercent: 120,
        duration: 0.7,
        stagger: 0.025,
        ease: "power3.out",
        scrollTrigger: {
          scroller: document.querySelector("[data-scroll-container]") || window,
          trigger: el,
          start: "top 80%",
        },
      });
    });

    // ── 8. Stagger card grids ─────────────────────────────────────────
    document.querySelectorAll(".stagger-grid").forEach((grid) => {
      const cards = grid.querySelectorAll(":scope > *");
      gsap.fromTo(
        cards,
        { opacity: 0, y: 50 },
        {
          opacity: 1,
          y: 0,
          duration: 0.7,
          stagger: 0.1,
          ease: "power3.out",
          scrollTrigger: st(grid, { start: "top 75%" }),
        },
      );
    });

    // ── 9. Counter animation ──────────────────────────────────────────
    document.querySelectorAll("[data-counter]").forEach((el) => {
      const target = parseFloat(el.dataset.counter);
      const suffix = el.dataset.suffix || "";
      const obj = { val: 0 };
      gsap.to(obj, {
        val: target,
        duration: 2,
        ease: "power2.out",
        scrollTrigger: st(el),
        onUpdate: () => {
          el.textContent = Math.floor(obj.val) + suffix;
        },
      });
    });

    // ── 10. Horizontal scroll panels ─────────────────────────────────
    document.querySelectorAll("[data-h-scroll]").forEach((section) => {
      const track = section.querySelector(".h-scroll-track");
      if (!track) return;
      const items = track.querySelectorAll(":scope > *");
      const totalW = track.scrollWidth - section.offsetWidth;

      gsap.to(track, {
        x: -totalW,
        ease: "none",
        scrollTrigger: {
          ...scrollerConfig,
          trigger: section,
          start: "top top",
          end: () => `+=${totalW}`,
          scrub: 1,
          pin: true,
          anticipatePin: 1,
        },
      });
    });

    // ── 11. Parallax sections ─────────────────────────────────────────
    document.querySelectorAll("[data-parallax-speed]").forEach((el) => {
      const speed = parseFloat(el.dataset.parallaxSpeed) || 0.3;
      gsap.fromTo(
        el,
        { y: -80 * speed },
        {
          y: 80 * speed,
          ease: "none",
          scrollTrigger: {
            ...scrollerConfig,
            trigger: el,
            start: "top bottom",
            end: "bottom top",
            scrub: true,
          },
        },
      );
    });

    // ── 12. Skill bars ────────────────────────────────────────────────
    document.querySelectorAll(".skill-bar").forEach((bar) => {
      const fill = bar.querySelector(".skill-fill");
      const pct = bar.dataset.pct || "80";
      gsap.fromTo(
        fill,
        { width: "0%" },
        {
          width: pct + "%",
          duration: 1.4,
          ease: "power3.out",
          scrollTrigger: st(bar),
        },
      );
    });

    // ── 13. Floating orbs parallax ────────────────────────────────────
    document.querySelectorAll(".orb").forEach((orb, i) => {
      gsap.to(orb, {
        y: (i % 2 === 0 ? -1 : 1) * 60,
        ease: "none",
        scrollTrigger: {
          ...scrollerConfig,
          trigger: orb.parentElement,
          start: "top bottom",
          end: "bottom top",
          scrub: 2,
        },
      });
    });

    // ── 14. Anime cards 3D entrance ───────────────────────────────────
    document
      .querySelectorAll(".anime-card[data-3d-enter]")
      .forEach((card, i) => {
        gsap.fromTo(
          card,
          { opacity: 0, rotateX: 20, y: 50, transformPerspective: 800 },
          {
            opacity: 1,
            rotateX: 0,
            y: 0,
            duration: 0.9,
            delay: i * 0.08,
            ease: "power3.out",
            scrollTrigger: st(card, { start: "top 85%" }),
          },
        );
      });

    // ── 15. Timeline items alternate slide ────────────────────────────
    document.querySelectorAll(".timeline-item").forEach((item, i) => {
      gsap.fromTo(
        item,
        { opacity: 0, x: i % 2 === 0 ? -50 : 50 },
        {
          opacity: 1,
          x: 0,
          duration: 0.8,
          ease: "power3.out",
          scrollTrigger: st(item, { start: "top 80%" }),
        },
      );
    });

    // ── 16. Speed line burst on section enter ─────────────────────────
    document.querySelectorAll("[data-section-burst]").forEach((section) => {
      ScrollTrigger.create({
        ...scrollerConfig,
        trigger: section,
        start: "top 60%",
        once: true,
        onEnter: () => spawnSpeedLines(section),
      });
    });
  }

  // ── Speed lines visual burst ──────────────────────────────────────────
  function spawnSpeedLines(context) {
    const burst = document.createElement("div");
    burst.style.cssText = `
            position:absolute; inset:0; pointer-events:none; z-index:10;
            overflow:hidden; border-radius:inherit;
        `;
    const rect = context.getBoundingClientRect();
    for (let i = 0; i < 12; i++) {
      const line = document.createElement("div");
      const angle = (i / 12) * 360;
      const len = 100 + Math.random() * 150;
      line.style.cssText = `
                position:absolute;
                left:50%; top:50%;
                width:${len}px; height:1px;
                background:linear-gradient(90deg,rgba(139,92,246,0.6),transparent);
                transform-origin:0 50%;
                transform:rotate(${angle}deg);
                animation:line-fade 0.5s ease-out forwards;
            `;
      burst.appendChild(line);
    }
    if (!document.getElementById("speed-line-kf")) {
      const kf = document.createElement("style");
      kf.id = "speed-line-kf";
      kf.textContent =
        "@keyframes line-fade{from{opacity:0.8;transform-origin:0 50%;}to{opacity:0;width:0;transform-origin:0 50%;}}";
      document.head.appendChild(kf);
    }
    context.style.position = "relative";
    context.appendChild(burst);
    setTimeout(() => burst.remove(), 600);
  }

  return { init, spawnSpeedLines };
})();

window.addEventListener("preloader:done", () => {
  requestAnimationFrame(() => ScrollAnimations.init());
});
if (sessionStorage.getItem("visited")) {
  requestAnimationFrame(() => ScrollAnimations.init());
}
