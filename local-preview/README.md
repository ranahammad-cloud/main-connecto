# Connecto Local Preview

This folder contains a dependency-free browser preview of the Connecto MVP. It is intended for quick visual review when npm/Composer dependencies are not installed yet.

## Run it

From the repository root:

```bash
make preview
```

Open `http://localhost:4173`.

## What you can test

- Switch between Interviewee, Interviewer, and Admin dashboards.
- Toggle dark and light themes.
- Search interviewers by name, specialty, or skill.
- Filter by maximum price.
- Open a booking modal and review the 10% platform commission breakdown.
- Review the interview room, wallet/escrow, and admin panel previews.

## Notes

This static preview does not call the Laravel API. Use it for UI validation and stakeholder feedback while the full Laravel + React/Vite app is installed with Composer and npm.
