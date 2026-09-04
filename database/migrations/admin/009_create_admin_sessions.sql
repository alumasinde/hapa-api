CREATE TABLE admin_sessions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    admin_user_id BIGINT UNSIGNED NOT NULL,
    revoked_at DATETIME NULL,
    last_used_at DATETIME NULL,
    created_at DATETIME NOT NULL,
    FOREIGN KEY (admin_user_id) REFERENCES admin_users(id),
    INDEX idx_admin_sessions_active (admin_user_id, revoked_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
