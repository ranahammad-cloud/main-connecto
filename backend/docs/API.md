# Connecto API Documentation

Base URL: `/api`

## Authentication

| Method | Endpoint | Description |
| --- | --- | --- |
| POST | `/auth/register` | Register an interviewee or interviewer with email/password. Interviewers start as `pending`. |
| POST | `/auth/login` | Login and receive a Sanctum token. |
| GET | `/auth/google/redirect` | Start Google OAuth. |
| GET | `/auth/linkedin/redirect` | Start LinkedIn OAuth. |
| GET | `/me` | Return authenticated user, profile, and wallet. |
| POST | `/auth/logout` | Revoke current token. |

## Marketplace and profiles

| Method | Endpoint | Description |
| --- | --- | --- |
| GET | `/marketplace/interviewers?search=&skill=&max_price=` | Browse approved interviewers with filters. |
| GET | `/marketplace/interviewers/{user}` | View interviewer profile, availability, and reviews. |
| POST | `/profiles` | Create or update the current user's profile. |
| PUT/PATCH | `/profiles/{profile}` | Update an owned profile. |

## Bookings and sessions

| Method | Endpoint | Description |
| --- | --- | --- |
| GET | `/bookings` | List role-scoped bookings. |
| POST | `/bookings` | Create a booking request with `interviewer_id` and `scheduled_at`. |
| POST | `/bookings/{booking}/accept` | Interviewer accepts a booking. |
| POST | `/bookings/{booking}/reject` | Interviewer rejects a booking. |
| POST | `/bookings/{booking}/feedback` | Interviewer submits post-interview feedback. |
| GET | `/sessions/{booking}` | Return video provider room metadata for Agora/100ms integration. |
| POST | `/reviews` | Interviewee rates and reviews a completed booking. |

## Payments and wallet

| Method | Endpoint | Description |
| --- | --- | --- |
| POST | `/bookings/{booking}/payment-intent` | Create Stripe PaymentIntent and payment log with 10% commission. |
| POST | `/payments/webhook` | Handle Stripe payment events and move funds into escrow. |
| GET | `/wallet` | View authenticated user's wallet balance and transactions. |
| POST | `/wallet/withdrawals` | Request a withdrawal from available earnings. |

## Admin

Admin endpoints require an authenticated user with role `admin`.

| Method | Endpoint | Description |
| --- | --- | --- |
| GET | `/admin/users` | View all users and profiles. |
| POST | `/admin/interviewers/{user}/approve` | Approve a pending interviewer. |
| GET | `/admin/bookings` | Monitor sessions and booking statuses. |
| GET | `/admin/transactions` | View payment logs. |
| POST | `/admin/disputes/{booking}/resolve` | Resolve booking disputes. |
