DELETE older
FROM flash_reports older
JOIN flash_reports newer
    ON newer.flash_id = older.flash_id
    AND newer.reporter_user_id = older.reporter_user_id
    AND newer.id > older.id;

ALTER TABLE flash_reports
    ADD UNIQUE KEY uq_flash_reporter (flash_id, reporter_user_id);

ALTER TABLE flash_media
    ADD INDEX idx_flash_media_active (flash_id, deleted_at, sort_order);
