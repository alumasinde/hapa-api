DELETE older
FROM flash_observations older
JOIN flash_observations newer
    ON newer.flash_id = older.flash_id
    AND newer.user_id = older.user_id
    AND (
        newer.updated_at > older.updated_at
        OR (newer.updated_at = older.updated_at AND newer.id > older.id)
    );

ALTER TABLE flash_observations
    DROP INDEX uq_flash_user_observation,
    ADD UNIQUE KEY uq_flash_user (flash_id, user_id);
