CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    display_name VARCHAR(120) NOT NULL,
    phone VARCHAR(20) NULL UNIQUE,
    email VARCHAR(190) NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    pin_hash VARCHAR(255) NULL,
    phone_verified_at DATETIME NULL,
    email_verified_at DATETIME NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'active',
    last_login_at DATETIME NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    deleted_at DATETIME NULL,
    INDEX idx_users_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE user_sessions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    refresh_token_hash VARCHAR(255) NOT NULL UNIQUE,
    device_id VARCHAR(191) NULL,
    platform VARCHAR(30) NULL,
    expires_at DATETIME NOT NULL,
    revoked_at DATETIME NULL,
    last_used_at DATETIME NULL,
    created_at DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    INDEX idx_sessions_user_active (user_id, revoked_at, expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE user_devices (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    device_identifier VARCHAR(191) NOT NULL,
    platform VARCHAR(30) NOT NULL,
    device_name VARCHAR(120) NULL,
    push_token TEXT NULL,
    last_seen_at DATETIME NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    UNIQUE KEY uq_user_device (user_id, device_identifier),
    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE otps (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    destination VARCHAR(191) NOT NULL,
    purpose VARCHAR(50) NOT NULL,
    code_hash VARCHAR(255) NOT NULL,
    attempts SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    expires_at DATETIME NOT NULL,
    consumed_at DATETIME NULL,
    created_at DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    INDEX idx_otps_lookup (destination, purpose, expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE user_preferences (
    user_id BIGINT UNSIGNED PRIMARY KEY,
    default_radius_km TINYINT UNSIGNED NOT NULL DEFAULT 5,
    category_filters JSON NULL,
    notification_preferences JSON NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE modes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    mode_key VARCHAR(80) NOT NULL UNIQUE,
    name VARCHAR(120) NOT NULL,
    description VARCHAR(500) NULL,
    is_enabled BOOLEAN NOT NULL DEFAULT FALSE,
    starts_at DATETIME NULL,
    ends_at DATETIME NULL,
    priority INT NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_key VARCHAR(80) NOT NULL UNIQUE,
    name VARCHAR(120) NOT NULL,
    description VARCHAR(500) NULL,
    icon VARCHAR(120) NULL,
    is_enabled BOOLEAN NOT NULL DEFAULT TRUE,
    sort_order INT NOT NULL DEFAULT 0,
    expires_after_minutes INT UNSIGNED NOT NULL,
    max_expires_after_minutes INT UNSIGNED NULL,
    source_verification_required BOOLEAN NOT NULL DEFAULT FALSE,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    INDEX idx_categories_enabled_sort (is_enabled, sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE mode_categories (
    mode_id BIGINT UNSIGNED NOT NULL,
    category_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (mode_id, category_id),
    FOREIGN KEY (mode_id) REFERENCES modes(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE source_types (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    source_key VARCHAR(80) NOT NULL UNIQUE,
    name VARCHAR(120) NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE observation_types (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    observation_key VARCHAR(80) NOT NULL UNIQUE,
    label VARCHAR(120) NOT NULL,
    effect_type VARCHAR(40) NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE category_observation_types (
    category_id BIGINT UNSIGNED NOT NULL,
    observation_type_id BIGINT UNSIGNED NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    PRIMARY KEY (category_id, observation_type_id),
    FOREIGN KEY (category_id) REFERENCES categories(id),
    FOREIGN KEY (observation_type_id) REFERENCES observation_types(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE flashes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    category_id BIGINT UNSIGNED NOT NULL,
    source_type_id BIGINT UNSIGNED NOT NULL,
    description VARCHAR(500) NULL,
    location POINT SRID 4326 NOT NULL,
    area_name VARCHAR(191) NULL,
    lifecycle_status VARCHAR(30) NOT NULL DEFAULT 'active',
    verification_state VARCHAR(40) NOT NULL DEFAULT 'unverified',
    moderation_status VARCHAR(30) NOT NULL DEFAULT 'visible',
    expires_at DATETIME NOT NULL,
    resolved_at DATETIME NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (category_id) REFERENCES categories(id),
    FOREIGN KEY (source_type_id) REFERENCES source_types(id),
    SPATIAL INDEX idx_flashes_location (location),
    INDEX idx_flashes_feed (lifecycle_status, moderation_status, expires_at, created_at),
    INDEX idx_flashes_category (category_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE flash_media (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    flash_id BIGINT UNSIGNED NOT NULL,
    media_type VARCHAR(30) NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    thumbnail_path VARCHAR(500) NULL,
    sort_order SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    width INT UNSIGNED NULL,
    height INT UNSIGNED NULL,
    file_size BIGINT UNSIGNED NULL,
    mime_type VARCHAR(100) NOT NULL,
    created_at DATETIME NOT NULL,
    deleted_at DATETIME NULL,
    FOREIGN KEY (flash_id) REFERENCES flashes(id),
    INDEX idx_flash_media_order (flash_id, sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE flash_observations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    flash_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    observation_type_id BIGINT UNSIGNED NOT NULL,
    note VARCHAR(300) NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    FOREIGN KEY (flash_id) REFERENCES flashes(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (observation_type_id) REFERENCES observation_types(id),
    UNIQUE KEY uq_flash_user_observation (flash_id, user_id, observation_type_id),
    INDEX idx_observations_flash_created (flash_id, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE flash_reports (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    flash_id BIGINT UNSIGNED NOT NULL,
    reporter_user_id BIGINT UNSIGNED NOT NULL,
    reason VARCHAR(80) NOT NULL,
    description VARCHAR(500) NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'open',
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    FOREIGN KEY (flash_id) REFERENCES flashes(id),
    FOREIGN KEY (reporter_user_id) REFERENCES users(id),
    INDEX idx_flash_reports_status (status, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE audit_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    actor_type VARCHAR(50) NOT NULL,
    actor_id BIGINT UNSIGNED NULL,
    action VARCHAR(120) NOT NULL,
    subject_type VARCHAR(80) NOT NULL,
    subject_id BIGINT UNSIGNED NULL,
    metadata JSON NULL,
    ip_address VARBINARY(16) NULL,
    created_at DATETIME NOT NULL,
    INDEX idx_audit_subject (subject_type, subject_id),
    INDEX idx_audit_actor (actor_type, actor_id),
    INDEX idx_audit_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
