CREATE TABLE IF NOT EXISTS visa_regions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(100) NOT NULL UNIQUE,
    title VARCHAR(255) NOT NULL,
    title_vn VARCHAR(255) NULL,
    description TEXT NULL,
    description_vn TEXT NULL,
    image VARCHAR(1000) NULL,
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS visa_countries (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    region_id INT UNSIGNED NOT NULL,
    slug VARCHAR(100) NOT NULL,
    title VARCHAR(255) NOT NULL,
    title_vn VARCHAR(255) NULL,
    summary TEXT NULL,
    summary_vn TEXT NULL,
    detail TEXT NULL,
    detail_vn TEXT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_visa_country_region_slug (region_id, slug),
    KEY idx_visa_country_region_order (region_id, sort_order),
    CONSTRAINT fk_visa_countries_region FOREIGN KEY (region_id) REFERENCES visa_regions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO visa_regions (slug, title, title_vn, sort_order)
VALUES
    ('global', 'USA, UK, Canada, Australia & New Zealand', 'Visa Mỹ, Anh, Canada, Úc & New Zealand', 10),
    ('schengen', 'Europe Countries – Schengen Visa', 'Visa châu Âu – Schengen', 20),
    ('china', 'China Visa', 'Visa Trung Quốc', 30),
    ('asia', 'Asia Countries Visa', 'Visa các nước châu Á', 40)
ON DUPLICATE KEY UPDATE title=VALUES(title), title_vn=VALUES(title_vn), sort_order=VALUES(sort_order);
