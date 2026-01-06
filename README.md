# Dian Mukti Wibowo — Portfolio (Static site)

A simple, fast, single‑page portfolio and CV site built with static HTML/CSS/JS. The site is localized and supports multiple languages, includes a downloadable CV and project portfolio, and highlights recent work around web, mobile and LLM/AI agent research.

---

## Features ✅

- Static, dependency‑free frontend (no framework required) using Tailwind + Bootstrap assets
- i18n support with JSON translations (see `assets/i18n/translations.json`)
- Language selector with flag FAB and persistent choice (stored in localStorage)
- CV viewer (PDF.js) and image preview modal
- Lightweight asset layout under `assets/` (images, libs, icons)
- LLM/AI Agent mention: highlights active work with OLLAMA, QWEN 2, etc.

---

## Local development 🛠️

- Serve the folder with a static server for testing (examples):

  - `serve .` (using npm `serve` or any static server)
  - `python -m http.server 8000`

- Edit `index.html` and `assets/i18n/translations.json` for content and translations.
- Fonts: site uses Google Fonts (Inter) included in `index.html`.

---

## Internationalization 🌐

- Translations live in `assets/i18n/translations.json` (keys are used as `data-i18n` attributes).
- To add a new language:
  1. Add a locale block in `translations.json` with the same keys.
  2. Update the `FLAGS` map in `index.html` and ensure a `.lang-item` entry exists in the language modal.
  3. Test RTL layouts if adding a right-to-left language (e.g., Arabic).

---

## Assets & Security 🔒

- `assets/` contains third-party libs and site resources. Small `index.html` files and `README.md` files have been added to each subfolder to prevent directory listing.
- Keep large images optimized. Use webp when possible.

---

## Contributing & Notes ✍️

- Send PRs for content or translations. Keep tone concise and neutral.
- For translation fixes, edit `assets/i18n/translations.json` and provide native/language-appropriate phrasing.

---

## Bahasa Indonesia (ringkasan)

Situs portofolio statis dengan dukungan i18n (file `assets/i18n/translations.json`), penampil CV, dan preview gambar. Font Inter digunakan dari Google Fonts. Untuk mode pengembangan, jalankan server statis (mis. `serve .`).

---

## Contact ✉️

- Email: <a href="mailto:onyetcorp@gmail.com">onyetcorp@gmail.com</a>
- WhatsApp: +62 822-2187-4400 (preferred for quick messages)
- LinkedIn: <https://www.linkedin.com/in/dian-mukti-wibowo-244576a6>

_Usually responds within 1 business day. Prefer email or WhatsApp for quick communication._

---

## License

This repository is licensed under the **MIT License** — see the `LICENSE` file for the official (English) text.

Translations for convenience (English text is authoritative):
- `LICENSE.id` — Indonesian
- `LICENSE.zh` — Chinese

---
