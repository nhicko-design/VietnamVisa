-- ============================================================
-- SUNVIET DATABASE
-- Master SQL file
-- Project: C:\xampp\htdocs\VietnamVisa
-- Database: sunviet
-- Charset: utf8mb4
-- ============================================================

CREATE DATABASE IF NOT EXISTS sunviet
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE sunviet;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- DROP EXISTING TABLES
-- ============================================================

DROP TABLE IF EXISTS service_faq;
DROP TABLE IF EXISTS service_steps;
DROP TABLE IF EXISTS service_features;
DROP TABLE IF EXISTS service_media;
DROP TABLE IF EXISTS news_media;
DROP TABLE IF EXISTS services;
DROP TABLE IF EXISTS news;
DROP TABLE IF EXISTS home_media;
DROP TABLE IF EXISTS inquiries;
DROP TABLE IF EXISTS settings;
DROP TABLE IF EXISTS admin_users;

-- ============================================================
-- 1. ADMIN USERS
-- ============================================================

CREATE TABLE admin_users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    username VARCHAR(100) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,

    full_name VARCHAR(255) NULL,
    email VARCHAR(255) NULL,

    is_active TINYINT(1) NOT NULL DEFAULT 1,

    last_login_at DATETIME NULL,

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE KEY uq_admin_users_username (username),
    UNIQUE KEY uq_admin_users_email (email)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- 2. SETTINGS
-- ============================================================

CREATE TABLE settings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    setting_key VARCHAR(150) NOT NULL,
    setting_value TEXT NULL,
    setting_group VARCHAR(100) NULL,

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE KEY uq_settings_key (setting_key),
    INDEX idx_settings_group (setting_group)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- 3. CUSTOMER INQUIRIES
-- ============================================================

CREATE TABLE inquiries (
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


-- ============================================================
-- 4. HOME MEDIA
-- ============================================================

CREATE TABLE home_media (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    section VARCHAR(100) NOT NULL,

    media_type ENUM('image', 'video') NOT NULL,

    file_path VARCHAR(1000) NOT NULL,

    title VARCHAR(255) NULL,
    description TEXT NULL,
    link_url VARCHAR(1000) NULL,

    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_home_media_section (section),
    INDEX idx_home_media_sort_order (sort_order),
    INDEX idx_home_media_active (is_active)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- 4. NEWS
-- ============================================================

CREATE TABLE news (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    slug VARCHAR(255) NOT NULL,

    category VARCHAR(100) NULL,
    category_vn VARCHAR(100) NULL,

    title VARCHAR(255) NOT NULL,
    title_vn VARCHAR(255) NULL,
    excerpt TEXT NULL,
    excerpt_vn TEXT NULL,
    content LONGTEXT NULL,
    content_vn LONGTEXT NULL,

    featured_image VARCHAR(1000) NULL,

    author VARCHAR(255) NULL,

    status ENUM('draft', 'published') NOT NULL DEFAULT 'draft',

    published_at DATETIME NULL,

    is_featured TINYINT(1) NOT NULL DEFAULT 0,
    sort_order INT NOT NULL DEFAULT 0,

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE KEY uq_news_slug (slug),

    INDEX idx_news_status (status),
    INDEX idx_news_published_at (published_at),
    INDEX idx_news_featured (is_featured),
    INDEX idx_news_sort_order (sort_order)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- 5. NEWS MEDIA
-- ============================================================

CREATE TABLE news_media (
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


-- ============================================================
-- 6. SERVICES
-- ============================================================

CREATE TABLE services (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    category VARCHAR(50) NOT NULL,

    slug VARCHAR(255) NOT NULL,

    -- English
    title VARCHAR(255) NOT NULL,
    short_description TEXT NULL,
    description TEXT NULL,

    -- Vietnamese
    title_vn VARCHAR(255) NULL,
    short_description_vn TEXT NULL,
    description_vn TEXT NULL,

    price DECIMAL(10,2) NULL,
    currency VARCHAR(10) NOT NULL DEFAULT 'USD',

    image VARCHAR(1000) NULL,
    icon VARCHAR(50) NULL,

    is_featured TINYINT(1) NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,

    sort_order INT NOT NULL DEFAULT 0,

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE KEY uq_services_slug (slug),

    INDEX idx_services_category (category),
    INDEX idx_services_featured (is_featured),
    INDEX idx_services_active (is_active),
    INDEX idx_services_sort_order (sort_order)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- 6. SERVICE MEDIA
-- ============================================================

CREATE TABLE service_media (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    service_id INT UNSIGNED NOT NULL,

    media_type ENUM('image', 'video') NOT NULL,

    file_path VARCHAR(1000) NOT NULL,

    title VARCHAR(255) NULL,
    alt_text VARCHAR(255) NULL,

    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_service_media_service
        FOREIGN KEY (service_id)
        REFERENCES services(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    INDEX idx_service_media_service_id (service_id),
    INDEX idx_service_media_type (media_type),
    INDEX idx_service_media_sort_order (sort_order),
    INDEX idx_service_media_active (is_active)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- 7. SERVICE FEATURES
-- ============================================================

CREATE TABLE service_features (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    service_id INT UNSIGNED NOT NULL,

    -- English
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,

    -- Vietnamese
    title_vn VARCHAR(255) NULL,
    description_vn TEXT NULL,

    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_service_features_service
        FOREIGN KEY (service_id)
        REFERENCES services(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    INDEX idx_service_features_service_id (service_id),
    INDEX idx_service_features_sort_order (sort_order),
    INDEX idx_service_features_active (is_active)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- 8. SERVICE STEPS
-- ============================================================

CREATE TABLE service_steps (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    service_id INT UNSIGNED NOT NULL,

    step_number INT NOT NULL DEFAULT 1,

    -- English
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,

    -- Vietnamese
    title_vn VARCHAR(255) NULL,
    description_vn TEXT NULL,

    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_service_steps_service
        FOREIGN KEY (service_id)
        REFERENCES services(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    INDEX idx_service_steps_service_id (service_id),
    INDEX idx_service_steps_number (step_number),
    INDEX idx_service_steps_sort_order (sort_order),
    INDEX idx_service_steps_active (is_active)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- 9. SERVICE FAQ
-- ============================================================

CREATE TABLE service_faq (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    service_id INT UNSIGNED NOT NULL,

    -- English
    question VARCHAR(500) NOT NULL,
    answer TEXT NOT NULL,

    -- Vietnamese
    question_vn VARCHAR(500) NULL,
    answer_vn TEXT NULL,

    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_service_faq_service
        FOREIGN KEY (service_id)
        REFERENCES services(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    INDEX idx_service_faq_service_id (service_id),
    INDEX idx_service_faq_sort_order (sort_order),
    INDEX idx_service_faq_active (is_active)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- INSERT SERVICES
-- ============================================================

INSERT INTO news
(
    slug, category, category_vn, title, title_vn, excerpt, excerpt_vn,
    content, content_vn, featured_image, author, status, published_at,
    is_featured, sort_order
)
VALUES
(
    'vietnam-visa-guide-for-first-time-travelers', 'Visa Guide', 'Hướng dẫn Visa',
    'Vietnam Visa Guide for First-Time Travelers', 'Hướng dẫn Visa Việt Nam cho người đi lần đầu',
    'A clear starting point for travelers who want to understand the visa journey.', 'Điểm bắt đầu dễ hiểu cho du khách muốn nắm quy trình visa.',
    'Prepare your passport information, intended entry date and entry point.\nClear information makes the application easier to understand.',
    'Hãy chuẩn bị thông tin hộ chiếu, ngày nhập cảnh và cửa khẩu.\nThông tin rõ ràng giúp quy trình đăng ký dễ hiểu hơn.',
    'https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&w=1200&q=85',
    'SunViet', 'published', '2026-09-05 00:00:00', 1, 1
),
(
    'how-to-prepare-business-trip-to-vietnam', 'Business', 'Công tác',
    'How to Prepare for a Business Trip to Vietnam', 'Cách chuẩn bị cho chuyến công tác tại Việt Nam',
    'A practical checklist for professionals planning meetings and short business visits.', 'Checklist thực tế dành cho chuyên gia chuẩn bị chuyến công tác.',
    'Start with your travel purpose and schedule.\nKeep copies of important information and check the dates in your documents.',
    'Bắt đầu bằng mục đích chuyến đi và lịch trình.\nLưu bản sao thông tin quan trọng và kiểm tra ngày tháng trên hồ sơ.',
    'https://images.unsplash.com/photo-1556761175-b413da4baf72?auto=format&fit=crop&w=1200&q=85',
    'SunViet', 'published', '2026-08-21 00:00:00', 0, 2
),
(
    'new-immigration-website-experience', 'Company', 'Công ty',
    'A Simpler Immigration Website Experience', 'Trải nghiệm website dịch vụ di trú đơn giản hơn',
    'Why we are redesigning the way visitors discover services and contact our team.', 'Vì sao chúng tôi thiết kế lại cách khách hàng khám phá dịch vụ và liên hệ đội ngũ.',
    'Service websites should answer questions before asking for information.\nEach service now has its own detail page and FAQ.',
    'Website dịch vụ nên trả lời câu hỏi trước khi yêu cầu thông tin.\nMỗi dịch vụ có trang chi tiết và FAQ riêng.',
    'https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&w=1200&q=85',
    'SunViet', 'published', '2026-07-14 00:00:00', 0, 3
);

INSERT INTO services (
    category,
    slug,
    title,
    short_description,
    description,
    title_vn,
    short_description_vn,
    description_vn,
    price,
    currency,
    image,
    icon,
    is_featured,
    is_active,
    sort_order
)
VALUES

(
    'visa',
    'vietnam-evisa',

    'Vietnam E-Visa',
    'A simple, guided application journey for travelers entering Vietnam.',
    'From checking your information to preparing the application, our team helps make the Vietnam E-Visa process easier to understand.',

    'E-Visa Việt Nam',
    'Hỗ trợ quy trình đăng ký E-Visa Việt Nam rõ ràng và thuận tiện.',
    'Từ kiểm tra thông tin đến chuẩn bị hồ sơ, đội ngũ của chúng tôi giúp quy trình E-Visa Việt Nam trở nên dễ hiểu và thuận tiện hơn.',

    NULL,
    'USD',

    'https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&w=1400&q=85',

    '01',

    1,
    1,
    1
),

(
    'visa',
    'business-visa',

    'Business Visa',
    'Visa support for professionals, meetings, projects and business trips in Vietnam.',
    'A structured service for business travelers who need clear document guidance and a predictable application journey.',

    'Visa Công Tác',
    'Hỗ trợ visa cho chuyên gia, cuộc họp, dự án và chuyến công tác tại Việt Nam.',
    'Dịch vụ dành cho khách công tác cần hướng dẫn hồ sơ rõ ràng và quy trình đăng ký thuận tiện.',

    NULL,
    'USD',

    'https://images.unsplash.com/photo-1556761175-b413da4baf72?auto=format&fit=crop&w=1400&q=85',

    '02',

    1,
    1,
    2
),

(
    'immigration',
    'work-permit',

    'Work Permit',
    'A clear presentation of the work permit process for foreign employees in Vietnam.',
    'Organize requirements, documents and process information in one clear place for employees and companies.',

    'Giấy Phép Lao Động',
    'Trình bày rõ ràng quy trình giấy phép lao động cho người nước ngoài tại Việt Nam.',
    'Tổng hợp yêu cầu, giấy tờ và quy trình tại một nơi rõ ràng cho người lao động và doanh nghiệp.',

    NULL,
    'USD',

    'https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=1400&q=85',

    '03',

    1,
    1,
    3
),

(
    'immigration',
    'temporary-residence-card',

    'Temporary Residence Card',
    'Present your temporary residence card service clearly from requirements to result.',
    'A structured information page for foreign residents and companies preparing temporary residence card applications.',

    'Thẻ Tạm Trú',
    'Trình bày dịch vụ thẻ tạm trú rõ ràng từ yêu cầu đến kết quả.',
    'Trang thông tin có cấu trúc dành cho người nước ngoài và doanh nghiệp chuẩn bị hồ sơ thẻ tạm trú.',

    NULL,
    'USD',

    'https://images.unsplash.com/photo-1500534623283-312aade485b7?auto=format&fit=crop&w=1400&q=85',

    '04',

    1,
    1,
    4
),

(
    'support',
    'airport-fast-track',

    'Airport Fast Track',
    'Arrival assistance designed to make your airport experience smoother.',
    'Present airport assistance options in a simple service format with clear benefits and a direct contact CTA.',

    'Hỗ Trợ Fast Track Sân Bay',
    'Hỗ trợ khi đến sân bay giúp hành trình nhập cảnh thuận tiện hơn.',
    'Trình bày các lựa chọn hỗ trợ sân bay theo dạng dịch vụ rõ ràng với lợi ích và CTA liên hệ trực tiếp.',

    NULL,
    'USD',

    'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=1400&q=85',

    '05',

    0,
    1,
    5
);


-- ============================================================
-- SERVICE FEATURES
-- ============================================================

-- Vietnam E-Visa
INSERT INTO service_features
(
    service_id,
    title,
    description,
    title_vn,
    description_vn,
    sort_order
)
VALUES
(
    1,
    'Application guidance',
    NULL,
    'Hướng dẫn hồ sơ',
    NULL,
    1
),
(
    1,
    'Document checklist',
    NULL,
    'Checklist giấy tờ',
    NULL,
    2
),
(
    1,
    'Entry information review',
    NULL,
    'Kiểm tra thông tin nhập cảnh',
    NULL,
    3
),
(
    1,
    'Progress support',
    NULL,
    'Hỗ trợ theo dõi tiến trình',
    NULL,
    4
);


-- Business Visa
INSERT INTO service_features
(
    service_id,
    title,
    description,
    title_vn,
    description_vn,
    sort_order
)
VALUES
(
    2,
    'Business-purpose guidance',
    NULL,
    'Tư vấn mục đích công tác',
    NULL,
    1
),
(
    2,
    'Document review',
    NULL,
    'Kiểm tra hồ sơ',
    NULL,
    2
),
(
    2,
    'Application preparation',
    NULL,
    'Chuẩn bị hồ sơ',
    NULL,
    3
),
(
    2,
    'Dedicated support',
    NULL,
    'Hỗ trợ riêng',
    NULL,
    4
);


-- Work Permit
INSERT INTO service_features
(
    service_id,
    title,
    description,
    title_vn,
    description_vn,
    sort_order
)
VALUES
(
    3,
    'Requirement overview',
    NULL,
    'Tổng quan yêu cầu',
    NULL,
    1
),
(
    3,
    'Document checklist',
    NULL,
    'Checklist giấy tờ',
    NULL,
    2
),
(
    3,
    'Company support flow',
    NULL,
    'Quy trình cho doanh nghiệp',
    NULL,
    3
),
(
    3,
    'Process guidance',
    NULL,
    'Hướng dẫn xử lý',
    NULL,
    4
);


-- Temporary Residence Card
INSERT INTO service_features
(
    service_id,
    title,
    description,
    title_vn,
    description_vn,
    sort_order
)
VALUES
(
    4,
    'Eligibility overview',
    NULL,
    'Tổng quan điều kiện',
    NULL,
    1
),
(
    4,
    'Document checklist',
    NULL,
    'Checklist giấy tờ',
    NULL,
    2
),
(
    4,
    'Application steps',
    NULL,
    'Các bước đăng ký',
    NULL,
    3
),
(
    4,
    'Support information',
    NULL,
    'Thông tin hỗ trợ',
    NULL,
    4
);


-- Airport Fast Track
INSERT INTO service_features
(
    service_id,
    title,
    description,
    title_vn,
    description_vn,
    sort_order
)
VALUES
(
    5,
    'Arrival assistance',
    NULL,
    'Hỗ trợ khi đến',
    NULL,
    1
),
(
    5,
    'Clear meeting instructions',
    NULL,
    'Hướng dẫn điểm gặp',
    NULL,
    2
),
(
    5,
    'Support information',
    NULL,
    'Thông tin hỗ trợ',
    NULL,
    3
),
(
    5,
    'Flexible service options',
    NULL,
    'Tùy chọn dịch vụ linh hoạt',
    NULL,
    4
);


-- ============================================================
-- SERVICE STEPS
-- ============================================================

-- Vietnam E-Visa
INSERT INTO service_steps
(
    service_id,
    step_number,
    title,
    description,
    title_vn,
    description_vn,
    sort_order
)
VALUES
(
    1,
    1,
    'Tell us your trip details',
    NULL,
    'Cung cấp thông tin chuyến đi',
    NULL,
    1
),
(
    1,
    2,
    'Review your information',
    NULL,
    'Kiểm tra thông tin',
    NULL,
    2
),
(
    1,
    3,
    'Prepare the application',
    NULL,
    'Chuẩn bị hồ sơ',
    NULL,
    3
),
(
    1,
    4,
    'Receive the result',
    NULL,
    'Nhận kết quả',
    NULL,
    4
);


-- Business Visa
INSERT INTO service_steps
(
    service_id,
    step_number,
    title,
    description,
    title_vn,
    description_vn,
    sort_order
)
VALUES
(
    2,
    1,
    'Share your business trip details',
    NULL,
    'Cung cấp thông tin chuyến công tác',
    NULL,
    1
),
(
    2,
    2,
    'Check required documents',
    NULL,
    'Kiểm tra giấy tờ cần thiết',
    NULL,
    2
),
(
    2,
    3,
    'Prepare the application',
    NULL,
    'Chuẩn bị hồ sơ',
    NULL,
    3
),
(
    2,
    4,
    'Receive your result',
    NULL,
    'Nhận kết quả',
    NULL,
    4
);


-- Work Permit
INSERT INTO service_steps
(
    service_id,
    step_number,
    title,
    description,
    title_vn,
    description_vn,
    sort_order
)
VALUES
(
    3,
    1,
    'Review profile',
    NULL,
    'Kiểm tra hồ sơ',
    NULL,
    1
),
(
    3,
    2,
    'Prepare documents',
    NULL,
    'Chuẩn bị giấy tờ',
    NULL,
    2
),
(
    3,
    3,
    'Submit application',
    NULL,
    'Nộp hồ sơ',
    NULL,
    3
),
(
    3,
    4,
    'Track the result',
    NULL,
    'Theo dõi kết quả',
    NULL,
    4
);


-- Temporary Residence Card
INSERT INTO service_steps
(
    service_id,
    step_number,
    title,
    description,
    title_vn,
    description_vn,
    sort_order
)
VALUES
(
    4,
    1,
    'Check your case',
    NULL,
    'Kiểm tra trường hợp',
    NULL,
    1
),
(
    4,
    2,
    'Prepare documents',
    NULL,
    'Chuẩn bị giấy tờ',
    NULL,
    2
),
(
    4,
    3,
    'Submit the application',
    NULL,
    'Nộp hồ sơ',
    NULL,
    3
),
(
    4,
    4,
    'Receive the card',
    NULL,
    'Nhận thẻ',
    NULL,
    4
);


-- Airport Fast Track
INSERT INTO service_steps
(
    service_id,
    step_number,
    title,
    description,
    title_vn,
    description_vn,
    sort_order
)
VALUES
(
    5,
    1,
    'Choose the service',
    NULL,
    'Chọn dịch vụ',
    NULL,
    1
),
(
    5,
    2,
    'Share arrival details',
    NULL,
    'Cung cấp thông tin chuyến bay',
    NULL,
    2
),
(
    5,
    3,
    'Receive instructions',
    NULL,
    'Nhận hướng dẫn',
    NULL,
    3
),
(
    5,
    4,
    'Meet the support team',
    NULL,
    'Gặp đội ngũ hỗ trợ',
    NULL,
    4
);


-- ============================================================
-- SERVICE FAQ
-- ============================================================

-- Vietnam E-Visa
INSERT INTO service_faq
(
    service_id,
    question,
    answer,
    question_vn,
    answer_vn,
    sort_order
)
VALUES
(
    1,
    'How early should I apply?',
    'We recommend preparing your application before your planned travel date.',
    'Nên đăng ký trước bao lâu?',
    'Bạn nên chuẩn bị hồ sơ trước ngày dự kiến nhập cảnh để có thời gian xử lý phù hợp.',
    1
);


-- Business Visa
INSERT INTO service_faq
(
    service_id,
    question,
    answer,
    question_vn,
    answer_vn,
    sort_order
)
VALUES
(
    2,
    'Can you support company travelers?',
    'Yes. The frontend is structured to support individual and business service flows.',
    'Có hỗ trợ khách công tác của doanh nghiệp không?',
    'Có. Giao diện được thiết kế để hỗ trợ cả khách cá nhân và khách doanh nghiệp.',
    1
);


-- Work Permit
INSERT INTO service_faq
(
    service_id,
    question,
    answer,
    question_vn,
    answer_vn,
    sort_order
)
VALUES
(
    3,
    'Who can use this service?',
    'Foreign employees and companies can use the service information as a starting point.',
    'Ai có thể sử dụng dịch vụ này?',
    'Người lao động nước ngoài và doanh nghiệp có thể sử dụng thông tin dịch vụ làm điểm bắt đầu.',
    1
);


-- Temporary Residence Card
INSERT INTO service_faq
(
    service_id,
    question,
    answer,
    question_vn,
    answer_vn,
    sort_order
)
VALUES
(
    4,
    'Can the detail page be managed from an admin system later?',
    'Yes. This page is intentionally separated from the data layer so the backend can replace the mock content later.',
    'Sau này trang chi tiết có thể lấy dữ liệu từ admin không?',
    'Có. Trang được tách khỏi lớp dữ liệu để backend có thể thay thế dữ liệu mẫu bằng database/API.',
    1
);


-- Airport Fast Track
INSERT INTO service_faq
(
    service_id,
    question,
    answer,
    question_vn,
    answer_vn,
    sort_order
)
VALUES
(
    5,
    'Where will I see the final service price?',
    'The backend/admin team can connect the final pricing rules to this view later.',
    'Giá dịch vụ cuối cùng sẽ lấy ở đâu?',
    'Backend/admin có thể kết nối bảng giá thực tế với giao diện này sau.',
    1
);


-- ============================================================
-- OPTIONAL DEFAULT SETTINGS
-- ============================================================

INSERT INTO settings
(
    setting_key,
    setting_value,
    setting_group
)
VALUES
(
    'site_name',
    'SunViet',
    'general'
),
(
    'default_language',
    'en',
    'general'
);


-- ============================================================
-- FINISH
-- ============================================================

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- VERIFY
-- ============================================================

SELECT
    'services' AS table_name,
    COUNT(*) AS total
FROM services

UNION ALL

SELECT
    'service_features',
    COUNT(*)
FROM service_features

UNION ALL

SELECT
    'service_steps',
    COUNT(*)
FROM service_steps

UNION ALL

SELECT
    'service_faq',
    COUNT(*)
FROM service_faq

UNION ALL

SELECT
    'service_media',
    COUNT(*)
FROM service_media

UNION ALL

SELECT
    'news',
    COUNT(*)
FROM news

UNION ALL

SELECT
    'home_media',
    COUNT(*)
FROM home_media

UNION ALL

SELECT
    'settings',
    COUNT(*)
FROM settings

UNION ALL

SELECT
    'admin_users',
    COUNT(*)
FROM admin_users;
