/* ═══════════════════════════════════════════════════════════════════════════
   PRELOADER — Anime-style loading screen
   ═══════════════════════════════════════════════════════════════════════════ */

const Preloader = (() => {
  const el = document.getElementById("preloader");
  const fill = document.getElementById("loader-fill");
  const pctEl = document.getElementById("loader-pct");
  const msgEl = document.getElementById("loader-msg");

  const messages = [
    "読み込み中…", // Loading…
    "データ処理中…", // Processing data…
    "もうすぐだよ…", // Almost there…
    "準備完了！", // Ready!
  ];

  let pct = 0;
  let msgIndex = 0;
  let done = false;

  function setMsg(i) {
    if (!msgEl) return;
    msgEl.style.opacity = 0;
    setTimeout(() => {
      msgEl.textContent = messages[i % messages.length];
      msgEl.style.opacity = 1;
    }, 200);
  }

  function tick() {
    if (done) return;
    const increment = Math.random() * 18 + 4;
    pct = Math.min(pct + increment, 95);

    if (fill) fill.style.width = pct + "%";
    if (pctEl) pctEl.textContent = Math.floor(pct) + "%";

    const newMsgIndex = Math.floor(pct / 30);
    if (newMsgIndex !== msgIndex) {
      msgIndex = newMsgIndex;
      setMsg(msgIndex);
    }
  }

  function finish() {
    done = true;
    pct = 100;
    if (fill) fill.style.width = "100%";
    if (pctEl) pctEl.textContent = "100%";
    setMsg(3);

    setTimeout(() => {
      if (!el) return;
      // Slice exit animation
      el.style.clipPath = "inset(0 0 100% 0)";
      el.style.transition = "clip-path 0.7s cubic-bezier(0.77,0,0.175,1)";
      setTimeout(() => {
        el.style.display = "none";
        document.body.classList.remove("preloading");
        window.dispatchEvent(new Event("preloader:done"));
      }, 700);
    }, 300);
  }

  function init() {
    if (sessionStorage.getItem("visited")) {
      if (el) el.style.display = "none";
      window.dispatchEvent(new Event("preloader:done"));
      return;
    }

    document.body.classList.add("preloading");
    if (el) el.style.display = "flex";
    setMsg(0);

    const timer = setInterval(tick, 90);

    window.addEventListener("load", () => {
      clearInterval(timer);
      // Give a tiny buffer so assets actually render
      setTimeout(() => {
        finish();
        sessionStorage.setItem("visited", "1");
      }, 200);
    });
  }

  return { init };
})();

Preloader.init();
