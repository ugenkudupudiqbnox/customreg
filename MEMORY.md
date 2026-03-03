# local_customreg – Developer Memory (Production)

## Environment

- Moodle 5.1.1 and above
- MariaDB
- nginx or apache
- PHP-FPM


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

## Database Schema (Production)

### Table: `local_customreg`
- `userid` (int, unique index)
- `identitytype` (char 50: 'new', 'existing', etc.)
- `institutionid` (char 100)
- `courseidsjson` (text, JSON list of courses assigned)
- `documentuploaded` (bool)
- `documentrequired` (bool)
- `status` (char 50: 'pending', 'approved', 'rejected')
- `verifiedby` (int, admin userid)
- `timeverified` (int, timestamp)
- `admin_comments` (text)

### Table: `local_customreg_logs`
- `userid` (int)
- `adminid` (int)
- `action` (char 50: 'raised', 'approved', 'rejected')
- `details` (text)

---

## Migration History

| Version | Action |
| :--- | :--- |
| `2026022419` | Created `local_customreg` table |
| `2026022432` | Created `local_customreg_logs` table |
| `2026030401` | Added `admin_comments` field to `local_customreg` |
| `2026030402` | UI Refined: Redirected Rejected users back to upload |
| `2026030403` | v2.1.0: Default Admin Comments, 25 Languages & Email Subject Fix |

---

## Technical Sessions Context

### v2.0.0 Feature Expansion (3-4 March 2026)
- **Email Notifications**: Integrated `email_to_user` for all status changes.
- **Events API**: Added `registration_updated` event for Moodle log visibility.
- **Form Logic**: Implemented `hideIf` in `upload.php` for cleaner user experience.
- **Multi-Course Approval**: Added logic to approve/deny individual courses within the admin dashboard.

### v2.1.0 Admin UX & Localization
- **Default Comments**: Populated the admin modal with editable default text (`default_approve_comment`, `default_deny_comment`) to speed up workflow.
- **AMD Integration**: Used `core/modal_factory` to inject PHP language strings into the JS layer via `js_amd_inline`.
- **Global Reach**: Localized 15+ new strings for 25 languages (ar, bn, de, es, fr, gu, hi, id, it, ja, kn, ko, ml, mr, nl, or, pa, pt, ru, ta, te, tr, ur, zh_cn).
- **Subject Standardization**: Changed "Registration Approved" to "Course Enrollment Approved" to better reflect the service intent.

### Production Environment (learn.qbnox.com)
- **CLI Upgrades**: Large schema changes on this host require `admin/cli/upgrade.php` with `php -d max_input_vars=5000`.
- **Cache Management**: Always run `purge_caches.php` after deploying new `.php` or `.js` files.

---

## Core Logic & Enforcement

### Enforcement Policy
- **Global**: All students are redirected to `upload.php` if `documentrequired = 1` and `status != 'approved'`.
- **Exemptions**: Admins, Managers, Course Creators.
- **Auto-Enforce**: If a user record is missing during `before_http_headers`, a 'pending' record is auto-generated in `local_customreg`.

### Signup Logic
- Users who sign up with specific institutional IDs may have different `documentrequired` flags (managed in [classes/hook_handler.php](classes/hook_handler.php)).

### 5-Course Limit (Admin)
- Enforce a 5-course limit in administrative interfaces for regular admin users. (v11.5)

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

