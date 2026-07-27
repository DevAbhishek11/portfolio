/* ═══════════════════════════════════════════════════════════════════════════
   VIDEO MINI-PLAYER WIDGET — plugs into FloatingWidgets, pauses on close
   ═══════════════════════════════════════════════════════════════════════════ */
(function () {
  const root = document.getElementById("video-mini-player");
  if (!root) return;

  const toggleBtn = document.getElementById("video-toggle");
  const panel = document.getElementById("video-panel");
  const closeBtn = document.getElementById("video-close");
  const video = document.getElementById("video-player-el");

  let widgetHandle = null;
  if (typeof FloatingWidgets !== "undefined") {
    widgetHandle = FloatingWidgets.register("video-player", root, { corner: "bottom-left", order: 0 });
  }

  function setOpen(open) {
    panel.classList.toggle("hidden", !open);
    toggleBtn.style.display = open ? "none" : "flex";
    if (open) {
      video.play().catch(() => {});
    } else {
      video.pause();
    }
    widgetHandle?.relayout();
  }

  toggleBtn.addEventListener("click", () => setOpen(true));
  closeBtn.addEventListener("click", () => setOpen(false));
})();
