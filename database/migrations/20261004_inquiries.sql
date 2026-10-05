CREATE TABLE IF NOT EXISTS inquiries (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    inquiry_type ENUM('contact', 'application') NOT NULL,
    status ENUM('new', 'in_progress', 'closed') NOT NULL DEFAULT 'new',

    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NULL,
    service VARCHAR(255) NULL,
    nationality VARCHAR(100) NULL,
    entry_date DATE NULL,
    entry_point VARCHAR(255) NULL,
    message TEXT NULL,

    language_code CHAR(2) NOT NULL DEFAULT 'en',
    source_url VARCHAR(1000) NULL,
    ip_hash CHAR(64) NULL,

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_inquiries_type (inquiry_type),
    INDEX idx_inquiries_status (status),
    INDEX idx_inquiries_created_at (created_at)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;
