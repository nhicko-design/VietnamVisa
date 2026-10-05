-- Adds a reusable image and video gallery for News articles.

USE sunviet;

CREATE TABLE IF NOT EXISTS news_media (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    news_id INT UNSIGNED NOT NULL,
    media_type ENUM('image', 'video') NOT NULL DEFAULT 'image',
    file_path VARCHAR(1000) NOT NULL,
    title VARCHAR(255) NULL,
    alt_text VARCHAR(255) NULL,
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_news_media_news
        FOREIGN KEY (news_id)
        REFERENCES news(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    INDEX idx_news_media_news_id (news_id),
    INDEX idx_news_media_type (media_type),
    INDEX idx_news_media_sort_order (sort_order),
    INDEX idx_news_media_active (is_active)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;
