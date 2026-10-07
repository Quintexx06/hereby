# MCP servers

Configured for the project in `.mcp.json`. Check their status with `/mcp` in Claude Code.

| Server          | What it gives the agent                                          | Setup |
| --------------- | ---------------------------------------------------------------- | ----- |
| `laravel-boost` | App info, DB schema and queries, Tinker, logs, version-aware Laravel docs search | None (`php artisan boost:mcp`) |
| `shadcn-vue`    | Browse and install shadcn-vue components with the correct API    | None |
| `context7`      | Up-to-date library docs (GSAP, reka-ui, Vue…)                    | None |
| `playwright`    | Drive a browser: screenshots and flows for verification          | None |
| `motionsites`   | MotionSites landing-page and motion design prompts               | Sign in once: `/mcp` → motionsites → Authenticate (OAuth). Also added at user scope, so it works in every project. |

Notes:
- `.claude/settings.json` auto-enables `laravel-boost`, `shadcn-vue`, `context7`,
  `playwright` and `motionsites`.
- `laravel-boost` needs a bootstrapped app: run `composer setup` once (creates
  `.env`, the key and the SQLite database), or it fails with "Connection closed".
- Inspiration from any MCP is checked against `DESIGN.md` before it lands.
- Code from MotionSites is usually React. Treat it as **inspiration**,
  then rebuild it in Vue with our tokens, components and motion rules.
- Don't commit API keys. `.mcp.json` reads them from environment variables.
- Account-level connectors (GitHub, Figma, Linear…) belong in your user config,
  not in this repo.
