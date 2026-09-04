CREATE TABLE flash_views (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    flash_id BIGINT UNSIGNED NOT NULL,
    viewer_key CHAR(64) NOT NULL,
    viewed_on DATE NOT NULL,
    created_at DATETIME NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_flash_view_daily (flash_id, viewer_key, viewed_on),
    KEY idx_flash_views_flash (flash_id, created_at),
    CONSTRAINT fk_flash_views_flash FOREIGN KEY (flash_id) REFERENCES flashes(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE flash_helpful_reactions (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    flash_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    created_at DATETIME NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_flash_helpful_user (flash_id, user_id),
    KEY idx_flash_helpful_flash (flash_id),
    CONSTRAINT fk_flash_helpful_flash FOREIGN KEY (flash_id) REFERENCES flashes(id) ON DELETE CASCADE,
    CONSTRAINT fk_flash_helpful_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE flash_share_events (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    flash_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NULL,
    created_at DATETIME NOT NULL,
    PRIMARY KEY (id),
    KEY idx_flash_share_events_flash (flash_id, created_at),
    CONSTRAINT fk_flash_share_events_flash FOREIGN KEY (flash_id) REFERENCES flashes(id) ON DELETE CASCADE,
    CONSTRAINT fk_flash_share_events_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;
