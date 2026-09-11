# Classroom

A classroom management application for schools and colleges, built on
CodeIgniter 4. Administrators set up classes, subjects, teachers and students;
teachers post assignments, classwork and notices; students see what is due for
their class and submit their work.

---

## Requirements

- PHP 8.0 or later, with `intl`, `mbstring`, `json`, `curl` and either
  `mysqli` or `sqlite3`
- MySQL 5.7+ / MariaDB 10.3+ (or SQLite for local work)
- Composer

## Getting started

```bash
composer install
cp env .env
```

Edit `.env` and set `app.baseURL` and the `database.default.*` values, then
create the schema and, optionally, a demo tenant to click around in:

```bash
php spark migrate
php spark db:seed DemoSeeder
php spark serve
```

Point your web server at the **`public/`** directory, not the project root —
everything above `public/` (including `.env` and `writable/`) must stay
unreachable over HTTP.

### Base URL

`app.baseURL` is what every asset, link and redirect is built from. Left at its
default it is worked out from the request, so the app runs on whatever host and
port you serve it from without any configuration — `localhost:8080`,
`localhost/classroom/public` or anything else.

Guessing is only allowed for loopback and private addresses. On a real
hostname the app refuses to start until you set the value, because the `Host`
header is attacker-controlled and a forged one would otherwise end up in the
links it generates:

```ini
app.baseURL = 'https://classroom.example.edu/'
```

### Upgrading a database created before this project had migrations

The original application shipped no migrations, so existing installations have
a hand-built schema that has drifted from the models. `php spark migrate`
handles it: `CreateCoreSchema` creates whatever is missing, and
`ReconcileLegacySchema` repairs the differences that break the application —
chiefly `assignment_upload`, which described an assignment rather than a
submission and had no link to either the assignment or the student.

Take a backup first:

```bash
mysqldump -u root --databases classroom --result-file=classroom-backup.sql
```

### Windows with XAMPP

XAMPP ships PHP but does not add it to `PATH`, which is why `php` on its own
reports "not recognised". Either use the full path:

```bash
C:\xampp\php\php.exe spark migrate
```

or add it to `PATH` once, in a **new** terminal afterwards:

```bash
setx PATH "%PATH%;C:\xampp\php"
```

Start MySQL from the XAMPP control panel (or `C:\xampp\mysql\bin\mysqld.exe`)
before migrating, and create the database:

```bash
C:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE classroom CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

To skip the database server entirely, use SQLite instead — uncomment the
SQLite lines in `.env` and enable the extension, which XAMPP bundles but
leaves off: remove the `;` from `;extension=sqlite3` in `C:\xampp\php\php.ini`.

### Demo accounts

Created by `DemoSeeder`, for local use only:

| Role    | Email                  | Password      |
| ------- | ---------------------- | ------------- |
| Admin   | `admin@classroom.test` | `admin@123`   |
| Teacher | `anil@classroom.test`  | `teacher@123` |
| Student | `rahul@classroom.test` | `student@123` |

## Roles

Every record belongs to an organisation (an `admin_id`), and every query is
scoped to the signed-in user's organisation.

| | Admin | Teacher | Student |
| --- | :---: | :---: | :---: |
| Classes, subjects, teachers, students | ✅ | ✅ | — |
| Post assignments, classwork, notices | ✅ | ✅ | — |
| View assignments, classwork, notices | all | all | own class |
| Submit work | — | — | ✅ |
| Own profile | ✅ | ✅ | ✅ |

## Theming

Appearance has two independent axes, both chosen by the reader and stored in
their browser — nothing is saved against the account:

- **Mode** — light, dark, or follow the operating system (which it tracks
  live, not only at page load).
- **Accent** — indigo, violet, teal, emerald, amber, rose or slate. The accent
  drives the brand colour *and* the soft gradient wash behind the page.

Both are applied by `app/Views/partials/theme_boot.php`, a short synchronous
script in the `<head>`, so no page flashes the wrong theme before the
stylesheet loads.

Adding an accent means adding one block to `public/assets/css/app.css`:

```css
:root[data-accent="ocean"] {
  --h: 205;          /* brand hue                                  */
  --s: 70%;          /* brand saturation                           */
  --l-solid: 42%;    /* optional: lightness on light surfaces      */
  --l-deep: 34%;     /*           …and for text on light surfaces  */
  --l-dark: 66%;     /* optional: lightness on dark surfaces       */
  --wash-a: 205;     /* the two hues the background blends between */
  --wash-b: 170;
}
```

Nothing else in the sheet names a hue: every brand token is derived from those
by varying lightness. The per-mode lightness values exist because the same
colour cannot serve both surfaces — a blue that holds white text on white is
nearly invisible on near-black, and a mid-lightness blue is the worst case,
failing against white *and* dark text.

`tests/app/AccentContrastTest.php` parses the stylesheet, rebuilds each derived
colour and fails if any accent drops below WCAG AA (4.5:1) in either mode, so
an unreadable theme cannot be added by accident. All fourteen accent/mode
combinations currently measure 4.85:1 or better.

The wash itself is a fixed pseudo-element behind everything, so cards and table
rows keep their own opaque surface — the gradient never sits behind body text.

## Layout

```
app/
  Controllers/     one per resource, thin; all extend BaseController
  Models/          extend App\Models\BaseModel, which adds tenant scoping
  Libraries/
    Auth.php       sign-in and the current identity, for all three roles
    FileStore.php  validated uploads, stored outside the web root
  Filters/         auth, role and guest route guards
  Validation/      DateRules — see "Known gaps"
  Views/
    layouts/       app (signed in) and auth (signed out) shells
    partials/      nav, alerts, icons, theme picker, empty states
  Database/
    Migrations/    the full schema, plus the legacy-database reconciliation
    Seeds/         DemoSeeder
public/
  assets/css/app.css   the whole design system: tokens, accents, components
  assets/js/app.js     theme picker, navigation, menus, confirmations
tests/app/         the application's test suite
```

Uploaded files live in `writable/uploads/{organisation}/{category}/` and are
served only by `FileController`, which checks the session and the owning
organisation before streaming a file.

## Running the tests

```bash
composer test           # or: vendor/bin/phpunit --testsuite App
```

The suite runs against an in-memory SQLite database (the `tests` group in
`app/Config/Database.php`), so it needs the `sqlite3` extension but no database
server. It covers authentication, route-level access control, tenant isolation,
upload validation and theme contrast.

## Security notes

The application enforces the following, and `tests/app` asserts each of them:

- **Passwords are verified for all three roles.** Earlier versions had the
  `password_verify()` call commented out on the student branch, so any student
  email address signed in with any password.
- **Auto-routing is off.** Every route is declared in `app/Config/Routes.php`.
  With auto-routing on, every public controller method was reachable as a URL.
- **Every route is behind a filter.** `auth` for anything requiring a session,
  `role:admin,teacher` for management pages.
- **CSRF tokens on every state-changing request**, configured globally in
  `app/Config/Filters.php`.
- **Deletion is POST-only.** Delete links used to be `GET`, so a crawler, a
  prefetch or a forged `<img>` tag could destroy records.
- **Queries are tenant-scoped in the database, not the view.** Reading, editing
  or deleting another organisation's record returns 404.
- **Uploads are validated and stored outside the web root.** Extension, sniffed
  MIME type and size are all checked, and the two must agree — a PHP script
  renamed `.pdf` is rejected.
- **All output is escaped** with `esc()`.

Before deploying, set `CI_ENVIRONMENT = production`, generate an encryption key
with `php spark key:generate`, and turn on `app.forceGlobalSecureRequests` and
`app.cookieSecure`.

## Known gaps

- **CodeIgniter 4.1.9 predates PHP 8.2.** Two consequences are worked around in
  this repository rather than fixed upstream:
  - `valid_date[Y-m-d]` rejects every date, because
    `DateTime::getLastErrors()` now returns `false` rather than an array of
    zero counts when a parse succeeds. `App\Validation\DateRules::iso_date`
    replaces it, and `tests/app/DateRuleTest.php` fails once the framework is
    upgraded and the workaround can be removed.
  - The test harness calls `mb_convert_encoding($html, 'HTML-ENTITIES')`, which
    is deprecated; `app/Config/Boot/testing.php` excludes `E_DEPRECATED` so the
    suite reports application failures rather than framework ones.

  Upgrading to a current CodeIgniter 4.x release removes both.
- **`system/` and `vendor/` are committed to the repository.** That is how the
  project was set up, and it is left alone here, but it makes framework
  upgrades a manual merge.
- **`public/uploads/` and `public/csv/` still hold files from the previous
  version**, which served uploads directly out of the web root. Nothing writes
  there any more and both now carry a deny-all `.htaccess`, but the leftover
  files can be deleted once you have confirmed nothing needs them.
- **Temporary passwords are fixed strings** (`student@123`, `teacher@123`) shown
  to the administrator when an account is created. A proper invite-by-email flow
  with a forced password change on first sign-in is the next step; there is no
  password-change screen yet.
- **Listings are unpaginated.** Fine for a few hundred rows, not for tens of
  thousands.

## Licence

MIT. See [LICENSE](LICENSE).
