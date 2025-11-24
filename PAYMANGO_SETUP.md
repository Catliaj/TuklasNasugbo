# PayMango Integration — Setup & Testing

This document explains how to configure and test the PayMango integration added to the project.

1) Environment variables

- Copy `.env.example` to `.env` (do NOT commit secrets).
- Set the following:
  - `PAYMANGO_SECRET` — your PayMango server/test secret (used for creating checkout sessions)
  - `PAYMANGO_WEBHOOK_SECRET` — webhook signing secret (used to verify incoming webhooks)
  - `PAYMANGO_API_BASE` — optional API base URL (defaults to `https://api.paymango.com`)

2) Local webhook testing

- For PayMango to reach your local `/paymango/webhook` endpoint, expose your local server with ngrok:

```powershell
# Start your PHP dev server (CodeIgniter)
php spark serve --host=0.0.0.0 --port=8080

# In another shell, run ngrok to forward HTTP
ngrok http 8080
```

- Copy the public ngrok URL (e.g. `https://abcd-1234.ngrok.io`) and configure it in PayMango dashboard as your webhook URL: `https://.../paymango/webhook`.

3) Flow to test

- From the Spot Owner bookings page, click **Collect Payment** then **Proceed to Checkout**.
- The frontend calls `/spotowner/createPaymentSession/{bookingId}` which creates a server-side checkout session and returns a `checkout_url` that opens in a new tab.
- Complete a test payment in the provider's checkout UI.
- PayMango should POST a webhook to `/paymango/webhook`. The webhook handler verifies the HMAC signature (header `X-Paymango-Signature` by default) using `PAYMANGO_WEBHOOK_SECRET` and marks the booking as `Paid` (updates `payment_status`, `payment_provider_txn_id`, and `payment_received_at`).

4) Notes and fallbacks

- The `markPaymentPaid()` endpoint remains available as a manual fallback for offline/phone payments; however, it is NOT a substitute for webhook verification in production.
- The controller tries to be tolerant about provider response shapes; if your PayMango account uses different field names (e.g., `url` vs `checkout_url`), update `createPaymentSession()` accordingly.

5) Troubleshooting

- If you see `PAYMANGO_SECRET not set` errors, ensure `.env` is loaded by your server environment.
- Check `writable/logs/` for logs; the controller logs webhook signature mismatches and provider errors.

If you want, I can help test the flow interactively (you'll need to run the server and provide an ngrok URL or temporarily provide test secrets). Do you want me to proceed to run a local smoke test, or do this with your ngrok URL? 
