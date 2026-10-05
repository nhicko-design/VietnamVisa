-- Repairs Vietnamese text that was imported with a non-UTF-8 client encoding.
-- Safe to run repeatedly on the local sunviet database.

USE sunviet;
SET NAMES utf8mb4;

UPDATE news
SET
    category_vn = 'Hướng dẫn Visa',
    title_vn = 'Hướng dẫn Visa Việt Nam cho người đi lần đầu',
    excerpt_vn = 'Điểm bắt đầu dễ hiểu cho du khách muốn nắm quy trình visa trước khi lên kế hoạch chuyến đi.'
WHERE slug = 'vietnam-visa-guide-for-first-time-travelers';

UPDATE news
SET
    category_vn = 'Công tác',
    title_vn = 'Cách chuẩn bị cho chuyến công tác tại Việt Nam',
    excerpt_vn = 'Checklist thực tế dành cho chuyên gia chuẩn bị cuộc họp, dự án và chuyến công tác ngắn ngày.'
WHERE slug = 'how-to-prepare-business-trip-to-vietnam';

UPDATE news
SET
    category_vn = 'Công ty',
    title_vn = 'Trải nghiệm website dịch vụ di trú đơn giản hơn',
    excerpt_vn = 'Vì sao chúng tôi thiết kế lại cách khách hàng khám phá dịch vụ, xem chi tiết và liên hệ đội ngũ.'
WHERE slug = 'new-immigration-website-experience';
