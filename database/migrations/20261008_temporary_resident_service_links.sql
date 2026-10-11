CREATE TABLE IF NOT EXISTS service_page_links (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    page_key VARCHAR(80) NOT NULL,
    service_id INT UNSIGNED NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_page_service (page_key, service_id),
    KEY idx_page_order (page_key, sort_order),
    CONSTRAINT fk_service_page_links_service
        FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO service_page_links (page_key, service_id, sort_order)
SELECT 'temporary-resident-card', id,
       CASE slug
           WHEN 'spouse-resident-card' THEN 10
           WHEN 'investor-resident-card' THEN 20
           WHEN 'labor-resident-card' THEN 30
       END
FROM services
WHERE slug IN ('spouse-resident-card', 'investor-resident-card', 'labor-resident-card');
