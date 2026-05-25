# Frontend (React + Vite)

This directory contains a React frontend built with Vite and TailwindCSS. It's intended to be deployed separately (for example: Vercel) and talk to the Laravel backend running on Render.

Quick start (local):

```bash
cd frontend
npm install
npm run dev
```

Build for production (Vercel):

```bash
npm run build
```

Vercel settings:
- Framework: Other (Vite) or leave default
- Build command: `npm run build`
- Output directory: `dist`
- Environment variable: `VITE_API_URL` -> set this to your backend URL (e.g. `https://power-supply-feedback-management-system.onrender.com`)

CORS:
- The backend must allow CORS from your Vercel domain. If you get CORS errors, add an allow rule on the Laravel side or add a small CORS middleware that permits the frontend origin.

Styling and appearance:
- The Tailwind config and CSS are copied from the backend's `resources/css/app.css` and `tailwind.config.js` to preserve the same colors, background gradients, and utilities.
