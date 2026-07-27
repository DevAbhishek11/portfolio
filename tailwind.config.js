/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/views/**/*.blade.php",
    "./resources/views/**/**/*.blade.php",
    "./public/assets/js/**/*.js",
  ],
  darkMode: "class",
  theme: {
    extend: {
      fontFamily: {
        display: ['"Playfair Display"', "serif"],
        body: ["Inter", "sans-serif"],
      },
      colors: {
        // Anime dark theme tokens
        "bg-primary": "#0a0a0f",
        "bg-secondary": "#12121a",
        "bg-tertiary": "#1a1a2e",
        "bg-glass": "rgba(26,26,46,0.7)",
        // Accents
        "accent-1": "#8b5cf6",
        "accent-2": "#06b6d4",
        "accent-3": "#f43f5e",
        // Text
        "txt-primary": "#e4e4e7",
        "txt-secondary": "#a1a1aa",
        "txt-accent": "#c084fc",
        // Admin
        "admin-bg": "#0f0f14",
        "admin-surface": "#17171f",
        "admin-card": "#1e1e2a",
        "admin-border": "#2a2a3a",
      },
      spacing: {
        18: "4.5rem",
        22: "5.5rem",
        30: "7.5rem",
      },
      borderRadius: {
        xl2: "1.25rem",
        xl3: "1.5rem",
      },
      backgroundImage: {
        "gradient-anime": "linear-gradient(135deg,#8b5cf6,#06b6d4,#f43f5e)",
        "gradient-purple-cyan": "linear-gradient(135deg,#8b5cf6,#06b6d4)",
        "gradient-pink-purple": "linear-gradient(135deg,#f43f5e,#8b5cf6)",
        "grid-pattern": `linear-gradient(rgba(139,92,246,0.04) 1px,transparent 1px),
                         linear-gradient(90deg,rgba(139,92,246,0.04) 1px,transparent 1px)`,
      },
      backgroundSize: {
        grid: "50px 50px",
      },
      animation: {
        float: "floatY 5s ease-in-out infinite",
        "float-x": "floatX 4s ease-in-out infinite",
        "spin-slow": "spin 20s linear infinite",
        "pulse-glow": "pulseGlow 3s ease-in-out infinite",
        shimmer: "shimmer 4s linear infinite",
        "scroll-dot": "scrollDot 2s ease-in-out infinite",
        "blink-cursor": "blinkCursor 0.8s step-end infinite",
        "slide-up": "slideUp 0.4s ease",
        "onoma-pop":
          "onomaPop 0.8s cubic-bezier(0.175,0.885,0.32,1.275) forwards",
        sparkle: "sparkleBurst 0.7s ease-out forwards",
      },
      keyframes: {
        floatY: {
          "0%,100%": { transform: "translateY(0)" },
          "50%": { transform: "translateY(-12px)" },
        },
        floatX: {
          "0%,100%": { transform: "translateX(0)" },
          "50%": { transform: "translateX(-8px)" },
        },
        pulseGlow: {
          "0%,100%": { boxShadow: "0 0 20px rgba(139,92,246,0.2)" },
          "50%": { boxShadow: "0 0 40px rgba(139,92,246,0.5)" },
        },
        shimmer: {
          "0%": { backgroundPosition: "-200% center" },
          "100%": { backgroundPosition: "200% center" },
        },
        scrollDot: {
          "0%,100%": { opacity: "1", transform: "translateY(0)" },
          "50%": { opacity: "0.3", transform: "translateY(20px)" },
        },
        blinkCursor: { "0%,100%": { opacity: "1" }, "50%": { opacity: "0" } },
        slideUp: {
          from: { opacity: "0", transform: "translateY(20px)" },
          to: { opacity: "1", transform: "translateY(0)" },
        },
        onomaPop: {
          "0%": { opacity: "0", transform: "scale(0.3) rotate(-10deg)" },
          "50%": { opacity: "1", transform: "scale(1.2) rotate(3deg)" },
          "100%": { opacity: "0", transform: "scale(1) translateY(-30px)" },
        },
        sparkleBurst: {
          "0%": { transform: "scale(1) translate(0,0)", opacity: "1" },
          "100%": {
            transform: "scale(0) translate(var(--tx),var(--ty))",
            opacity: "0",
          },
        },
      },
      boxShadow: {
        anime: "0 0 30px rgba(139,92,246,0.15)",
        "anime-lg": "0 0 60px rgba(139,92,246,0.25)",
        "glow-purple": "0 8px 30px rgba(139,92,246,0.4)",
        "glow-cyan": "0 8px 30px rgba(6,182,212,0.3)",
        "glow-pink": "0 8px 30px rgba(244,63,94,0.3)",
      },
      dropShadow: {
        anime: "0 0 20px rgba(139,92,246,0.4)",
      },
    },
  },
  plugins: [],
};
