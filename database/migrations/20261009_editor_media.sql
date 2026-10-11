CREATE TABLE IF NOT EXISTS editor_media (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    admin_id INT UNSIGNED NULL,
    media_type ENUM('image', 'video') NOT NULL,
    file_path VARCHAR(1000) NOT NULL,
    original_name VARCHAR(255) NULL,
    title VARCHAR(255) NULL,
    alt_text VARCHAR(255) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_editor_media_created (created_at, id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
