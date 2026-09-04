INSERT IGNORE INTO permissions (permission_key, name, created_at, updated_at) VALUES
('flashes.moderate', 'Moderate flashes', UTC_TIMESTAMP(), UTC_TIMESTAMP()),
('users.read', 'View users', UTC_TIMESTAMP(), UTC_TIMESTAMP()),
('users.manage', 'Manage users', UTC_TIMESTAMP(), UTC_TIMESTAMP()),
('roles.manage', 'Manage roles and permissions', UTC_TIMESTAMP(), UTC_TIMESTAMP()),
('settings.manage', 'Manage application settings', UTC_TIMESTAMP(), UTC_TIMESTAMP());

INSERT IGNORE INTO roles (role_key, name, created_at, updated_at) VALUES
('super_admin', 'Super Administrator', UTC_TIMESTAMP(), UTC_TIMESTAMP()),
('moderator', 'Moderator', UTC_TIMESTAMP(), UTC_TIMESTAMP());

INSERT IGNORE INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id
FROM roles r
JOIN permissions p
WHERE r.role_key = 'super_admin';

INSERT IGNORE INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id
FROM roles r
JOIN permissions p
WHERE r.role_key = 'moderator' AND p.permission_key IN ('flashes.moderate', 'users.read');

INSERT INTO settings (setting_key, setting_value, created_at, updated_at)
VALUES ('moderation.auto_hide_report_threshold', '5', UTC_TIMESTAMP(), UTC_TIMESTAMP())
ON DUPLICATE KEY UPDATE updated_at = updated_at;
