# Translation Management Service (TMS)

A **Laravel 10 API-driven service** for managing translations across multiple locales with contextual tagging. Built for scalability, performance, and developer usability.

---

## **Features**

-   Store translations for multiple locales (e.g., `en`, `fr`, `es`) with future extensibility.
-   Tag translations by context (e.g., `mobile`, `desktop`, `web`).
-   CRUD endpoints for translations with optional filters by `tag`, `key`, or `content`.
-   JSON export endpoint for frontend applications (e.g., Vue.js).
-   Token-based authentication (API secured with Laravel Sanctum).
-   Efficient bulk seeding with 100k+ records to test scalability.
-   OpenAPI documentation auto-generated with Scramble.
-   PHPUnit & Pest testing of critical features.

## Locale Management

-   Locales are stored in the `locales` table with `code` (e.g., `en`, `fr`, `es`) and `name` fields.
-   New locales can be added via the `/api/locales` POST route.
-   Each translation references a `locale_id` for efficient lookups.
-   This ensures the system can scale to new languages without schema changes.

---

## **Technical Highlights**

-   **Database design**

    -   `contents` table: stores default content and translation keys.
    -   `locales` table: stores all supported languages.
    -   `tags` table: for contextual tags.
    -   `content_translations`: child table linking `contents` and `locales` (1-to-many), stores translations.
    -   Pivot table `content_tags` links `contents` and `tags`.
    -   Optimized queries using eager loading and `whereHas` for filters.

-   **Performance Considerations**

    -   Batch inserts for seeders to handle 100k+ rows.
    -   JSON export endpoint returns translations directly keyed by `content.key` for efficient frontend use.
    -   Avoided unnecessary nested structures in API responses to reduce payload and response time.

-   **Design Principles**

    -   Follows **SOLID** principles and **PSR-12** standards.
    -   Decoupled tables reduce redundancy.
    -   Explicit `belongsToMany` and `hasMany` relations for clarity and performance.
    -   Resource classes used selectively to balance readability and response speed.

-   **Authentication**
    -   Token-based API authentication using Laravel Sanctum.
    -   `login` and `logout` endpoints provided.
        > **Test User Info:**<br>email: test_user@tms.com<br>password: 12345678

---

## **Setup Instructions**

### 1 - Clone the repository

```bash
git clone https://github.com/faizeee/tms_test.git
cd tms_test
```

### 2 - Install dependencies

```bash
composer install
```

### 3 - Environment setup

```bash
cp .env.example .env
```

### 4 - Run migrations & seeders

```bash
php artisan migrate --seed
```

> Seeds include locales, tags, and sample translations (~100k records for performance testing).

### 5 - Serve the application

```bash
php artisan serve
```

### 6 - Run tests

```bash
php artisan test
```

### 7 - API Documentation

-   UI Viewer: http://localhost:8000/docs/api
-   OpenAPI JSON: http://localhost:8000/docs/api.json

### API Endpoints

| Endpoint                                   | Method | Description                                                       | Auth |
| ------------------------------------------ | ------ | ----------------------------------------------------------------- | ---- |
| `/api/translations`                        | GET    | List translations with optional filters (`tag`, `key`, `content`) | Yes  |
| `/api/translations`                        | POST   | Create a translation set (key + content + tags + translations)    | Yes  |
| `/api/translations/{content}`              | PUT    | Update a translation set                                          | Yes  |
| `/api/translations/export/{tag}/{locale?}` | GET    | Export translations in JSON format for frontend                   | Yes  |
| `/api/locales`                             | GET    | List all locales                                                  | Yes  |
| `/api/locales`                             | POST   | Create (add) a new locale                                         | Yes  |
| `/api/tags`                                | GET    | List all tags                                                     | Yes  |
| `/api/auth/login`                          | POST   | Login and receive API token                                       | No   |
| `/api/auth/logout`                         | POST   | Revoke API token                                                  | Yes  |

## Design Choices

### Key + Content

-   Each `content.key` identifies a translation unit.
-   Avoided using only `content` as key to ensure uniqueness across contexts.

### Pivot & Child Tables

-   `content_translations` as a child table allows efficient 1-to-many queries while still supporting many-to-many via attach/sync.
-   `content_tags` pivot allows flexible tagging without redundancy.

### Eager Loading & Filtering

-   Relations are explicitly loaded in queries to minimize N+1 problems.
-   Filters (`tag`, `key`, `content`) are applied using `when` + `whereHas` for efficiency.

### JSON Export

-   Returns `{ key: translation }` format per locale.
-   Preloads all translations for requested tag and locale for fast frontend consumption.

### Testing & Scalability

-   Seeders generate realistic 100k+ translation records for stress testing.
-   PHPUnit/Pest tests cover creation, update, filtering, export, and authentication.
