/* ═══════════════════════════════════════════════════════════════════════════
   CUSTOM ANIME CURSOR
   ═══════════════════════════════════════════════════════════════════════════ */

const AnimeCursor = (() => {
  // Skip on touch devices
  if (window.matchMedia("(pointer: coarse)").matches) return { init: () => {} };

  let cursorEl, dotEl, trailEl;
  let mx = -100,
    my = -100;
  let cx = -100,
    cy = -100;
  let state = "default";

  const LERP = 0.14;

  function buildDOM() {
    const style = document.createElement("style");
    style.textContent = `
            #anime-cursor-wrap { position:fixed; top:0; left:0; pointer-events:none; z-index:99999; }
            #anime-cursor {
                width:36px; height:36px; border-radius:50%;
                border:1.5px solid rgba(139,92,246,0.8);
                position:absolute; transform:translate(-50%,-50%);
                transition:width 0.25s, height 0.25s, border-color 0.25s, background 0.25s, border-radius 0.25s;
                will-change:transform;
            }
            #anime-cursor-dot {
                width:5px; height:5px; border-radius:50%;
                background:#8b5cf6;
                position:absolute; transform:translate(-50%,-50%);
                transition:transform 0.1s, width 0.2s, height 0.2s;
                will-change:transform;
            }
            #anime-cursor-trail {
                position:absolute; transform:translate(-50%,-50%);
                width:60px; height:60px; border-radius:50%;
                background:radial-gradient(circle,rgba(139,92,246,0.12),transparent 70%);
                will-change:transform;
                pointer-events:none;
            }
            body { cursor:none !important; }
            a,button,[role="button"],label { cursor:none !important; }

            #anime-cursor.hover-link {
                width:56px; height:56px;
                border-color:rgba(139,92,246,1);
                background:rgba(139,92,246,0.1);
            }
            #anime-cursor.hover-btn {
                width:64px; height:64px;
                border-color:rgba(6,182,212,1);
                background:rgba(6,182,212,0.1);
                border-radius:12px;
            }
            #anime-cursor.hover-img {
                width:56px; height:56px;
                border-color:rgba(244,63,94,0.9);
                background:rgba(244,63,94,0.08);
            }
            #anime-cursor.clicking {
                transform:translate(-50%,-50%) scale(0.75);
                background:rgba(139,92,246,0.25);
            }
            .cursor-label {
                position:absolute; left:50%; top:50%;
                transform:translate(-50%,-50%);
                font-size:9px; font-weight:700; letter-spacing:0.05em;
                color:white; text-transform:uppercase; white-space:nowrap;
                opacity:0; transition:opacity 0.2s;
            }
            #anime-cursor.hover-btn .cursor-label,
            #anime-cursor.hover-img .cursor-label { opacity:1; }
        `;
    document.head.appendChild(style);

    const wrap = document.createElement("div");
    wrap.id = "anime-cursor-wrap";
    trailEl = document.createElement("div");
    trailEl.id = "anime-cursor-trail";
    cursorEl = document.createElement("div");
    cursorEl.id = "anime-cursor";
    dotEl = document.createElement("div");
    dotEl.id = "anime-cursor-dot";
    const label = document.createElement("span");
    label.className = "cursor-label";
    label.textContent = "View";

    cursorEl.appendChild(label);
    wrap.appendChild(trailEl);
    wrap.appendChild(cursorEl);
    wrap.appendChild(dotEl);
    document.body.appendChild(wrap);
  }

  function lerp(a, b, t) {
    return a + (b - a) * t;
  }

  function animate() {
    cx = lerp(cx, mx, LERP);
    cy = lerp(cy, my, LERP);

    const tx = lerp(parseFloat(trailEl.style.left || "0"), mx, 0.06);
    const ty = lerp(parseFloat(trailEl.style.top || "0"), my, 0.06);

    cursorEl.style.left = cx + "px";
    cursorEl.style.top = cy + "px";
    dotEl.style.left = mx + "px";
    dotEl.style.top = my + "px";
    trailEl.style.left = tx + "px";
    trailEl.style.top = ty + "px";

    requestAnimationFrame(animate);
  }

  function setState(newState) {
    if (newState === state) return;
    state = newState;
    cursorEl.className = newState !== "default" ? newState : "";
  }

  function spawnClickBurst(x, y) {
    const colors = ["#8b5cf6", "#06b6d4", "#f43f5e", "#eab308"];
    for (let i = 0; i < 7; i++) {
      const span = document.createElement("span");
      span.classList.add("sparkle");
      const angle = (i / 7) * Math.PI * 2;
      const radius = 30 + Math.random() * 20;
      span.style.cssText = `
                left:${x}px; top:${y}px;
                --tx:${Math.cos(angle) * radius}px;
                --ty:${Math.sin(angle) * radius}px;
                background:${colors[i % colors.length]};
            `;
      document.body.appendChild(span);
      setTimeout(() => span.remove(), 700);
    }
  }

  function init() {
    buildDOM();
    animate();

    window.addEventListener("mousemove", (e) => {
      mx = e.clientX;
      my = e.clientY;
    });

    // State detection
    document.addEventListener("mouseover", (e) => {
      const t = e.target;
      if (t.closest('button, .btn-anime, .btn-outline, [role="button"]'))
        return setState("hover-btn");
      if (t.closest("a")) return setState("hover-link");
      if (t.closest("img")) return setState("hover-img");
      setState("default");
    });

    document.addEventListener("mousedown", () =>
      cursorEl.classList.add("clicking"),
    );
    document.addEventListener("mouseup", () =>
      cursorEl.classList.remove("clicking"),
    );

    document.addEventListener("click", (e) => {
      spawnClickBurst(e.clientX, e.clientY);
    });

    // Onomatopoeia on interactive clicks
    const onomaWords = ["BAM!", "POW!", "ZAP!", "ZOOM!", "FLASH!"];
    let onomaIdx = 0;
    document.querySelectorAll(".btn-anime, .btn-outline").forEach((btn) => {
      btn.addEventListener("click", (e) => {
        const word = document.createElement("span");
        word.className = "onomatopoeia";
        word.textContent = onomaWords[onomaIdx++ % onomaWords.length];
        word.style.left = e.clientX - 30 + "px";
        word.style.top = e.clientY - 40 + "px";
        document.body.appendChild(word);
        setTimeout(() => word.remove(), 900);
      });
    });
  }

  return { init };
})();

AnimeCursor.init();
