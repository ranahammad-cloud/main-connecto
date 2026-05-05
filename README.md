# Connecto

Connecto is a two-sided marketplace MVP that connects interviewees with professional interviewers for paid mock interview sessions. The repository is organized as a Laravel REST API backend and a React/Vite frontend.

## Stack

- **Frontend:** React, Vite, Tailwind CSS-ready styling, responsive SaaS marketplace UI
- **Backend:** Laravel API structure, Sanctum token authentication, RESTful MVC controllers
- **Database:** MySQL relational schema via migrations
- **Integrations:** Stripe payment intents/webhooks, Google OAuth, LinkedIn OAuth, Agora/100ms-ready video room endpoint

## Repository layout

```text
backend/   Laravel API source, migrations, routes, API documentation
frontend/  React/Vite marketplace UI and role-based dashboard prototype
docs/      Product and setup notes
```


## Instant local preview (no npm or Composer required)

If you only want to view and interact with the Connecto UI immediately, run the dependency-free preview server:

```bash
make preview
```

Then open `http://localhost:4173`. You can also run `PORT=5000 make preview` to use a different port. The preview includes role switching, dark/light mode, marketplace search and price filtering, booking modal simulation, Stripe commission breakdown, interview room mock UI, wallet state, and admin metrics.

## Full-stack local setup

### Backend

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

Set the Stripe, Google, LinkedIn, and video provider keys in `backend/.env` before testing external integrations.

### Frontend

```bash
cd frontend
npm install
npm run dev
```

The frontend expects the API at `http://localhost:8000/api` and runs on `http://localhost:5173`.

## MVP capability map

- Interviewees can create profiles, browse/search interviewers, book calendar slots, pay through Stripe, join sessions, and review experts.
- Interviewers can manage profiles, pricing, availability, incoming bookings, feedback, earnings, and withdrawal requests.
- Admins can approve interviewers, view bookings and transactions, and resolve disputes.
- Payments implement a 10% platform commission and escrow-style payment state transitions.
