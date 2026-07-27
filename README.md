# TCONGS — D2C E-commerce Platform

Custom-coded D2C jewellery/fashion storefront (reference: Estele.co) — Laravel + Filament backend, Blade/Tailwind storefront. Built against a phased developer spec (Foundation → Storefront → Commerce → Content → SEO → QA → Post-launch).

**Live:**
- Storefront + Admin (Laravel backend): https://tcongs-production.up.railway.app
- Static reference frontend (Vercel, pre-backend clone): https://tcongs-pi.vercel.app

---

## Build status by phase

### Phase 1 — Foundation ✅
- Laravel 13 + Filament 5 admin panel (`/admin`), MySQL, Redis
- Spatie Permission RBAC — `super_admin` / `marketing` roles, real 403s enforced
- Spatie Media Library + Intervention Image, polymorphic SEO meta table
- Category/Product/Setting admin CRUD

### Phase 2 — Storefront Core ✅
- Home, Category, Collection, Product, Search — all DB-driven, zero hardcoded content
- Guest cart + checkout (COD), stock-safe with row locking
- Real search: Laravel Scout + MeiliSearch (not raw `LIKE`)
- Redis full-page/query caching, invalidated on admin save
- Responsive WebP image pipeline (4 size tiers per §3.1 spec)

### Phase 3 — Commerce Logic ✅
- **Payments:** Razorpay, real API (test mode) — order creation, signature-verified callback, webhook handling
- **Shipping:** flat-rate (live) + full Shiprocket client (auth/rate/create-shipment/track) behind a config toggle, ready for a real account
- **Coupons:** flat/% discounts, product/category/sitewide scope, expiry/min-cart/usage-limit rules, re-validated at checkout
- **Orders:** guarded status pipeline (Placed → Packed → Shipped → Delivered, branching Cancelled/Returned), auto-restock, on-demand PDF invoices, refund bookkeeping

### Phase 4 — Content & Trust ✅ (Store Locator excluded — pending scope confirmation)
- Blog + categories (publishing pipeline), CMS pages, FAQ
- Reviews & Ratings — customer submission, admin approve/reject
- Testimonials & USP/trust badges (via block-based Homepage Builder)
- Popups/announcements, newsletter signup capture

### Phase 5 — SEO & Performance Hardening — not started
### Phase 6 — QA & Launch — not started
### Phase 7 — Post-launch readiness — not started

---

## Verification

All Phase 3/4 business logic has automated test coverage — **70 tests, 148 assertions, all passing** (`php artisan test` from `backend/`). Covers coupon validation, order status transitions + refunds, Razorpay callback/webhook, Shiprocket shipment actions, blog publishing, reviews, popups.

## Stack

Laravel 13 · Filament 5 · MySQL · Redis · Laravel Scout + MeiliSearch · Spatie Media Library · Razorpay · Shiprocket · Blade + Tailwind CSS + Alpine.js (no SPA framework on the public storefront, by design — SEO/speed first).
