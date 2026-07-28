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

  const BOT_ICON_SVG =
    '<svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">' +
    '<rect x="4" y="8" width="16" height="12" rx="3"/><path stroke-linecap="round" d="M12 8V4"/>' +
    '<circle cx="12" cy="3" r="1.1" fill="currentColor" stroke="none"/>' +
    '<circle cx="9" cy="13.5" r="1.3" fill="currentColor" stroke="none"/>' +
    '<circle cx="15" cy="13.5" r="1.3" fill="currentColor" stroke="none"/>' +
    '<path stroke-linecap="round" d="M9 17.5h6"/></svg>';

  function escapeHtml(str) {
    const div = document.createElement("div");
    div.textContent = str;
    return div.innerHTML;
  }

  // Minimal, safe "- bullet" / line-break formatting for AI replies — text
  // is HTML-escaped first, so only the ul/li/br tags added here are real markup.
  function formatAssistantText(text) {
    const lines = escapeHtml(text).split("\n");
    let html = "";
    let inList = false;
    lines.forEach((line) => {
      const bullet = line.match(/^\s*[-*]\s+(.*)$/);
      if (bullet) {
        if (!inList) { html += "<ul>"; inList = true; }
        html += "<li>" + bullet[1] + "</li>";
      } else {
        if (inList) { html += "</ul>"; inList = false; }
        html += line + "<br>";
      }
    });
    if (inList) html += "</ul>";
    return html;
  }

  function addMessage(role, text) {
    const row = document.createElement("div");
    const bubble = document.createElement("div");

    if (role === "user") {
      row.className = "chat-row chat-row-user";
      bubble.className = "chat-msg chat-msg-user";
      bubble.textContent = text;
    } else {
      row.className = "chat-row chat-row-assistant";
      const avatar = document.createElement("div");
      avatar.className = "chat-avatar";
      avatar.innerHTML = BOT_ICON_SVG;
      row.appendChild(avatar);
      bubble.className = "chat-msg chat-msg-assistant";
      bubble.innerHTML = formatAssistantText(text);
    }

    row.appendChild(bubble);
    messages.appendChild(row);
    messages.scrollTop = messages.scrollHeight;
    return row;
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
