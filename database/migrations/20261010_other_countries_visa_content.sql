CREATE TABLE IF NOT EXISTS visa_content_items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    owner_type ENUM('region', 'country') NOT NULL,
    owner_id INT UNSIGNED NOT NULL,
    item_type ENUM('feature', 'step', 'faq') NOT NULL,
    title VARCHAR(255) NOT NULL,
    title_vn VARCHAR(255) NULL,
    content TEXT NULL,
    content_vn TEXT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY idx_visa_content_owner (owner_type, owner_id, item_type, sort_order),
    KEY idx_visa_content_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
