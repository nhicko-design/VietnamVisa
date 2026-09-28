-- ============================================================
-- SUNVIET DATABASE SCHEMA
-- Version: 1.0
-- Compatible with: MySQL / MariaDB
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+07:00";

-- ============================================================
-- DATABASE
-- ============================================================

CREATE DATABASE IF NOT EXISTS sunviet
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE sunviet;


-- ============================================================
-- 1. SERVICES
-- ============================================================
-- Lưu thông tin các dịch vụ của SunViet.
-- Ví dụ:
-- Vietnam E-Visa
-- Business Visa
-- Work Permit
-- Temporary Residence Card
-- ...

CREATE TABLE IF NOT EXISTS services (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,

    category ENUM(
        'visa',
        'support'
    ) NOT NULL DEFAULT 'visa',

    slug VARCHAR(150) NOT NULL,

    title VARCHAR(255) NOT NULL,

    short_description TEXT NULL,

    description LONGTEXT NULL,

    price DECIMAL(10,2) NULL DEFAULT NULL,

    currency VARCHAR(10) NOT NULL DEFAULT 'USD',

    image VARCHAR(500) NULL,

    icon VARCHAR(100) NULL,

    is_featured TINYINT(1) NOT NULL DEFAULT 0,

    is_active TINYINT(1) NOT NULL DEFAULT 1,

    sort_order INT NOT NULL DEFAULT 0,

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id),

    UNIQUE KEY uq_services_slug (slug),

    KEY idx_services_category (category),

    KEY idx_services_active (is_active),

    KEY idx_services_sort_order (sort_order)

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- 2. SERVICE MEDIA
-- ============================================================
-- Lưu hình ảnh / video của từng service.
-- Một service có thể có nhiều media.

CREATE TABLE IF NOT EXISTS service_media (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,

    service_id INT UNSIGNED NOT NULL,

    media_type ENUM(
        'image',
        'video'
    ) NOT NULL DEFAULT 'image',

    file_path VARCHAR(500) NOT NULL,

    title VARCHAR(255) NULL,

    alt_text VARCHAR(255) NULL,

    sort_order INT NOT NULL DEFAULT 0,

    is_active TINYINT(1) NOT NULL DEFAULT 1,

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),

    KEY idx_service_media_service_id (service_id),

    CONSTRAINT fk_service_media_service
        FOREIGN KEY (service_id)
        REFERENCES services(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- 3. NEWS
-- ============================================================
-- Lưu các bài viết / tin tức.

CREATE TABLE IF NOT EXISTS news (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,

    slug VARCHAR(200) NOT NULL,

    title VARCHAR(255) NOT NULL,

    excerpt TEXT NULL,

    content LONGTEXT NULL,

    featured_image VARCHAR(500) NULL,

    author VARCHAR(150) NULL,

    status ENUM(
        'draft',
        'published'
    ) NOT NULL DEFAULT 'draft',

    published_at DATETIME NULL,

    is_featured TINYINT(1) NOT NULL DEFAULT 0,

    sort_order INT NOT NULL DEFAULT 0,

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id),

    UNIQUE KEY uq_news_slug (slug),

    KEY idx_news_status (status),

    KEY idx_news_published_at (published_at),

    KEY idx_news_featured (is_featured),

    KEY idx_news_sort_order (sort_order)

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- 4. HOME MEDIA
-- ============================================================
-- Quản lý hình ảnh / video được sử dụng trên Homepage.
--
-- Ví dụ:
-- Hero background video
-- Film AI video
-- Commercial video
-- AI Imagery
-- ...

CREATE TABLE IF NOT EXISTS home_media (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,

    section VARCHAR(100) NOT NULL,

    media_type ENUM(
        'image',
        'video'
    ) NOT NULL DEFAULT 'image',

    file_path VARCHAR(500) NOT NULL,

    title VARCHAR(255) NULL,

    description TEXT NULL,

    link_url VARCHAR(500) NULL,

    sort_order INT NOT NULL DEFAULT 0,

    is_active TINYINT(1) NOT NULL DEFAULT 1,

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id),

    KEY idx_home_media_section (section),

    KEY idx_home_media_active (is_active),

    KEY idx_home_media_sort_order (sort_order)

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- 5. SETTINGS
-- ============================================================
-- Lưu các thông tin cấu hình nhỏ của website.
--
-- Ví dụ:
-- site_name
-- site_description
-- phone
-- email
-- WhatsApp
-- Messenger
-- Zalo
-- Facebook
-- Address
-- ...

CREATE TABLE IF NOT EXISTS settings (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,

    setting_key VARCHAR(150) NOT NULL,

    setting_value TEXT NULL,

    setting_group VARCHAR(100) NOT NULL DEFAULT 'general',

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id),

    UNIQUE KEY uq_settings_key (setting_key),

    KEY idx_settings_group (setting_group)

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- 6. ADMIN USERS
-- ============================================================
-- Chuẩn bị cho Admin Login.
--
-- Chưa cần sử dụng ngay ở bước này.
-- Password sau này sẽ được lưu bằng password_hash().
-- Không lưu password dạng plain text.

CREATE TABLE IF NOT EXISTS admin_users (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,

    username VARCHAR(100) NOT NULL,

    password_hash VARCHAR(255) NOT NULL,

    full_name VARCHAR(150) NULL,

    email VARCHAR(255) NULL,

    is_active TINYINT(1) NOT NULL DEFAULT 1,

    last_login_at DATETIME NULL,

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id),

    UNIQUE KEY uq_admin_username (username),

    UNIQUE KEY uq_admin_email (email)

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- DEFAULT SETTINGS
-- ============================================================

INSERT INTO settings
    (setting_key, setting_value, setting_group)
VALUES
    ('site_name', 'SunViet', 'general'),

    (
        'site_description',
        'Vietnam Visa and Immigration Services',
        'general'
    ),

    ('contact_phone', '', 'contact'),

    ('contact_email', '', 'contact'),

    ('contact_address', '', 'contact'),

    ('whatsapp_url', '', 'social'),

    ('messenger_url', '', 'social'),

    ('zalo_url', '', 'social'),

    ('facebook_url', '', 'social'),

    ('instagram_url', '', 'social')

ON DUPLICATE KEY UPDATE
    setting_key = VALUES(setting_key);


-- ============================================================
-- END
-- ============================================================