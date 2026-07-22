# Estele — Frontend

Static HTML front end for the D2C jewellery site, styled with **Tailwind CSS**
per the updated spec. Reference: https://estele.co

No store functionality — cart, checkout and account are **UI only**, no backend logic.

## Live

https://tcongs-pi.vercel.app

## Structure

```
index.html              Home (15 sections)
collection.html         Category listing + filters + pagination
product.html            Product detail + gallery + accordion + reviews
search.html             Search results
cart.html               Bag (UI only)
checkout.html           Checkout form (UI only)
account.html            Login / register
wishlist.html           Saved items
blog.html               Journal listing
blog-post.html          Article
404.html                Not found
pages/                  CMS pages (about, faq, policies, franchise,
                        store locator, contact, sitemap)

src/app.css              Tailwind entry — design tokens live in @theme
src/app.js                Carousels, drawer, search, gallery, qty, filters, chat
vite.config.js           Build config (mirrors a Laravel + Vite setup)
dist/                    Built output (app.css, app.js) — pages link here
```

## Build

```bash
npm install          # first time only
npm run build         # one-off build -> dist/app.css, dist/app.js
npm run dev            # rebuilds on file save
```

Every page links `dist/app.css` and `dist/app.js`. Run a build after any change
to `src/app.css`, `src/app.js`, or the Tailwind classes in the HTML.

## Changing the design

All design tokens live in the `@theme` block at the top of `src/app.css`. In
Tailwind v4 the tokens ARE the utility classes — `--color-accent` generates
`bg-accent`, `text-accent`, `border-accent`, etc. Change a value there and every
utility using it updates on rebuild.

```css
--font-sans: 'Poppins', ui-sans-serif, system-ui, sans-serif;
--font-serif: 'Libre Baskerville', ui-serif, Georgia, serif;
--color-accent: #cb6b88;
--container-wrapper: 1420px;
```

Fonts are loaded from Google Fonts in the `<head>` of every page (see `ui.py`'s
`head()`).

> Note: the live site uses **Apercu**, a commercial licensed font. Poppins is used
> here because it is free and is what the site's own theme settings specify. If the
> company buys an Apercu licence, self-host the woff files and change `--font-sans`.

## Site chrome (on every page)

- Sticky header with the live theme's golden gradient (`linear-gradient(to bottom, #E9D7BEB3 0%, #fff 100%)`)
- Rotating pink announcement bar (`#ffe3ec`, matches the live site)
- Nested mobile drawer with expandable groups and TRENDING / NEW LAUNCH pills,
  same golden gradient background
- Sticky mobile search bar
- Mobile bottom tab bar (Home / Categories / Trending / Stores / Account)
- Support chat widget with canned replies (front-end only — swap `reply()`
  in `src/app.js` for a real endpoint when the API exists)

## Hero

Full-bleed (no container), **cross-fade** transition — matches the live theme's
`t4s-slide-eff-fade`, not a side-scroll. Controlled by `data-fade` on the
carousel root; see the "HERO" block in `src/app.js`.

## Tile artwork

Category, collection and celebrity tile images have the name **typeset into the
artwork**. Do not add a text label under them — it double-prints the name. The
`<img alt>` carries the name for screen readers and SEO.

| Section | Ratio | Radius | Cols (d/t/m) |
|---|---|---|---|
| Shop by Category | 2:3 | 8% | 8 / 3 / 2 |
| Shop by Collection | 2:3 | 8% | 4 / 3 / 2 |
| Celebrities | 1:1.38 | 16px | 4 / 4 / 2 |
| Budget | 1:1 | 14px | 4 / 2 / 2 |

## Converting to Blade later

The generator scripts already split things the way Blade will want them:

- `ui.py` (in the build scripts, not shipped) holds `head()`, `header()`,
  `footer()`, `chat()`, `tabbar()`, `product_card()`, `collection_tile()`,
  `breadcrumb()`, `section_header()`, `carousel()` — each becomes one
  `resources/views/components/*.blade.php`.
- Header/footer are byte-identical on every page — lift them into
  `layouts/app.blade.php`.
- Repeating blocks (product card, collection tile, budget tile, testimonial,
  footer column) are written **once and duplicated**, never hand-varied. Each
  becomes a single `@foreach` over a component.
- Swap hardcoded text/images for `{{ $var }}`. Tailwind classes need no changes.

## Images

Currently referenced from the live Shopify CDN with `?width=` for responsive
`srcset`. Before launch these must be replaced with the client's own assets served
from your CDN.

## Local preview

```bash
npm run build
python -m http.server 8899
# http://127.0.0.1:8899
```
