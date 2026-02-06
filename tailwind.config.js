module.exports = {
  content: ["./public/**/*.php", "./public/**/*.js"],
  corePlugins: {
    preflight: false,
  },
  prefix: "tw-",
  theme: {
    extend: {
      colors: {
        bg: "var(--bg)",
        "bg-strong": "var(--bg-strong)",
        surface: "var(--surface)",
        "surface-2": "var(--surface-2)",
        ink: "var(--ink)",
        muted: "var(--muted)",
        accent: "var(--accent)",
        "accent-2": "var(--accent-2)",
        "accent-3": "var(--accent-3)",
        warn: "var(--warn)",
        info: "var(--info)",
        stroke: "var(--stroke)",
      },
      boxShadow: {
        card: "var(--shadow)",
      },
    },
  },
  plugins: [],
};
