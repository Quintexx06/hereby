# MCP servers

Configured for the project in `.mcp.json`. Check their status with `/mcp` in Claude Code.

| Server          | What it gives the agent                                          | Setup |
| --------------- | ---------------------------------------------------------------- | ----- |
| `laravel-boost` | App info, DB schema and queries, Tinker, logs, version-aware Laravel docs search | None (`php artisan boost:mcp`) |
| `shadcn-vue`    | Browse and install shadcn-vue components with the correct API    | None |
| `context7`      | Up-to-date library docs (GSAP, reka-ui, Vue…)                    | None |
| `playwright`    | Drive a browser: screenshots and flows for verification          | None |
| `magic-21st`    | 21st.dev component inspiration and generation                    | Set `TWENTY_FIRST_API_KEY` in your shell (key from the 21st.dev Magic console) |
| `motionsites`   | MotionSites landing-page and motion design prompts               | None (free tier: 3 prompts) |

Notes:
- `.claude/settings.json` auto-enables `laravel-boost`, `shadcn-vue`, `context7`
  and `playwright`. `magic-21st` and `motionsites` are opt-in: approve them
  when Claude Code asks, or add them to `enabledMcpjsonServers`.
- Code from 21st.dev and MotionSites is usually React. Treat it as **inspiration**,
  then rebuild it in Vue with our tokens, components and motion rules.
- Don't commit API keys. `.mcp.json` reads them from environment variables.
- Account-level connectors (GitHub, Figma, Linear…) belong in your user config,
  not in this repo.
