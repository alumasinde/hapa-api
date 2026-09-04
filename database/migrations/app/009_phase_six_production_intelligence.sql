CREATE TABLE flash_intelligence (
    flash_id BIGINT UNSIGNED PRIMARY KEY,
    confidence_score DECIMAL(5,2) NOT NULL DEFAULT 0,
    trust_score DECIMAL(5,2) NOT NULL DEFAULT 0,
    confirmation_score DECIMAL(5,2) NOT NULL DEFAULT 0,
    report_penalty DECIMAL(5,2) NOT NULL DEFAULT 0,
    state VARCHAR(30) NOT NULL DEFAULT 'new',
    last_evaluated_at DATETIME NOT NULL,
    FOREIGN KEY (flash_id) REFERENCES flashes(id) ON DELETE CASCADE,
    INDEX idx_flash_intelligence_score (confidence_score, state)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE user_trust_profiles (
    user_id BIGINT UNSIGNED PRIMARY KEY,
    trust_score DECIMAL(5,2) NOT NULL DEFAULT 50,
    successful_reports INT UNSIGNED NOT NULL DEFAULT 0,
    harmful_reports INT UNSIGNED NOT NULL DEFAULT 0,
    abuse_events INT UNSIGNED NOT NULL DEFAULT 0,
    last_evaluated_at DATETIME NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_trust_score (trust_score)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE abuse_events (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    event_type VARCHAR(80) NOT NULL,
    severity TINYINT UNSIGNED NOT NULL DEFAULT 1,
    subject_type VARCHAR(80) NULL,
    subject_id BIGINT UNSIGNED NULL,
    metadata JSON NULL,
    created_at DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_abuse_user_created (user_id, created_at),
    INDEX idx_abuse_type_created (event_type, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE jobs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    job_type VARCHAR(100) NOT NULL,
    payload JSON NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'pending',
    attempts TINYINT UNSIGNED NOT NULL DEFAULT 0,
    available_at DATETIME NOT NULL,
    reserved_at DATETIME NULL,
    completed_at DATETIME NULL,
    last_error TEXT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    INDEX idx_jobs_queue (status, available_at, id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE INDEX idx_flashes_created_id ON flashes (created_at, id);
CREATE INDEX idx_flash_reports_flash_status ON flash_reports (flash_id, status);
CREATE INDEX idx_observations_flash_type ON flash_observations (flash_id, observation_type_id);

INSERT INTO settings (setting_key, setting_value, created_at, updated_at) VALUES
('intelligence.base_confidence', '35', UTC_TIMESTAMP(), UTC_TIMESTAMP()),
('intelligence.confirm_weight', '12', UTC_TIMESTAMP(), UTC_TIMESTAMP()),
('intelligence.clear_weight', '18', UTC_TIMESTAMP(), UTC_TIMESTAMP()),
('intelligence.report_penalty', '8', UTC_TIMESTAMP(), UTC_TIMESTAMP()),
('intelligence.min_trust_score', '20', UTC_TIMESTAMP(), UTC_TIMESTAMP()),
('lifecycle.auto_resolve_cleared_count', '3', UTC_TIMESTAMP(), UTC_TIMESTAMP()),
('lifecycle.auto_resolve_margin', '1', UTC_TIMESTAMP(), UTC_TIMESTAMP()),
('abuse.max_events_before_suspend_review', '5', UTC_TIMESTAMP(), UTC_TIMESTAMP())
ON DUPLICATE KEY UPDATE updated_at = updated_at;