-- ============================================================
-- SUNVIET / VietnamVisa DATABASE
-- XAMPP + MySQL / MariaDB
-- Step 2: Database foundation + sample content
-- ============================================================

CREATE DATABASE IF NOT EXISTS VietnamVisa
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE VietnamVisa;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS service_faqs;
DROP TABLE IF EXISTS service_steps;
DROP TABLE IF EXISTS service_features;
DROP TABLE IF EXISTS services;
DROP TABLE IF EXISTS news;
DROP TABLE IF EXISTS pages;
DROP TABLE IF EXISTS faqs;
DROP TABLE IF EXISTS admin_users;
DROP TABLE IF EXISTS settings;

SET FOREIGN_KEY_CHECKS = 1;

-- ------------------------------------------------------------
-- Admin users
-- ------------------------------------------------------------
CREATE TABLE admin_users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('super_admin','editor') NOT NULL DEFAULT 'editor',
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    last_login_at DATETIME NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Services
-- ------------------------------------------------------------
CREATE TABLE services (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(190) NOT NULL UNIQUE,
    icon VARCHAR(50) NULL,
    category VARCHAR(100) NOT NULL,
    title_en VARCHAR(255) NOT NULL,
    title_vn VARCHAR(255) NOT NULL,
    short_en TEXT NULL,
    short_vn TEXT NULL,
    description_en TEXT NULL,
    description_vn TEXT NULL,
    image VARCHAR(1000) NULL,
    featured TINYINT(1) NOT NULL DEFAULT 0,
    status ENUM('draft','published') NOT NULL DEFAULT 'draft',
    sort_order INT NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_services_category (category),
    INDEX idx_services_status (status),
    INDEX idx_services_featured (featured)
) ENGINE=InnoDB;

CREATE TABLE service_features (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    service_id INT UNSIGNED NOT NULL,
    language ENUM('en','vn') NOT NULL,
    content TEXT NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    CONSTRAINT fk_service_features_service
        FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE,
    INDEX idx_service_features_service (service_id)
) ENGINE=InnoDB;

CREATE TABLE service_steps (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    service_id INT UNSIGNED NOT NULL,
    language ENUM('en','vn') NOT NULL,
    content TEXT NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    CONSTRAINT fk_service_steps_service
        FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE,
    INDEX idx_service_steps_service (service_id)
) ENGINE=InnoDB;

CREATE TABLE service_faqs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    service_id INT UNSIGNED NOT NULL,
    language ENUM('en','vn') NOT NULL,
    question TEXT NOT NULL,
    answer TEXT NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    CONSTRAINT fk_service_faqs_service
        FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE,
    INDEX idx_service_faqs_service (service_id)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- News / Articles
-- ------------------------------------------------------------
CREATE TABLE news (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(190) NOT NULL UNIQUE,
    date DATE NOT NULL,
    category_en VARCHAR(120) NOT NULL,
    category_vn VARCHAR(120) NOT NULL,
    title_en VARCHAR(255) NOT NULL,
    title_vn VARCHAR(255) NOT NULL,
    excerpt_en TEXT NULL,
    excerpt_vn TEXT NULL,
    content_en LONGTEXT NULL,
    content_vn LONGTEXT NULL,
    image VARCHAR(1000) NULL,
    author VARCHAR(120) NULL,
    status ENUM('draft','published') NOT NULL DEFAULT 'draft',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_news_date (date),
    INDEX idx_news_status (status)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Editable pages (About / Visa / Support etc.)
-- Home and Contact can remain fixed in the view as requested.
-- ------------------------------------------------------------
CREATE TABLE pages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(190) NOT NULL UNIQUE,
    title_en VARCHAR(255) NOT NULL,
    title_vn VARCHAR(255) NOT NULL,
    content_en LONGTEXT NULL,
    content_vn LONGTEXT NULL,
    meta_title_en VARCHAR(255) NULL,
    meta_title_vn VARCHAR(255) NULL,
    meta_description_en VARCHAR(500) NULL,
    meta_description_vn VARCHAR(500) NULL,
    status ENUM('draft','published') NOT NULL DEFAULT 'published',
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Global FAQ management
-- ------------------------------------------------------------
CREATE TABLE faqs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    question_en TEXT NOT NULL,
    answer_en TEXT NOT NULL,
    question_vn TEXT NOT NULL,
    answer_vn TEXT NOT NULL,
    status ENUM('draft','published') NOT NULL DEFAULT 'published',
    sort_order INT NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Site settings
-- ------------------------------------------------------------
CREATE TABLE settings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(190) NOT NULL UNIQUE,
    setting_value TEXT NULL,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- SAMPLE ADMIN
-- Email: admin@sunviet.local
-- Password: ChangeMe123!
-- IMPORTANT: change this password after first login.
-- ============================================================
INSERT INTO admin_users (name, email, password_hash, role, status)
VALUES (
    'SunViet Admin',
    'admin@sunviet.local',
    '$2y$12$/LKIJRjbK3.5pa04p0ZNteX0pnv3hZk0wohmzwlVblTq/JBPufmKy',
    'super_admin',
    'active'
);

-- ============================================================
-- SAMPLE SERVICES
-- ============================================================
INSERT INTO services
(slug, icon, category, title_en, title_vn, short_en, short_vn, description_en, description_vn, image, featured, status, sort_order)
VALUES
('vietnam-evisa','01','Visa','Vietnam E-Visa','E-Visa Việt Nam',
 'A simple, guided application journey for travelers entering Vietnam.',
 'Hỗ trợ quy trình đăng ký E-Visa Việt Nam rõ ràng và thuận tiện.',
 'From checking your information to preparing the application, our team helps make the Vietnam E-Visa process easier to understand.',
 'Từ kiểm tra thông tin đến chuẩn bị hồ sơ, đội ngũ của chúng tôi giúp quy trình E-Visa Việt Nam trở nên dễ hiểu và thuận tiện hơn.',
 'https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&w=1400&q=85',1,'published',1),
('business-visa','02','Visa','Business Visa','Visa Công Tác',
 'Visa support for professionals, meetings, projects and business trips in Vietnam.',
 'Hỗ trợ visa cho chuyên gia, cuộc họp, dự án và chuyến công tác tại Việt Nam.',
 'A structured service for business travelers who need clear document guidance and a predictable application journey.',
 'Dịch vụ dành cho khách công tác cần hướng dẫn hồ sơ rõ ràng và quy trình đăng ký thuận tiện.',
 'https://images.unsplash.com/photo-1556761175-b413da4baf72?auto=format&fit=crop&w=1400&q=85',1,'published',2),
('work-permit','03','VietnamVisation','Work Permit','Giấy Phép Lao Động',
 'A clear presentation of the work permit process for foreign employees in Vietnam.',
 'Trình bày rõ ràng quy trình giấy phép lao động cho người nước ngoài tại Việt Nam.',
 'Organize requirements, documents and process information in one clear place for employees and companies.',
 'Tổng hợp yêu cầu, giấy tờ và quy trình tại một nơi rõ ràng cho người lao động và doanh nghiệp.',
 'https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=1400&q=85',1,'published',3),
('temporary-residence-card','04','VietnamVisation','Temporary Residence Card','Thẻ Tạm Trú',
 'Present your temporary residence card service clearly from requirements to result.',
 'Trình bày dịch vụ thẻ tạm trú rõ ràng từ yêu cầu đến kết quả.',
 'A structured information page for foreign residents and companies preparing temporary residence card applications.',
 'Trang thông tin có cấu trúc dành cho người nước ngoài và doanh nghiệp chuẩn bị hồ sơ thẻ tạm trú.',
 'https://images.unsplash.com/photo-1500534623283-312aade485b7?auto=format&fit=crop&w=1400&q=85',1,'published',4),
('airport-fast-track','05','Support','Airport Fast Track','Hỗ Trợ Fast Track Sân Bay',
 'Arrival assistance designed to make your airport experience smoother.',
 'Hỗ trợ khi đến sân bay giúp hành trình nhập cảnh thuận tiện hơn.',
 'Present airport assistance options in a simple service format with clear benefits and a direct contact CTA.',
 'Trình bày các lựa chọn hỗ trợ sân bay theo dạng dịch vụ rõ ràng với lợi ích và CTA liên hệ trực tiếp.',
 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=1400&q=85',0,'published',5);

-- Features / steps / FAQs for each service
INSERT INTO service_features (service_id,language,content,sort_order) SELECT id,'en','Application guidance',1 FROM services WHERE slug='vietnam-evisa';
INSERT INTO service_features (service_id,language,content,sort_order) SELECT id,'en','Document checklist',2 FROM services WHERE slug='vietnam-evisa';
INSERT INTO service_features (service_id,language,content,sort_order) SELECT id,'en','Entry information review',3 FROM services WHERE slug='vietnam-evisa';
INSERT INTO service_features (service_id,language,content,sort_order) SELECT id,'en','Progress support',4 FROM services WHERE slug='vietnam-evisa';
INSERT INTO service_features (service_id,language,content,sort_order) SELECT id,'vn','Hướng dẫn hồ sơ',1 FROM services WHERE slug='vietnam-evisa';
INSERT INTO service_features (service_id,language,content,sort_order) SELECT id,'vn','Checklist giấy tờ',2 FROM services WHERE slug='vietnam-evisa';
INSERT INTO service_features (service_id,language,content,sort_order) SELECT id,'vn','Kiểm tra thông tin nhập cảnh',3 FROM services WHERE slug='vietnam-evisa';
INSERT INTO service_features (service_id,language,content,sort_order) SELECT id,'vn','Hỗ trợ theo dõi tiến trình',4 FROM services WHERE slug='vietnam-evisa';

INSERT INTO service_features (service_id,language,content,sort_order) SELECT id,'en','Business-purpose guidance',1 FROM services WHERE slug='business-visa';
INSERT INTO service_features (service_id,language,content,sort_order) SELECT id,'en','Document review',2 FROM services WHERE slug='business-visa';
INSERT INTO service_features (service_id,language,content,sort_order) SELECT id,'en','Application preparation',3 FROM services WHERE slug='business-visa';
INSERT INTO service_features (service_id,language,content,sort_order) SELECT id,'en','Dedicated support',4 FROM services WHERE slug='business-visa';
INSERT INTO service_features (service_id,language,content,sort_order) SELECT id,'vn','Tư vấn mục đích công tác',1 FROM services WHERE slug='business-visa';
INSERT INTO service_features (service_id,language,content,sort_order) SELECT id,'vn','Kiểm tra hồ sơ',2 FROM services WHERE slug='business-visa';
INSERT INTO service_features (service_id,language,content,sort_order) SELECT id,'vn','Chuẩn bị hồ sơ',3 FROM services WHERE slug='business-visa';
INSERT INTO service_features (service_id,language,content,sort_order) SELECT id,'vn','Hỗ trợ riêng',4 FROM services WHERE slug='business-visa';

INSERT INTO service_features (service_id,language,content,sort_order) SELECT id,'en','Requirement overview',1 FROM services WHERE slug='work-permit';
INSERT INTO service_features (service_id,language,content,sort_order) SELECT id,'en','Document checklist',2 FROM services WHERE slug='work-permit';
INSERT INTO service_features (service_id,language,content,sort_order) SELECT id,'en','Company support flow',3 FROM services WHERE slug='work-permit';
INSERT INTO service_features (service_id,language,content,sort_order) SELECT id,'en','Process guidance',4 FROM services WHERE slug='work-permit';
INSERT INTO service_features (service_id,language,content,sort_order) SELECT id,'vn','Tổng quan yêu cầu',1 FROM services WHERE slug='work-permit';
INSERT INTO service_features (service_id,language,content,sort_order) SELECT id,'vn','Checklist giấy tờ',2 FROM services WHERE slug='work-permit';
INSERT INTO service_features (service_id,language,content,sort_order) SELECT id,'vn','Quy trình cho doanh nghiệp',3 FROM services WHERE slug='work-permit';
INSERT INTO service_features (service_id,language,content,sort_order) SELECT id,'vn','Hướng dẫn xử lý',4 FROM services WHERE slug='work-permit';

INSERT INTO service_features (service_id,language,content,sort_order) SELECT id,'en','Eligibility overview',1 FROM services WHERE slug='temporary-residence-card';
INSERT INTO service_features (service_id,language,content,sort_order) SELECT id,'en','Document checklist',2 FROM services WHERE slug='temporary-residence-card';
INSERT INTO service_features (service_id,language,content,sort_order) SELECT id,'en','Application steps',3 FROM services WHERE slug='temporary-residence-card';
INSERT INTO service_features (service_id,language,content,sort_order) SELECT id,'en','Support information',4 FROM services WHERE slug='temporary-residence-card';
INSERT INTO service_features (service_id,language,content,sort_order) SELECT id,'vn','Tổng quan điều kiện',1 FROM services WHERE slug='temporary-residence-card';
INSERT INTO service_features (service_id,language,content,sort_order) SELECT id,'vn','Checklist giấy tờ',2 FROM services WHERE slug='temporary-residence-card';
INSERT INTO service_features (service_id,language,content,sort_order) SELECT id,'vn','Các bước đăng ký',3 FROM services WHERE slug='temporary-residence-card';
INSERT INTO service_features (service_id,language,content,sort_order) SELECT id,'vn','Thông tin hỗ trợ',4 FROM services WHERE slug='temporary-residence-card';

INSERT INTO service_features (service_id,language,content,sort_order) SELECT id,'en','Arrival assistance',1 FROM services WHERE slug='airport-fast-track';
INSERT INTO service_features (service_id,language,content,sort_order) SELECT id,'en','Clear meeting instructions',2 FROM services WHERE slug='airport-fast-track';
INSERT INTO service_features (service_id,language,content,sort_order) SELECT id,'en','Support information',3 FROM services WHERE slug='airport-fast-track';
INSERT INTO service_features (service_id,language,content,sort_order) SELECT id,'en','Flexible service options',4 FROM services WHERE slug='airport-fast-track';
INSERT INTO service_features (service_id,language,content,sort_order) SELECT id,'vn','Hỗ trợ khi đến',1 FROM services WHERE slug='airport-fast-track';
INSERT INTO service_features (service_id,language,content,sort_order) SELECT id,'vn','Hướng dẫn điểm gặp',2 FROM services WHERE slug='airport-fast-track';
INSERT INTO service_features (service_id,language,content,sort_order) SELECT id,'vn','Thông tin hỗ trợ',3 FROM services WHERE slug='airport-fast-track';
INSERT INTO service_features (service_id,language,content,sort_order) SELECT id,'vn','Tùy chọn dịch vụ linh hoạt',4 FROM services WHERE slug='airport-fast-track';

-- Steps
INSERT INTO service_steps(service_id,language,content,sort_order) SELECT id,'en','Tell us your trip details',1 FROM services WHERE slug='vietnam-evisa';
INSERT INTO service_steps(service_id,language,content,sort_order) SELECT id,'en','Review your information',2 FROM services WHERE slug='vietnam-evisa';
INSERT INTO service_steps(service_id,language,content,sort_order) SELECT id,'en','Prepare the application',3 FROM services WHERE slug='vietnam-evisa';
INSERT INTO service_steps(service_id,language,content,sort_order) SELECT id,'en','Receive the result',4 FROM services WHERE slug='vietnam-evisa';
INSERT INTO service_steps(service_id,language,content,sort_order) SELECT id,'vn','Cung cấp thông tin chuyến đi',1 FROM services WHERE slug='vietnam-evisa';
INSERT INTO service_steps(service_id,language,content,sort_order) SELECT id,'vn','Kiểm tra thông tin',2 FROM services WHERE slug='vietnam-evisa';
INSERT INTO service_steps(service_id,language,content,sort_order) SELECT id,'vn','Chuẩn bị hồ sơ',3 FROM services WHERE slug='vietnam-evisa';
INSERT INTO service_steps(service_id,language,content,sort_order) SELECT id,'vn','Nhận kết quả',4 FROM services WHERE slug='vietnam-evisa';

INSERT INTO service_steps(service_id,language,content,sort_order) SELECT id,'en','Share your business trip details',1 FROM services WHERE slug='business-visa';
INSERT INTO service_steps(service_id,language,content,sort_order) SELECT id,'en','Check required documents',2 FROM services WHERE slug='business-visa';
INSERT INTO service_steps(service_id,language,content,sort_order) SELECT id,'en','Prepare the application',3 FROM services WHERE slug='business-visa';
INSERT INTO service_steps(service_id,language,content,sort_order) SELECT id,'en','Receive your result',4 FROM services WHERE slug='business-visa';
INSERT INTO service_steps(service_id,language,content,sort_order) SELECT id,'vn','Cung cấp thông tin chuyến công tác',1 FROM services WHERE slug='business-visa';
INSERT INTO service_steps(service_id,language,content,sort_order) SELECT id,'vn','Kiểm tra giấy tờ cần thiết',2 FROM services WHERE slug='business-visa';
INSERT INTO service_steps(service_id,language,content,sort_order) SELECT id,'vn','Chuẩn bị hồ sơ',3 FROM services WHERE slug='business-visa';
INSERT INTO service_steps(service_id,language,content,sort_order) SELECT id,'vn','Nhận kết quả',4 FROM services WHERE slug='business-visa';

INSERT INTO service_steps(service_id,language,content,sort_order) SELECT id,'en','Review profile',1 FROM services WHERE slug='work-permit';
INSERT INTO service_steps(service_id,language,content,sort_order) SELECT id,'en','Prepare documents',2 FROM services WHERE slug='work-permit';
INSERT INTO service_steps(service_id,language,content,sort_order) SELECT id,'en','Submit application',3 FROM services WHERE slug='work-permit';
INSERT INTO service_steps(service_id,language,content,sort_order) SELECT id,'en','Track the result',4 FROM services WHERE slug='work-permit';
INSERT INTO service_steps(service_id,language,content,sort_order) SELECT id,'vn','Kiểm tra hồ sơ',1 FROM services WHERE slug='work-permit';
INSERT INTO service_steps(service_id,language,content,sort_order) SELECT id,'vn','Chuẩn bị giấy tờ',2 FROM services WHERE slug='work-permit';
INSERT INTO service_steps(service_id,language,content,sort_order) SELECT id,'vn','Nộp hồ sơ',3 FROM services WHERE slug='work-permit';
INSERT INTO service_steps(service_id,language,content,sort_order) SELECT id,'vn','Theo dõi kết quả',4 FROM services WHERE slug='work-permit';

INSERT INTO service_steps(service_id,language,content,sort_order) SELECT id,'en','Check your case',1 FROM services WHERE slug='temporary-residence-card';
INSERT INTO service_steps(service_id,language,content,sort_order) SELECT id,'en','Prepare documents',2 FROM services WHERE slug='temporary-residence-card';
INSERT INTO service_steps(service_id,language,content,sort_order) SELECT id,'en','Submit the application',3 FROM services WHERE slug='temporary-residence-card';
INSERT INTO service_steps(service_id,language,content,sort_order) SELECT id,'en','Receive the card',4 FROM services WHERE slug='temporary-residence-card';
INSERT INTO service_steps(service_id,language,content,sort_order) SELECT id,'vn','Kiểm tra trường hợp',1 FROM services WHERE slug='temporary-residence-card';
INSERT INTO service_steps(service_id,language,content,sort_order) SELECT id,'vn','Chuẩn bị giấy tờ',2 FROM services WHERE slug='temporary-residence-card';
INSERT INTO service_steps(service_id,language,content,sort_order) SELECT id,'vn','Nộp hồ sơ',3 FROM services WHERE slug='temporary-residence-card';
INSERT INTO service_steps(service_id,language,content,sort_order) SELECT id,'vn','Nhận thẻ',4 FROM services WHERE slug='temporary-residence-card';

INSERT INTO service_steps(service_id,language,content,sort_order) SELECT id,'en','Choose the service',1 FROM services WHERE slug='airport-fast-track';
INSERT INTO service_steps(service_id,language,content,sort_order) SELECT id,'en','Share arrival details',2 FROM services WHERE slug='airport-fast-track';
INSERT INTO service_steps(service_id,language,content,sort_order) SELECT id,'en','Receive instructions',3 FROM services WHERE slug='airport-fast-track';
INSERT INTO service_steps(service_id,language,content,sort_order) SELECT id,'en','Meet the support team',4 FROM services WHERE slug='airport-fast-track';
INSERT INTO service_steps(service_id,language,content,sort_order) SELECT id,'vn','Chọn dịch vụ',1 FROM services WHERE slug='airport-fast-track';
INSERT INTO service_steps(service_id,language,content,sort_order) SELECT id,'vn','Cung cấp thông tin chuyến bay',2 FROM services WHERE slug='airport-fast-track';
INSERT INTO service_steps(service_id,language,content,sort_order) SELECT id,'vn','Nhận hướng dẫn',3 FROM services WHERE slug='airport-fast-track';
INSERT INTO service_steps(service_id,language,content,sort_order) SELECT id,'vn','Gặp đội ngũ hỗ trợ',4 FROM services WHERE slug='airport-fast-track';

-- Service FAQ samples
INSERT INTO service_faqs(service_id,language,question,answer,sort_order) SELECT id,'en','How early should I apply?','We recommend preparing your application before your planned travel date.',1 FROM services WHERE slug='vietnam-evisa';
INSERT INTO service_faqs(service_id,language,question,answer,sort_order) SELECT id,'vn','Nên đăng ký trước bao lâu?','Bạn nên chuẩn bị hồ sơ trước ngày dự kiến nhập cảnh để có thời gian xử lý phù hợp.',1 FROM services WHERE slug='vietnam-evisa';
INSERT INTO service_faqs(service_id,language,question,answer,sort_order) SELECT id,'en','Can you support company travelers?','Yes. The frontend is structured to support individual and business service flows.',1 FROM services WHERE slug='business-visa';
INSERT INTO service_faqs(service_id,language,question,answer,sort_order) SELECT id,'vn','Có hỗ trợ khách công tác của doanh nghiệp không?','Có. Giao diện được thiết kế để hỗ trợ cả khách cá nhân và khách doanh nghiệp.',1 FROM services WHERE slug='business-visa';
INSERT INTO service_faqs(service_id,language,question,answer,sort_order) SELECT id,'en','Who can use this service?','Foreign employees and companies can use the service information as a starting point.',1 FROM services WHERE slug='work-permit';
INSERT INTO service_faqs(service_id,language,question,answer,sort_order) SELECT id,'vn','Ai có thể sử dụng dịch vụ này?','Người lao động nước ngoài và doanh nghiệp có thể sử dụng thông tin dịch vụ làm điểm bắt đầu.',1 FROM services WHERE slug='work-permit';
INSERT INTO service_faqs(service_id,language,question,answer,sort_order) SELECT id,'en','Can the detail page be managed from an admin system later?','Yes. This page is intentionally separated from the data layer so the backend can replace the mock content later.',1 FROM services WHERE slug='temporary-residence-card';
INSERT INTO service_faqs(service_id,language,question,answer,sort_order) SELECT id,'vn','Sau này trang chi tiết có thể lấy dữ liệu từ admin không?','Có. Trang được tách khỏi lớp dữ liệu để backend có thể thay thế dữ liệu mẫu bằng database/API.',1 FROM services WHERE slug='temporary-residence-card';
INSERT INTO service_faqs(service_id,language,question,answer,sort_order) SELECT id,'en','Where will I see the final service price?','The backend/admin team can connect the final pricing rules to this view later.',1 FROM services WHERE slug='airport-fast-track';
INSERT INTO service_faqs(service_id,language,question,answer,sort_order) SELECT id,'vn','Giá dịch vụ cuối cùng sẽ lấy ở đâu?','Backend/admin có thể kết nối bảng giá thực tế với giao diện này sau.',1 FROM services WHERE slug='airport-fast-track';

-- ============================================================
-- SAMPLE NEWS
-- ============================================================
INSERT INTO news
(slug,date,category_en,category_vn,title_en,title_vn,excerpt_en,excerpt_vn,image,content_en,content_vn,author,status)
VALUES
('vietnam-visa-guide-for-first-time-travelers','2026-09-05','Visa Guide','Hướng dẫn Visa',
 'Vietnam Visa Guide for First-Time Travelers','Hướng dẫn Visa Việt Nam cho người đi lần đầu',
 'A clean starting point for travelers who want to understand the visa journey before booking their trip.',
 'Điểm bắt đầu dễ hiểu cho du khách muốn nắm quy trình visa trước khi lên kế hoạch chuyến đi.',
 'https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&w=1200&q=85',
 'Before starting an application, prepare your passport information, intended entry date and entry point.\n\nA good website experience should make these requirements easy to understand before the user starts the form.\n\nOur new frontend is designed around this principle: clear information first, application second.',
 'Trước khi đăng ký, hãy chuẩn bị thông tin hộ chiếu, ngày dự kiến nhập cảnh và cửa khẩu nhập cảnh.\n\nMột website tốt nên giúp người dùng hiểu yêu cầu trước khi bắt đầu điền biểu mẫu.\n\nFrontend mới được thiết kế theo nguyên tắc: thông tin rõ ràng trước, đăng ký sau.',
 'SunViet','published'),
('how-to-prepare-business-trip-to-vietnam','2026-08-21','Business','Công tác',
 'How to Prepare for a Business Trip to Vietnam','Cách chuẩn bị cho chuyến công tác tại Việt Nam',
 'A practical checklist for professionals planning meetings, projects and short business visits.',
 'Checklist thực tế dành cho chuyên gia chuẩn bị cuộc họp, dự án và chuyến công tác ngắn ngày.',
 'https://images.unsplash.com/photo-1556761175-b413da4baf72?auto=format&fit=crop&w=1200&q=85',
 'Start with your travel purpose and schedule. Then identify the documents that match your case.\n\nKeep copies of important information and make sure the dates in your documents are consistent.\n\nIf your trip involves a company invitation or additional VietnamVisation support, plan that step early.',
 'Bắt đầu bằng mục đích chuyến đi và lịch trình. Sau đó xác định giấy tờ phù hợp với trường hợp của bạn.\n\nNên lưu bản sao các thông tin quan trọng và kiểm tra tính nhất quán của ngày tháng trên hồ sơ.\n\nNếu chuyến đi cần thư mời doanh nghiệp hoặc hỗ trợ di trú bổ sung, hãy chuẩn bị sớm.',
 'SunViet','published'),
('new-VietnamVisation-website-experience','2026-07-14','Company','Công ty',
 'A Simpler VietnamVisation Website Experience','Trải nghiệm website dịch vụ di trú đơn giản hơn',
 'Why we are redesigning the way visitors discover services, read details and contact our team.',
 'Vì sao chúng tôi thiết kế lại cách khách hàng khám phá dịch vụ, xem chi tiết và liên hệ đội ngũ.',
 'https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&w=1200&q=85',
 'Service websites should answer the user’s questions before asking for information.\n\nThat is why each service now has its own detail page, process, feature list, FAQ and call to action.\n\nThe content layer is also separated so the future admin system can manage services and news without changing the visual layer.',
 'Website dịch vụ nên trả lời câu hỏi của khách hàng trước khi yêu cầu họ cung cấp thông tin.\n\nVì vậy mỗi dịch vụ có trang chi tiết riêng, quy trình, tính năng, FAQ và CTA.\n\nLớp dữ liệu cũng được tách riêng để hệ thống admin sau này có thể quản lý dịch vụ và bài viết mà không cần sửa giao diện.',
 'SunViet','published');

-- ============================================================
-- Editable pages
-- ============================================================
INSERT INTO pages (slug,title_en,title_vn,content_en,content_vn,status) VALUES
('about','About SunViet','Về SunViet','About page content can be edited from the admin panel.','Nội dung trang giới thiệu có thể chỉnh sửa từ admin.','published'),
('visa','Vietnam Visa','Visa Việt Nam','Visa page content can be edited from the admin panel.','Nội dung trang Visa có thể chỉnh sửa từ admin.','published'),
('support','Support','Hỗ trợ','Support page content can be edited from the admin panel.','Nội dung trang Hỗ trợ có thể chỉnh sửa từ admin.','published');

-- Global FAQ samples
INSERT INTO faqs (question_en,answer_en,question_vn,answer_vn,status,sort_order) VALUES
('How can I start a service request?','Choose a service, review its requirements and use the contact or application CTA.','Tôi bắt đầu yêu cầu dịch vụ như thế nào?','Chọn dịch vụ, xem yêu cầu và sử dụng nút liên hệ hoặc đăng ký.','published',1),
('Can I use the website in Vietnamese?','Yes. The frontend provides English and Vietnamese routes.','Website có hỗ trợ tiếng Việt không?','Có. Giao diện có cả phiên bản tiếng Anh và tiếng Việt.','published',2);

-- Settings
INSERT INTO settings (setting_key,setting_value) VALUES
('site_name','SunViet'),
('default_language','en'),
('admin_email','admin@sunviet.local');

-- ============================================================
-- Useful verification queries
-- ============================================================
-- SELECT COUNT(*) AS services FROM services;
-- SELECT COUNT(*) AS news FROM news;
-- SELECT COUNT(*) AS pages FROM pages;
-- SELECT COUNT(*) AS faqs FROM faqs;
-- SELECT email, role, status FROM admin_users;
