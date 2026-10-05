-- Non-destructive News migration for the existing sunviet database.
-- Run once after backing up the database.

USE sunviet;

ALTER TABLE news
    ADD COLUMN IF NOT EXISTS category VARCHAR(100) NULL AFTER slug,
    ADD COLUMN IF NOT EXISTS category_vn VARCHAR(100) NULL AFTER category,
    ADD COLUMN IF NOT EXISTS title_vn VARCHAR(255) NULL AFTER title,
    ADD COLUMN IF NOT EXISTS excerpt_vn TEXT NULL AFTER excerpt,
    ADD COLUMN IF NOT EXISTS content_vn LONGTEXT NULL AFTER content;

INSERT INTO news
(
    slug, category, category_vn, title, title_vn, excerpt, excerpt_vn,
    content, content_vn, featured_image, author, status, published_at,
    is_featured, sort_order
)
VALUES
(
    'vietnam-visa-guide-for-first-time-travelers',
    'Visa Guide', 'Hướng dẫn Visa',
    'Vietnam Visa Guide for First-Time Travelers',
    'Hướng dẫn Visa Việt Nam cho người đi lần đầu',
    'A clean starting point for travelers who want to understand the visa journey before booking their trip.',
    'Điểm bắt đầu dễ hiểu cho du khách muốn nắm quy trình visa trước khi lên kế hoạch chuyến đi.',
    'Before starting an application, prepare your passport information, intended entry date and entry point.\nA good website experience should make these requirements easy to understand before the user starts the form.\nOur new frontend is designed around this principle: clear information first, application second.',
    'Trước khi đăng ký, hãy chuẩn bị thông tin hộ chiếu, ngày dự kiến nhập cảnh và cửa khẩu nhập cảnh.\nMột website tốt nên giúp người dùng hiểu yêu cầu trước khi bắt đầu điền biểu mẫu.\nFrontend mới được thiết kế theo nguyên tắc: thông tin rõ ràng trước, đăng ký sau.',
    'https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&w=1200&q=85',
    'SunViet', 'published', '2026-09-05 00:00:00', 1, 1
),
(
    'how-to-prepare-business-trip-to-vietnam',
    'Business', 'Công tác',
    'How to Prepare for a Business Trip to Vietnam',
    'Cách chuẩn bị cho chuyến công tác tại Việt Nam',
    'A practical checklist for professionals planning meetings, projects and short business visits.',
    'Checklist thực tế dành cho chuyên gia chuẩn bị cuộc họp, dự án và chuyến công tác ngắn ngày.',
    'Start with your travel purpose and schedule. Then identify the documents that match your case.\nKeep copies of important information and make sure the dates in your documents are consistent.\nIf your trip involves a company invitation or additional immigration support, plan that step early.',
    'Bắt đầu bằng mục đích chuyến đi và lịch trình. Sau đó xác định giấy tờ phù hợp với trường hợp của bạn.\nNên lưu bản sao các thông tin quan trọng và kiểm tra tính nhất quán của ngày tháng trên hồ sơ.\nNếu chuyến đi cần thư mời doanh nghiệp hoặc hỗ trợ di trú bổ sung, hãy chuẩn bị sớm.',
    'https://images.unsplash.com/photo-1556761175-b413da4baf72?auto=format&fit=crop&w=1200&q=85',
    'SunViet', 'published', '2026-08-21 00:00:00', 0, 2
),
(
    'new-immigration-website-experience',
    'Company', 'Công ty',
    'A Simpler Immigration Website Experience',
    'Trải nghiệm website dịch vụ di trú đơn giản hơn',
    'Why we are redesigning the way visitors discover services, read details and contact our team.',
    'Vì sao chúng tôi thiết kế lại cách khách hàng khám phá dịch vụ, xem chi tiết và liên hệ đội ngũ.',
    'Service websites should answer the user’s questions before asking for information.\nThat is why each service now has its own detail page, process, feature list, FAQ and call to action.\nThe content layer is also separated so the future admin system can manage services and news without changing the visual layer.',
    'Website dịch vụ nên trả lời câu hỏi của khách hàng trước khi yêu cầu họ cung cấp thông tin.\nVì vậy mỗi dịch vụ có trang chi tiết riêng, quy trình, tính năng, FAQ và CTA.\nLớp dữ liệu cũng được tách riêng để hệ thống admin sau này có thể quản lý dịch vụ và bài viết mà không cần sửa giao diện.',
    'https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&w=1200&q=85',
    'SunViet', 'published', '2026-07-14 00:00:00', 0, 3
)
ON DUPLICATE KEY UPDATE
    category = COALESCE(category, VALUES(category)),
    category_vn = COALESCE(category_vn, VALUES(category_vn)),
    title_vn = COALESCE(title_vn, VALUES(title_vn)),
    excerpt_vn = COALESCE(excerpt_vn, VALUES(excerpt_vn)),
    content_vn = COALESCE(content_vn, VALUES(content_vn));
