# laravel-paddle development guide

For full documentation, see the README: https://github.com/protonemedia/laravel-paddle#readme

## At a glance
Provides a Laravel integration with **Paddle** for payments/subscriptions (legacy package; see README warning).

## Local setup
- Install dependencies: `composer install`
- Keep the dev loop package-focused (avoid adding app-only scaffolding).

## Testing
- Run: `composer test` (preferred) or the repository’s configured test runner.
- Add regression tests for bug fixes.

## Notes & conventions
- Keep changes conservative; avoid broad refactors.
- Webhook signature validation and idempotency are critical: changes should be tested.
- Treat public webhook/job payload handling as part of the contract.
