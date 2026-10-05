# VietnamVisa / SunViet frontend

Frontend-only PHP project designed in a modern Hostinger-inspired visual direction. It contains no database and no admin logic.

## Important architecture

- `data/services.php` is retained as legacy mock data; the active services source is `data/services-db.php`.
- News is loaded from MySQL through `data/news-db.php`; `data/news.php` is retained as the original reference content.
- Admin News management is available at `/VietnamVisa/admin/news/`.
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

Copy `VietnamVisa` to XAMPP `htdocs`, enable Apache rewrite, then open `/VietnamVisa/en`.
