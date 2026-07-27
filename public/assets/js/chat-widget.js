/* ═══════════════════════════════════════════════════════════════════════════
   AI CHAT WIDGET — talks to /chat/message, plugs into FloatingWidgets
   ═══════════════════════════════════════════════════════════════════════════ */
(function () {
  const root = document.getElementById("ai-chat-widget");
  if (!root) return; // chat disabled server-side (no OpenRouter key configured)

  const toggleBtn = document.getElementById("chat-toggle");
  const panel = document.getElementById("chat-panel");
  const closeBtn = document.getElementById("chat-close");
  const resetBtn = document.getElementById("chat-reset");
  const form = document.getElementById("chat-form");
  const input = document.getElementById("chat-input");
  const messages = document.getElementById("chat-messages");
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

  let widgetHandle = null;
  if (typeof FloatingWidgets !== "undefined") {
    widgetHandle = FloatingWidgets.register("ai-chat", root, { corner: "bottom-right", order: 0 });
  }

  function relayout() {
    widgetHandle?.relayout();
  }

  function setOpen(open) {
    panel.classList.toggle("hidden", !open);
    toggleBtn.setAttribute("aria-expanded", String(open));
    if (open) input?.focus();
    relayout();
  }

  toggleBtn.addEventListener("click", () => setOpen(panel.classList.contains("hidden")));
  closeBtn.addEventListener("click", () => setOpen(false));

  function addMessage(role, text) {
    const el = document.createElement("div");
    el.className = "chat-msg " + (role === "user" ? "chat-msg-user" : "chat-msg-assistant");
    el.textContent = text;
    messages.appendChild(el);
    messages.scrollTop = messages.scrollHeight;
    return el;
  }

  function addTypingIndicator() {
    const el = document.createElement("div");
    el.className = "chat-msg-typing";
    el.textContent = "Typing…";
    messages.appendChild(el);
    messages.scrollTop = messages.scrollHeight;
    return el;
  }

  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    const text = input.value.trim();
    if (!text) return;

    addMessage("user", text);
    input.value = "";
    input.disabled = true;
    const typing = addTypingIndicator();

    try {
      const res = await fetch("/chat/message", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": csrfToken,
          Accept: "application/json",
        },
        body: JSON.stringify({ message: text }),
      });
      const data = await res.json();
      typing.remove();
      addMessage("assistant", data.reply || "Sorry, something went wrong. Please try again.");
    } catch (err) {
      typing.remove();
      addMessage("assistant", "Sorry, I couldn't reach the server. Please try again in a moment.");
    } finally {
      input.disabled = false;
      input.focus();
      relayout();
    }
  });

  resetBtn.addEventListener("click", async () => {
    try {
      await fetch("/chat/reset", {
        method: "POST",
        headers: { "X-CSRF-TOKEN": csrfToken, Accept: "application/json" },
      });
    } catch (err) { /* best-effort */ }
    messages.innerHTML = "";
    addMessage("assistant", "Conversation reset. What would you like to know?");
    relayout();
  });
})();
