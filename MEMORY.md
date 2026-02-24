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
- documentrequired = 0
- documentuploaded = 1
- status = approved
- Never redirected
- Never blocked

New users:
- documentrequired = 1
- documentuploaded = 0
- status = pending
- Redirected after login
- Blocked until approved

---

## Correct Hooks (Moodle 5.x)

### Signup Hook

```

function local_customreg_after_signup($user, $data)

```

Used to insert row into mdl_local_customreg.

### Enforcement Hook (Bulletproof)

```

function local_customreg_before_http_headers()

```

Used to enforce redirect.

DO NOT use after_require_login in Moodle 5.x for enforcement.

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
   sudo systemctl restart php8.1-fpm

6. Confirm OPcache not serving old file.

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

