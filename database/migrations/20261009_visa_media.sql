CREATE TABLE IF NOT EXISTS visa_media (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    region_id INT UNSIGNED NULL,
    country_id INT UNSIGNED NULL,
    media_type ENUM('image', 'video') NOT NULL,
    file_path VARCHAR(1000) NOT NULL,
    title VARCHAR(255) NULL,
    alt_text VARCHAR(255) NULL,
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_visa_media_region FOREIGN KEY (region_id) REFERENCES visa_regions(id) ON DELETE CASCADE,
    CONSTRAINT fk_visa_media_country FOREIGN KEY (country_id) REFERENCES visa_countries(id) ON DELETE CASCADE,
    INDEX idx_visa_media_region (region_id, sort_order),
    INDEX idx_visa_media_country (country_id, sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
