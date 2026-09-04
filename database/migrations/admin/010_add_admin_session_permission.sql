INSERT IGNORE INTO permissions (permission_key, name, created_at, updated_at) VALUES ('admin.session', 'Use admin sessions', UTC_TIMESTAMP(), UTC_TIMESTAMP());

INSERT IGNORE INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id
FROM roles r
JOIN permissions p
WHERE r.role_key IN ('super_admin', 'moderator') AND p.permission_key = 'admin.session';
