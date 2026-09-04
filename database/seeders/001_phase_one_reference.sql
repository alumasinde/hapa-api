INSERT INTO modes (mode_key, name, description, is_enabled, priority, created_at, updated_at) VALUES
('normal', 'Normal', 'Everyday location awareness mode', TRUE, 0, UTC_TIMESTAMP(), UTC_TIMESTAMP()),
('election', 'Election', 'Election-specific categories and rules', FALSE, 10, UTC_TIMESTAMP(), UTC_TIMESTAMP()),
('flood', 'Flood', 'Flood and impassable road reporting', FALSE, 20, UTC_TIMESTAMP(), UTC_TIMESTAMP()),
('emergency', 'Emergency', 'Emergency information mode', FALSE, 30, UTC_TIMESTAMP(), UTC_TIMESTAMP());

INSERT INTO categories (category_key, name, description, is_enabled, sort_order, expires_after_minutes, created_at, updated_at) VALUES
('traffic', 'Traffic', 'Traffic incidents and disruptions', TRUE, 10, 240, UTC_TIMESTAMP(), UTC_TIMESTAMP()),
('power', 'Power', 'Power interruptions', TRUE, 20, 1440, UTC_TIMESTAMP(), UTC_TIMESTAMP()),
('water', 'Water', 'Water interruptions', TRUE, 30, 1440, UTC_TIMESTAMP(), UTC_TIMESTAMP()),
('fuel', 'Fuel', 'Fuel shortages and disruptions', TRUE, 40, 720, UTC_TIMESTAMP(), UTC_TIMESTAMP()),
('security', 'Security', 'Security incidents and alerts', TRUE, 50, 360, UTC_TIMESTAMP(), UTC_TIMESTAMP());

INSERT INTO source_types (source_key, name, sort_order, created_at, updated_at) VALUES
('community', 'Community', 10, UTC_TIMESTAMP(), UTC_TIMESTAMP()),
('trusted_contributor', 'Trusted Contributor', 20, UTC_TIMESTAMP(), UTC_TIMESTAMP()),
('verified_organisation', 'Verified Organisation', 30, UTC_TIMESTAMP(), UTC_TIMESTAMP()),
('official', 'Official', 40, UTC_TIMESTAMP(), UTC_TIMESTAMP());

INSERT INTO observation_types (observation_key, label, effect_type, created_at, updated_at) VALUES
('still_happening', 'Still happening', 'confirm', UTC_TIMESTAMP(), UTC_TIMESTAMP()),
('getting_worse', 'Getting worse', 'confirm', UTC_TIMESTAMP(), UTC_TIMESTAMP()),
('improving', 'Improving', 'neutral', UTC_TIMESTAMP(), UTC_TIMESTAMP()),
('cleared', 'Cleared', 'resolve', UTC_TIMESTAMP(), UTC_TIMESTAMP());
