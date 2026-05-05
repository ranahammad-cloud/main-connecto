# SRS Implementation Notes

Connecto is implemented as a production-oriented MVP scaffold. The Laravel backend defines the relational schema, API surface, auth flow, payment escrow states, booking lifecycle, wallet transactions, and admin operations. The React frontend demonstrates the required modern marketplace experience, role switcher, interviewer search, booking call-to-action, Stripe escrow messaging, video session positioning, and admin metrics.

## Design tokens

- Primary Indigo: `#4F46E5`
- Secondary Cyan: `#06B6D4`
- Accent Amber: `#F59E0B`
- Background: `#F9FAFB`
- Text: `#111827`

## Production hardening checklist

1. Install dependencies and publish Laravel Sanctum migrations/config.
2. Configure HTTPS, CORS, rate limiting, queue workers, and object storage for resumes.
3. Verify Stripe webhook signatures with `STRIPE_WEBHOOK_SECRET` before accepting events.
4. Add Agora or 100ms token generation for secure video room access.
5. Add policy classes for each model and an admin gate for the admin route group.
6. Add end-to-end tests for booking/payment/review lifecycles.
