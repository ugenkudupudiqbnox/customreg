# local_customreg – Developer Memory (Production)

## Environment

- Moodle 5.x
- MariaDB
- nginx
- PHP-FPM
- Production site
- ~400 existing users

---

## CRITICAL RULES (DO NOT BREAK)

1. Never modify install.xml in production.
2. Always use db/upgrade.php for schema changes.
3. Always bump $plugin->version when changing schema.
4. Never drop tables in production.
5. Always purge caches after code changes.
6. Restart PHP-FPM after replacing plugin files.
7. Do NOT uninstall plugin unless full cleanup is intended.
8. Always bump version when a new plugin zip file is created or code is commited/pushed into github repo.

---

## Database Table (Current Schema)

Table: mdl_local_customreg

Fields:

- id (PK)
- userid (unique)
- identitytype
- institutionid
- courseidsjson
- documentuploaded
- status
- verifiedby
- timeverified
- timecreated
- timemodified
- documentrequired (added in v9.x)

---

## Enforcement Model (Production Safe)

Existing users:
- `documentrequired = 0`
- `documentuploaded = 1`
- `status = approved`
- Never redirected, never blocked.

New users (Restricted):
- `documentrequired = 1`
- `documentuploaded = 0` (Initially)
- `status = pending`
- Redirected to `upload.php` after login.
- Cannot access dashboard or courses until approved.

---

## Admin Management (v10.3+)

- **Features**:
  - List of all customized users.
  - Search by Name or Email.
  - Pagination (20 users per page).
  - Status badges (Approved/Pending/Denied).
  - **In-Page Preview**: IDs now open in a large Bootstrap Modal overlay on the same page (no new tabs or windows).
  - **Action Icons**: Standard Moodle icons: **Preview (eye)**, **Tick** (Approve) and **Cross** (Deny).
  - **Deny Action**: 
    - Resets `documentuploaded` to 0.
    - Sets status to `denied`.
    - Deletes physical files to allow a fresh upload.
    - User sees an "Accepted document was not accepted" warning on the upload screen.

---

## Technical Details: File Serving

The plugin uses the `local_customreg_pluginfile` function in `lib.php` to serve uploaded IDs.
- **Context**: System (`CONTEXT_SYSTEM`)
- **Component**: `local_customreg`
- **File Area**: `govid`
- **Item ID**: `{userid}`
- **Permissions**: Only the owner or a Site Administrator can download the files.

Modern namespaced hooks in `classes/hook_handler.php`:

```php
public static function after_signup(\core\hook\user\after_signup $hook)
```
Used to insert row into mdl_local_customreg.

```php
public static function before_http_headers(\core\hook\output\before_http_headers $hook)
```
Used to enforce redirect.

```php
public static function signup_form_definition(\core\hook\user\signup_form_definition $hook)
```
Used to extend signup form.

DO NOT use legacy lib functions for enforcement.

---

## Enforcement Logic

Only enforce when:

```

$rec->documentrequired == 1

```

Redirect when:

```

documentuploaded == 0

```

Block when:

```

status !== 'approved'

```

Always allow:

- upload.php
- CLI scripts
- AJAX scripts
- site admin

---

## Debug Checklist

If redirect not happening:

1. Confirm DB row exists:
   SELECT * FROM mdl_local_customreg WHERE userid = X;

2. Confirm documentrequired = 1

3. Add temporary debug:
   error_log('CUSTOMREG EXECUTED');

4. Purge caches:
   php admin/cli/purge_caches.php

5. Restart PHP-FPM:
   sudo systemctl restart php-fpm (or specific version like php8.3-fpm)

6. Confirm OPcache not serving old file.

---

## Technical Hook Registration (Moodle 5.1+)

Hooks are registered in db/hooks.php using namespaced callbacks in \local_customreg\hook_handler:
- \core\hook\output\before_http_headers
- \core\hook\user\after_signup
- \core\hook\user\signup_form_definition

---

## nginx Notes

nginx does NOT affect PHP hooks.
Redirect logic runs inside PHP.
If redirect fails, issue is inside Moodle/plugin.

---

## Production Upgrade Procedure

When adding fields:

1. Modify db/upgrade.php
2. Add:
   if (!$dbman->field_exists(...)) { add_field(); }
3. Bump version in version.php
4. Visit:
   Site Administration → Notifications
5. Purge caches
6. Restart PHP-FPM

Never edit DB manually unless repairing corruption.

---

## Safe Testing Flow

1. Create test user
2. Confirm DB row inserted
3. Login
4. Expect redirect to:
   /local/customreg/upload.php
5. Upload file
6. Confirm documentuploaded = 1
7. Confirm blocked until admin approves

---

## Approval Workflow (Planned)

Admin page:
- List pending users
- Approve → set status=approved
- Auto-enrol
- Send email

---

## Common Failure Causes

- Wrong hook name
- Missing version bump
- Cache not purged
- PHP-FPM not restarted
- OPcache serving old lib.php
- DB prefix mismatch
- Signup hook not firing

---

## Versioning Strategy

- v9.0 – Clean stable install
- v9.1 – Add documentrequired field
- v9.2 – Fix signup hook
- v9.3 – Use before_http_headers enforcement

Next versions must follow upgrade-safe pattern.

---

## Final Architecture Goal

- Stable enforcement
- Clean approval UI
- Auto-enrol after approval
- Secure pluginfile
- Reporting dashboard
- Audit logging
- No reinstall ever again
```

