# Changes — Deep Review & Fixes

A full audit of the project was performed. All changes here address bugs found in mail delivery, broken text rendering, the 3D system you wanted removed, and several smaller correctness issues across the auth, admin, and frontend layers.

The SMTP setup in `.env` is correct and was verified end-to-end with a live test send (Gmail SMTP returned OK).

---

## 1. Mail — why mails were not going, and the fix

### Root cause
`app/Mail/TwoFactorOtpMail.php` implemented `Illuminate\Contracts\Queue\ShouldQueue`, but **no other mailable** in the project did. Combined with `QUEUE_CONNECTION=database` in `.env`, every 2FA OTP email was pushed into the `jobs` table and waited for a queue worker that was never started. Result: login OTP emails appeared to "not go", while the contact / password-reset emails (which were not queued) worked.

Confirmation: `jobs` table currently contains **5 stuck mail jobs** from past 2FA attempts.

### Fixes applied
- **`app/Mail/TwoFactorOtpMail.php`** — removed `implements ShouldQueue` and the matching import. OTP mails now send synchronously like the other mailables, so they go out the moment login succeeds.
- **`app/Http/Controllers/Frontend/ContactController.php`** — replaced the silent `catch (\Exception $e) { }` on `sendContactReceived` with a `Log::error(...)` so future delivery failures surface in `storage/logs/laravel.log` instead of being hidden.
- **`app/Http/Controllers/Admin/ContactQueryController.php`** — wrapped `sendContactReply` in `try/catch`, log on failure, and flash a clear error to the admin if mail send fails (the reply itself is still saved).

### Action you should take once
The 5 old jobs in the `jobs` table are expired OTPs from before the fix. Run this to clear them:

```bash
php artisan queue:flush
```

You do **not** need to run a queue worker for mail anymore — nothing in the project is queued.

---

## 2. Text not displaying — contact page

### Root cause
In `resources/views/frontend/contact.blade.php`, the entire contact-info column (email / location / phone cards, social-links card, availability card) was wrapped inside a `<canvas id="contact-canvas">` element. Anything inside a `<canvas>` is treated by the browser as **fallback content for browsers that do not support canvas** — in every modern browser this content is hidden. That's why the right-hand info column appeared blank.

### Fix
Replaced the wrapping `<canvas>` with a normal `<div id="contact-info">` that uses the same flex column layout. All three info cards plus the social-links and availability cards now render correctly.

---

## 3. 3D system — removed as requested

You asked for the 3D models / Three.js layer to be removed for now. Everything 3D-related is no longer loaded by the site. Models and JS files were left on disk so you can re-enable later — only the `<script>` and `<canvas>` hooks were stripped.

### `resources/views/layouts/app.blade.php`
- Removed all 3D `<script>` tags: `three-scene.js`, `anime-character.js`, `floating-objects.js`, `scene-environment.js`, `section-scenes.js`, `scene-quality.js`, `three-boot.js`.
- Removed the Three.js CDN: `https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js`.
- Removed the Locomotive Scroll CDN JS + CSS (it depended on the 3D scroll proxy) and the `locomotive-init.js` script.
- Removed the `data-scroll-container` attribute from the main wrapper and the `body.loco-ready { overflow:hidden }` CSS rule (this rule was hiding the page if locomotive ever failed to initialise).
- Kept the sparkle / sakura 2D canvases — they are not 3D, they are pure 2D and look nice on the dark theme.
- Kept `mouse-interactions.js` (card tilt effect — does **not** require Three.js, verified by grep).

### `resources/views/frontend/home.blade.php`
- Removed `<canvas id="hero-canvas">` from the hero section.
- Removed the large commented-out Three.js hero scene `@push('scripts')` block that was dead code.

### `app/Http/Middleware/SecurityHeaders.php`
- CSP tightened: removed `unpkg.com`, `cdn.quilljs.com`, `picsum.photos`, `loremflickr.com` (none referenced anywhere). Kept `cdnjs.cloudflare.com`, `cdn.jsdelivr.net`, `cdn.tailwindcss.com`, `fonts.googleapis.com`, and `api.qrserver.com` (used by the 2FA QR generator).

### Files left on disk (untouched, ready for re-enable later)
```
public/assets/js/three-scene.js
public/assets/js/three-boot.js
public/assets/js/anime-character.js
public/assets/js/floating-objects.js
public/assets/js/scene-environment.js
public/assets/js/section-scenes.js
public/assets/js/scene-quality.js
public/assets/js/locomotive-init.js
public/assets/models/character.glb
public/assets/models/3414765179067022604.vrm
```

---

## 4. Auth — password reset hardening

### `app/Http/Controllers/Auth/PasswordResetController.php`
- The old `reset()` method loaded **every** row from `password_reset_tokens` and iterated them in PHP to find a Hash match. Rewrote to query directly by the `email` hidden field and then verify the token hash on that single row.
- Added `email` to the validation rules (it was being read but not validated).
- The expiry check was `now()->diffInMinutes($r->created_at) <= 60`. In recent Carbon versions `diffInMinutes` is signed and can return a negative number, which would silently pass the comparison. Now uses `abs(Carbon::parse(...)->diffInMinutes(now())) > $expiryMinutes` and pulls the limit from `config('portfolio.admin.reset_expiry_minutes')`.
- On invalid/expired link, the email is preserved with `withInput()` so the user doesn't have to retype.

---

## 5. Small view / display fixes

### `resources/views/components/shared/meta-tags.blade.php`
Title used to render as `Site Name — Site Name` on the home page because the layout passes `config('portfolio.site_name')` as the default title and the template appends it again. Now skips the suffix when title already equals the site name.

### `resources/views/components/shared/flash-message.blade.php`
- Both flash blocks used the same `id="flash-msg"`, so when both `success` and `error` were present (rare but possible), only the first one got dismissed by the timeout. Switched to `data-flash` attribute and `querySelectorAll(...).forEach(...)` so both clear.
- The error flash gets bumped up `bottom:5.5rem` so it doesn't overlap the success flash if both fire.

### `resources/views/frontend/services.blade.php`
Line 69 had `data-counter={{ $stat['value'] }}` (unquoted) and the values contain `+` (e.g. `50+`). Browsers parse that as a malformed attribute and may drop characters. Quoted the attribute.

### `resources/views/admin/skills/index.blade.php`
The Sortable.js + Chart.js `<script>` tags were inside `@push('styles')` instead of `@push('scripts')`. The push target is just a string match — they ended up rendered inside `<head>` between `@stack('styles')` calls, which is technically allowed, **but** in the admin layout `@stack('styles')` is rendered before the closing `</head>` while the admin layout only has `@stack('scripts')` at the very end of `<body>`. Either order worked, but the convention was being violated and made the reorder JS load early relative to the DOM. Moved to `@push('scripts')`.

---

## 6. Model & controller correctness

### `app/Models/Project.php`
Added missing casts so booleans and integers come back from the DB with the right types:
```php
'is_published' => 'boolean',
'sort_order'   => 'integer',
```
`is_published` was in `$fillable` (and has a migration) but had no cast, so it was returning as a `0/1` string in some places.

### `app/Http/Controllers/Frontend/HomeController.php`
Removed a large commented-out alternate "cached" version of the controller that lived below the actual class. Dead code, kept things noisy.

---

## 7. What was reviewed and found to be fine (so you know it was checked)

- `app/Services/MailService.php`, `OtpService.php`, `TwoFactorService.php` — clean.
- All Mailables: `PasswordResetMail`, `ContactReceivedMail`, `ContactReplyMail`, `TwoFactorOtpMail` (now non-queued).
- All four mail Blade templates render correctly with safe escaping.
- `LoginController`, `TwoFactorController`, `ForgotPasswordController`, `AdminAuthenticated`, `TwoFactorVerified` middleware — logic correct.
- `ImageUploadService`, `AnalyticsService` — fine.
- All admin controllers (Project, Blog, Contact, Profile, Skill, Dashboard) — validation, fillable, and CSRF all correct.
- Frontend controllers (Home, About, Project, Blog, Service, Contact) — fine.
- `bootstrap/app.php` middleware registration — correct.
- Models: `User`, `Blog`, `Skill`, `BlogComment`, `ContactQueries`, `ContactReplies`, `TwoFactorToken` — fine.

---

## 8. How everything was tested

```bash
php -l   # on every edited PHP file → no syntax errors
php artisan view:clear
php artisan config:clear
php artisan route:clear
php artisan view:cache       # all blade templates compile cleanly
php artisan route:list       # 70 routes resolve, controllers + middleware all bind
# Live mail test
php artisan tinker --execute="Mail::raw('test', fn(\$m)=>\$m->to(env('MAIL_FROM_ADDRESS'))->subject('test'));"
# → SENT OK (Gmail SMTP delivered)
```

All checks pass.

---

## 9. Things you may want to follow up on (not changed)

These are observations, not bugs — flagging in case you want them tightened later:

1. **Tailwind setup is hybrid.** `package.json` has Tailwind v4 + Vite, but `layouts/app.blade.php` and `layouts/admin.blade.php` both pull Tailwind v3 from `cdn.tailwindcss.com`. The CDN is what actually styles the site; the local `tailwind.config.js` and `resources/css/app.css` aren't loaded. Two valid paths: (a) commit to CDN and delete the local Tailwind/Vite scaffolding, or (b) wire up Vite (`@vite('resources/css/app.css')` in both layouts, drop the CDN). CDN is fine for personal-portfolio scale.
2. **`.env.example` defaults `MAIL_MAILER=smtp`** with Mailgun. Your real `.env` uses Gmail — fine for personal use, but Gmail rate-limits free accounts at ~500/day. If you ever go public, switch to a transactional provider (Postmark / Resend / SES).
3. **`AdminUserSeeder` and `SkillSeeder`** were not reviewed in depth — let me know if you'd like an audit.
4. **`.env`** is committed in this branch (visible in `git status` via working directory only — not in the repo). It contains a real Gmail app password. Make sure it stays out of version control; `.gitignore` should already cover it.
