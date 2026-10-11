-- Vietnam Visa overview services
-- Each record remains editable from Admin > Services after this migration runs.

INSERT IGNORE INTO services (
    category,
    slug,
    title,
    short_description,
    description,
    title_vn,
    short_description_vn,
    description_vn,
    currency,
    icon,
    is_featured,
    is_active,
    sort_order
)
VALUES
(
    'visa',
    'visa-border-run',
    'Visa Border Run',
    'Guidance for eligible travellers renewing a Vietnam visa at a land border.',
    'Get clear guidance on the documents, timing and travel arrangements involved in a visa border run.',
    'Gia hạn visa tại cửa khẩu đường bộ',
    'Tư vấn cho khách đủ điều kiện gia hạn visa Việt Nam tại cửa khẩu đường bộ.',
    'Nhận hướng dẫn rõ ràng về hồ sơ, thời gian và phương án di chuyển khi thực hiện visa border run.',
    'USD',
    '03',
    0,
    1,
    30
),
(
    'visa',
    'embassy-visa',
    'Embassy Visa',
    'Support for travellers who need to complete visa stamping at a Vietnam embassy.',
    'Understand the required documents and the embassy visa process before you travel.',
    'Visa Đại sứ quán',
    'Hỗ trợ khách cần hoàn tất thủ tục dán visa tại Đại sứ quán Việt Nam.',
    'Tìm hiểu hồ sơ cần thiết và quy trình xin visa tại Đại sứ quán trước chuyến đi.',
    'USD',
    '04',
    0,
    1,
    40
),
(
    'visa',
    'visa-extension',
    'Visa Extension',
    'Case-by-case consultation for travellers who need to stay in Vietnam longer.',
    'Discuss available visa extension options with our team before your current stay ends.',
    'Gia hạn visa',
    'Tư vấn theo từng trường hợp cho khách cần lưu trú tại Việt Nam lâu hơn.',
    'Trao đổi với đội ngũ SunViet về các phương án gia hạn phù hợp trước khi thời hạn lưu trú hiện tại kết thúc.',
    'USD',
    '05',
    0,
    1,
    50
),
(
    'visa',
    'five-year-visa-exemption',
    'Five-Year Visa Exemption',
    'Guidance for eligible overseas Vietnamese and qualifying family members.',
    'Review eligibility and prepare the information needed for a five-year Vietnam visa exemption certificate.',
    'Miễn thị thực 5 năm',
    'Tư vấn cho người Việt Nam ở nước ngoài và thân nhân đủ điều kiện.',
    'Kiểm tra điều kiện và chuẩn bị thông tin cần thiết cho giấy miễn thị thực Việt Nam 5 năm.',
    'USD',
    '06',
    0,
    1,
    60
),
(
    'visa',
    'visa-support-services',
    'Visa Support Services',
    'Practical arrival and travel support that helps make your Vietnam entry smoother.',
    'Arrange airport fast track, arrival support and selected ground transport services alongside your visa plan.',
    'Dịch vụ hỗ trợ visa',
    'Hỗ trợ thực tế để quá trình nhập cảnh Việt Nam thuận lợi hơn.',
    'Sắp xếp fast track sân bay, hỗ trợ khi đến và một số dịch vụ di chuyển phù hợp cùng kế hoạch visa.',
    'USD',
    '07',
    0,
    1,
    70
);
