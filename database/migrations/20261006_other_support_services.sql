-- Other Services catalogue, based on the legacy SunViet Other Services page.
-- All records use category `support`, so they automatically appear on /other-services.

INSERT INTO services (
    category, slug, title, short_description, description,
    title_vn, short_description_vn, description_vn,
    price, currency, image, icon, is_featured, is_active, sort_order
) VALUES
    (
        'support', 'criminal-record-police-check',
        'Criminal Record / Police Check',
        'Support for criminal-record and police-check document preparation.',
        'Guidance for clients preparing criminal-record or police-check documents for work permits, overseas visa applications, immigration procedures and related requirements.',
        'Lý lịch tư pháp / Phiếu kiểm tra cảnh sát',
        'Hỗ trợ chuẩn bị hồ sơ lý lịch tư pháp và phiếu kiểm tra cảnh sát.',
        'Tư vấn hồ sơ lý lịch tư pháp hoặc phiếu kiểm tra cảnh sát phục vụ giấy phép lao động, visa nước ngoài, thủ tục xuất nhập cảnh và các nhu cầu liên quan.',
        NULL, 'USD', NULL, '01', 0, 1, 10
    ),
    (
        'support', 'vietnam-citizenship-for-viet-kieu',
        'Vietnam Citizenship for Viet Kieu',
        'Consultation for overseas Vietnamese preparing citizenship-related procedures.',
        'A starting point for overseas Vietnamese who need guidance on citizenship-related documents and the appropriate administrative process.',
        'Quốc tịch Việt Nam cho Việt kiều',
        'Tư vấn thủ tục quốc tịch dành cho người Việt Nam ở nước ngoài.',
        'Điểm khởi đầu cho người Việt Nam ở nước ngoài cần tư vấn giấy tờ liên quan đến quốc tịch và quy trình hành chính phù hợp.',
        NULL, 'USD', NULL, '03', 0, 1, 30
    ),
    (
        'support', 'flight-tickets-travel-insurance',
        'Flight Tickets & Travel Insurance',
        'Booking support for domestic and international flights, plus travel insurance.',
        'Support with domestic and international flight bookings, travel-insurance planning and coordination for a smoother travel itinerary.',
        'Vé máy bay & Bảo hiểm du lịch',
        'Hỗ trợ đặt vé bay nội địa, quốc tế và bảo hiểm du lịch.',
        'Hỗ trợ đặt vé bay nội địa, quốc tế, chuẩn bị bảo hiểm du lịch và phối hợp lịch trình thuận tiện hơn.',
        NULL, 'USD', NULL, '04', 0, 1, 40
    ),
    (
        'support', 'consular-legalization-public-notary',
        'Consular Legalization & Public Notary',
        'Guidance for legalizing and notarizing documents used in Vietnam.',
        'Support for documents issued outside Vietnam that need consular legalization or notarization before they can be used for immigration, work, study or other procedures.',
        'Hợp pháp hóa lãnh sự & Công chứng',
        'Hướng dẫn hợp pháp hóa lãnh sự và công chứng giấy tờ sử dụng tại Việt Nam.',
        'Hỗ trợ giấy tờ được cấp ngoài Việt Nam cần hợp pháp hóa lãnh sự hoặc công chứng trước khi sử dụng cho thủ tục xuất nhập cảnh, làm việc, học tập và các mục đích khác.',
        NULL, 'USD', NULL, '05', 0, 1, 50
    ),
    (
        'support', 'translation-services',
        'Translation Services',
        'Translation and notarized-translation support for important documents.',
        'Guidance for translating documents into the language required for overseas use, visa applications, study, work and related procedures.',
        'Dịch vụ dịch thuật',
        'Hỗ trợ dịch thuật và dịch thuật công chứng cho giấy tờ quan trọng.',
        'Hướng dẫn dịch giấy tờ sang ngôn ngữ cần thiết khi sử dụng ở nước ngoài, xin visa, học tập, làm việc và các thủ tục liên quan.',
        NULL, 'USD', NULL, '06', 0, 1, 60
    ),
    (
        'support', 'birth-certificate',
        'Birth Certificate',
        'Guidance for birth-certificate procedures for children born in Vietnam.',
        'Support for parents preparing the documents and administrative steps needed to obtain a birth certificate for a child born in Vietnam.',
        'Giấy khai sinh',
        'Hướng dẫn thủ tục giấy khai sinh cho trẻ sinh tại Việt Nam.',
        'Hỗ trợ phụ huynh chuẩn bị giấy tờ và các bước hành chính cần thiết để làm giấy khai sinh cho trẻ sinh tại Việt Nam.',
        NULL, 'USD', NULL, '07', 0, 1, 70
    ),
    (
        'support', 'marriage-certificate',
        'Marriage Certificate',
        'Consultation for couples preparing marriage-related procedures in Vietnam.',
        'A starting point for couples who need help understanding the documents and administrative requirements for marriage procedures in Vietnam.',
        'Giấy chứng nhận kết hôn',
        'Tư vấn thủ tục kết hôn tại Việt Nam.',
        'Điểm khởi đầu cho các cặp đôi cần tìm hiểu giấy tờ và yêu cầu hành chính đối với thủ tục kết hôn tại Việt Nam.',
        NULL, 'USD', NULL, '08', 0, 1, 80
    ),
    (
        'support', 'convert-driving-license',
        'Converting Driver License',
        'Support for converting an eligible foreign driving license to a Vietnam driving license.',
        'Guidance for clients who need to convert an eligible current driving-license category into a corresponding Vietnam driving license.',
        'Đổi giấy phép lái xe',
        'Hỗ trợ đổi giấy phép lái xe nước ngoài đủ điều kiện sang bằng lái Việt Nam.',
        'Hướng dẫn khách hàng đổi hạng giấy phép lái xe hiện có đủ điều kiện sang giấy phép lái xe Việt Nam tương ứng.',
        NULL, 'USD', NULL, '09', 0, 1, 90
    ),
    (
        'support', 'moc-bai-ground-border-visa-run',
        'Moc Bai Ground Border Visa Run',
        'Travel coordination and guidance for visa-run trips through Moc Bai border.',
        'Support for clients planning a border visa-run trip through Moc Bai, including travel coordination and practical procedure guidance.',
        'Visa run cửa khẩu Mộc Bài',
        'Hỗ trợ di chuyển và hướng dẫn thủ tục visa run qua cửa khẩu Mộc Bài.',
        'Hỗ trợ khách hàng chuẩn bị chuyến visa run qua cửa khẩu Mộc Bài, bao gồm phối hợp di chuyển và hướng dẫn các bước thực tế.',
        NULL, 'USD', NULL, '10', 0, 1, 100
    )
ON DUPLICATE KEY UPDATE
    category = VALUES(category),
    title = VALUES(title),
    short_description = VALUES(short_description),
    description = VALUES(description),
    title_vn = VALUES(title_vn),
    short_description_vn = VALUES(short_description_vn),
    description_vn = VALUES(description_vn),
    icon = VALUES(icon),
    is_active = VALUES(is_active),
    sort_order = VALUES(sort_order);

UPDATE services
SET
    category = 'support',
    title = 'Fast-track at Airport for Arrival & Departure',
    short_description = 'Priority airport support for arrival and departure procedures.',
    description = 'Airport fast-track support designed to help travellers move through arrival or departure procedures more smoothly.',
    title_vn = 'Fast Track sân bay cho chiều đến & chiều đi',
    short_description_vn = 'Hỗ trợ ưu tiên tại sân bay cho thủ tục chiều đến và chiều đi.',
    description_vn = 'Dịch vụ Fast Track tại sân bay giúp hành khách thực hiện thủ tục đến hoặc đi thuận tiện hơn.',
    icon = '02',
    is_active = 1,
    sort_order = 20
WHERE slug = 'airport-fast-track';
