ALTER TABLE rate_limits
    ADD INDEX idx_rate_limits_lookup (scope_key, identifier, expires_at);
