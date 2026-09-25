# Immigra / SunViet frontend

Frontend-only PHP project designed in a modern Hostinger-inspired visual direction. It contains no database and no admin logic.

## Important architecture

- `data/services.php` and `data/news.php` are **mock data only**.
- A backend developer can replace those arrays with DB/API results without redesigning the views.
- Service detail pages: `/en/service/{slug}` and `/vn/service/{slug}`.
- News detail pages: `/en/article/{slug}` and `/vn/article/{slug}`.
- Main pages: `/en`, `/en/services`, `/en/visa`, `/en/support`, `/en/about`, `/en/news`, `/en/apply`, `/en/contact`, `/en/quotation`.

## Backend integration contract

The view expects each service record to provide:
`slug`, `category`, `title_en`, `title_vn`, `short_en`, `short_vn`, `description_en`, `description_vn`, `image`, `features_en/vn`, `steps_en/vn`, `faq_en/vn`.

The view expects each news record to provide:
`slug`, `date`, `category_en/vn`, `title_en/vn`, `excerpt_en/vn`, `image`, `content_en/vn`.

Later the backend can populate `$SERVICES` and `$NEWS` from database/API with the same shape.

## Run

Copy `immigra` to XAMPP `htdocs`, enable Apache rewrite, then open `/immigra/en`.
