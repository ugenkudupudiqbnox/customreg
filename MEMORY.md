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

## Final Architecture Goal

- Stable enforcement
- Clean approval UI
- Auto-enrol after approval
- Secure pluginfile
- Reporting dashboard
- Audit logging
- No reinstall ever again
```

