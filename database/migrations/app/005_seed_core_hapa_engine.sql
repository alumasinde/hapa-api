INSERT INTO modes (mode_key, name, description, is_enabled, priority, created_at, updated_at)
VALUES ('normal', 'Normal', 'Everyday Hapa updates', 1, 0, UTC_TIMESTAMP(), UTC_TIMESTAMP())
ON DUPLICATE KEY UPDATE name = VALUES(name), description = VALUES(description), updated_at = UTC_TIMESTAMP();

INSERT INTO categories (category_key, name, description, icon, is_enabled, sort_order, expires_after_minutes, created_at, updated_at)
VALUES
('traffic', 'Traffic', 'Traffic incidents and road conditions', 'traffic', 1, 10, 240, UTC_TIMESTAMP(), UTC_TIMESTAMP()),
('power', 'Power', 'Electricity outages and restoration updates', 'power', 1, 20, 1440, UTC_TIMESTAMP(), UTC_TIMESTAMP()),
('water', 'Water', 'Water outages and supply updates', 'water', 1, 30, 1440, UTC_TIMESTAMP(), UTC_TIMESTAMP()),
('fuel', 'Fuel', 'Fuel availability and shortage updates', 'fuel', 1, 40, 720, UTC_TIMESTAMP(), UTC_TIMESTAMP()),
('security', 'Security', 'Security incidents and local safety updates', 'security', 1, 50, 360, UTC_TIMESTAMP(), UTC_TIMESTAMP()),
('election', 'Election', 'Election and voting updates', 'election', 0, 60, 720, UTC_TIMESTAMP(), UTC_TIMESTAMP())
ON DUPLICATE KEY UPDATE name = VALUES(name), description = VALUES(description), icon = VALUES(icon), expires_after_minutes = VALUES(expires_after_minutes), updated_at = UTC_TIMESTAMP();

INSERT INTO source_types (source_key, name, sort_order, created_at, updated_at)
VALUES
('user', 'User report', 10, UTC_TIMESTAMP(), UTC_TIMESTAMP()),
('official', 'Official source', 20, UTC_TIMESTAMP(), UTC_TIMESTAMP()),
('verified', 'Verified source', 30, UTC_TIMESTAMP(), UTC_TIMESTAMP())
ON DUPLICATE KEY UPDATE name = VALUES(name), sort_order = VALUES(sort_order), updated_at = UTC_TIMESTAMP();

INSERT INTO observation_types (observation_key, label, effect_type, created_at, updated_at)
VALUES
('still_happening', 'Still happening', 'confirm', UTC_TIMESTAMP(), UTC_TIMESTAMP()),
('cleared', 'Cleared', 'dispute', UTC_TIMESTAMP(), UTC_TIMESTAMP())
ON DUPLICATE KEY UPDATE label = VALUES(label), effect_type = VALUES(effect_type), updated_at = UTC_TIMESTAMP();

INSERT IGNORE INTO category_observation_types (category_id, observation_type_id, sort_order)
SELECT c.id, o.id, CASE o.observation_key WHEN 'still_happening' THEN 10 ELSE 20 END
FROM categories c
JOIN observation_types o ON o.observation_key IN ('still_happening', 'cleared');
