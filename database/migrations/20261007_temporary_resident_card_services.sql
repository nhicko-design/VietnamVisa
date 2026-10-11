-- Detail pages for the three Temporary Resident Card service routes.

INSERT INTO services (
    category, slug, title, short_description, description,
    title_vn, short_description_vn, description_vn,
    price, currency, image, icon, is_featured, is_active, sort_order
) VALUES
    (
        'immigration', 'spouse-resident-card',
        'Spouse Resident Card',
        'Guidance for eligible foreign spouses of Vietnamese citizens.',
        'Consultation for eligible foreign spouses of Vietnamese citizens who hold a valid marriage certificate and need guidance on a temporary resident card application.',
        'Thẻ tạm trú diện vợ/chồng',
        'Hướng dẫn dành cho người nước ngoài là vợ hoặc chồng của công dân Việt Nam.',
        'Tư vấn cho người nước ngoài là vợ hoặc chồng của công dân Việt Nam, có giấy đăng ký kết hôn hợp lệ và cần hướng dẫn về hồ sơ thẻ tạm trú.',
        NULL, 'USD', NULL, '01', 0, 1, 11
    ),
    (
        'immigration', 'investor-resident-card',
        'Investor Resident Card',
        'Guidance for eligible foreign investors in a Vietnam-based company.',
        'Consultation for eligible foreign investors who need guidance on documents and the temporary resident card route connected to their investment in Vietnam.',
        'Thẻ tạm trú diện đầu tư',
        'Hướng dẫn dành cho nhà đầu tư nước ngoài có khoản đầu tư tại Việt Nam.',
        'Tư vấn cho nhà đầu tư nước ngoài cần hướng dẫn về giấy tờ và diện thẻ tạm trú liên quan đến khoản đầu tư tại Việt Nam.',
        NULL, 'USD', NULL, '02', 0, 1, 12
    ),
    (
        'immigration', 'labor-resident-card',
        'Labor Resident Card',
        'Guidance for eligible foreign employees who hold a Vietnam work permit.',
        'Consultation for foreign employees who hold a suitable Vietnam work permit and need guidance on their temporary resident card route.',
        'Thẻ tạm trú diện lao động',
        'Hướng dẫn dành cho người nước ngoài có giấy phép lao động tại Việt Nam.',
        'Tư vấn cho người lao động nước ngoài có giấy phép lao động phù hợp tại Việt Nam và cần hướng dẫn về diện thẻ tạm trú.',
        NULL, 'USD', NULL, '03', 0, 1, 13
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
