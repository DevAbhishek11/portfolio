/* ═══════════════════════════════════════════════════════════════════════════
   FLOATING WIDGET MANAGER
   Keeps fixed-position widgets (AI chat bubble, video mini-player, future
   ones) stacked without overlapping each other, the navbar, or the footer.
   Widgets are draggable within a safe zone and remember where they were left.
   ═══════════════════════════════════════════════════════════════════════════ */
const FloatingWidgets = (() => {
  const MARGIN = 20;
  const STACK_GAP = 14;
  const widgets = new Map(); // id -> state

  function navbarSafeTop() {
    const nav = document.getElementById("navbar");
    if (!nav) return MARGIN;
    const rect = nav.getBoundingClientRect();
    return Math.max(MARGIN, rect.bottom + MARGIN);
  }

  function footerSafeBottom() {
    const footer = document.getElementById("site-footer");
    if (!footer) return MARGIN;
    const rect = footer.getBoundingClientRect();
    // Distance from the viewport bottom up to the footer's top edge, once
    // the footer has scrolled into view — 0 (i.e. just MARGIN) otherwise.
    // Clamped so a footer whose top has scrolled far above the viewport
    // (deep scroll, or a very tall footer) can never push the widget
    // beyond the visible viewport entirely.
    const overlap = Math.max(0, window.innerHeight - rect.top);
    const maxOverlap = Math.max(0, window.innerHeight - MARGIN * 2);
    return MARGIN + Math.min(overlap, maxOverlap);
  }

  function move(el, props) {
    if (typeof gsap !== "undefined") {
      gsap.to(el, { ...props, duration: 0.3, ease: "power2.out", overwrite: "auto" });
    } else {
      Object.entries(props).forEach(([k, v]) => {
        el.style[k] = typeof v === "number" ? v + "px" : v;
      });
    }
  }

  function clamp(state) {
    const rect = state.el.getBoundingClientRect();
    const minX = MARGIN;
    const maxX = window.innerWidth - rect.width - MARGIN;
    const minY = navbarSafeTop();
    const maxY = window.innerHeight - footerSafeBottom() - rect.height;
    state.x = Math.min(Math.max(state.x, minX), Math.max(minX, maxX));
    state.y = Math.min(Math.max(state.y, minY), Math.max(minY, maxY));
  }

  function layout() {
    const stacks = {};
    widgets.forEach((w) => {
      if (w.userPlaced) {
        // Re-clamp a user-dragged widget so it never ends up under the
        // navbar/footer after a resize, but leave its chosen spot alone.
        clamp(w);
        move(w.el, { left: w.x, top: w.y, right: "auto", bottom: "auto" });
        return;
      }
      (stacks[w.corner] ||= []).push(w);
    });

    Object.values(stacks).forEach((list) => {
      list.sort((a, b) => a.order - b.order);
      let offset = 0;
      list.forEach((w) => {
        const rect = w.el.getBoundingClientRect();
        const fromRight = w.corner.includes("right");
        const fromTop = w.corner.includes("top");
        const sideMargin = MARGIN;
        const bandStart = fromTop ? navbarSafeTop() : footerSafeBottom();

        // However bandStart/offset were computed, never let a widget's
        // resting edge fall outside the viewport — last line of defense
        // regardless of how many widgets are stacked or how the page has
        // scrolled.
        const maxBand = Math.max(MARGIN, window.innerHeight - rect.height - MARGIN);
        const band = Math.min(bandStart + offset, maxBand);

        const props = { left: "auto", right: "auto", top: "auto", bottom: "auto" };
        props[fromRight ? "right" : "left"] = sideMargin;
        if (fromTop) {
          props.top = band;
        } else {
          props.bottom = band;
        }

        move(w.el, props);
        offset += rect.height + STACK_GAP;
      });
    });
  }

  const scheduleLayout = (() => {
    let raf = null;
    return () => {
      if (raf) return;
      raf = requestAnimationFrame(() => {
        raf = null;
        layout();
      });
    };
  })();

  function makeDraggable(state) {
    const { el, id } = state;
    let dragging = false;
    let startX = 0, startY = 0, originX = 0, originY = 0;
    const handle = el.querySelector("[data-fw-handle]") || el;

    handle.style.touchAction = "none";
    handle.addEventListener("pointerdown", (e) => {
      // Ignore drags starting on interactive controls inside the handle.
      if (e.target.closest("button,a,input,textarea,select")) return;
      dragging = true;
      const rect = el.getBoundingClientRect();
      startX = e.clientX;
      startY = e.clientY;
      originX = rect.left;
      originY = rect.top;
      handle.setPointerCapture(e.pointerId);
    });

    handle.addEventListener("pointermove", (e) => {
      if (!dragging) return;
      state.userPlaced = true;
      state.x = originX + (e.clientX - startX);
      state.y = originY + (e.clientY - startY);
      clamp(state);
      el.style.transition = "none";
      el.style.left = state.x + "px";
      el.style.top = state.y + "px";
      el.style.right = "auto";
      el.style.bottom = "auto";
    });

    function endDrag(e) {
      if (!dragging) return;
      dragging = false;
      el.style.transition = "";
      if (state.userPlaced) {
        localStorage.setItem("fw-pos-" + id, JSON.stringify({ x: state.x, y: state.y }));
      }
    }

    handle.addEventListener("pointerup", endDrag);
    handle.addEventListener("pointercancel", endDrag);
  }

  /**
   * Register a floating widget.
   * @param {string} id - stable identifier (used for saved position).
   * @param {HTMLElement} el - the widget's outer fixed-position element.
   * @param {object} opts - { corner: 'bottom-right'|'bottom-left'|'top-right'|'top-left', order: number }
   */
  function register(id, el, opts = {}) {
    const state = {
      id,
      el,
      corner: opts.corner || "bottom-right",
      order: opts.order || 0,
      userPlaced: false,
      x: 0,
      y: 0,
    };

    el.style.position = "fixed";
    el.style.zIndex = 950;

    const saved = localStorage.getItem("fw-pos-" + id);
    if (saved) {
      try {
        const pos = JSON.parse(saved);
        if (typeof pos.x === "number" && typeof pos.y === "number") {
          state.userPlaced = true;
          state.x = pos.x;
          state.y = pos.y;
        }
      } catch (e) { /* ignore malformed saved position */ }
    }

    widgets.set(id, state);
    makeDraggable(state);

    if (typeof ResizeObserver !== "undefined") {
      new ResizeObserver(() => scheduleLayout()).observe(el);
    }

    scheduleLayout();

    return {
      relayout: scheduleLayout,
      resetPosition() {
        state.userPlaced = false;
        localStorage.removeItem("fw-pos-" + id);
        scheduleLayout();
      },
      destroy() {
        widgets.delete(id);
        scheduleLayout();
      },
    };
  }

  window.addEventListener("resize", scheduleLayout, { passive: true });
  window.addEventListener("scroll", scheduleLayout, { passive: true });

  return { register, relayout: scheduleLayout };
})();
