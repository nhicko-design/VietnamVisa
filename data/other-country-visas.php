<?php

/**
 * Frontend catalogue for the Other Countries Visa section.
 *
 * Use managed database cards when available, with built-in content as a fallback.
 */
function other_country_visa_catalog(): array
{
    $catalog = [
        'global' => [
            'title' => 'Visa to USA, UK, Canada, Australia & New Zealand',
            'title_vn' => 'Visa Mỹ, Anh, Canada, Úc & New Zealand',
            'short' => 'Consultation for popular long-haul destinations outside Asia and Europe.',
            'short_vn' => 'Tư vấn hồ sơ cho các điểm đến phổ biến ngoài châu Á và châu Âu.',
            'image' => 'https://images.unsplash.com/photo-1485738422979-f5c462d49f74?auto=format&fit=crop&w=1400&q=85',
            'countries' => [
                'usa' => [
                    'title' => 'United States Visa',
                    'title_vn' => 'Visa Hoa Kỳ',
                    'summary' => 'Support for tourism, business and family-visit visa consultation.',
                    'summary_vn' => 'Tư vấn hồ sơ visa du lịch, công tác và thăm thân.',
                    'focus' => 'Travel plan, financial profile and interview preparation.',
                    'focus_vn' => 'Kế hoạch chuyến đi, hồ sơ tài chính và chuẩn bị phỏng vấn.',
                ],
                'united-kingdom' => [
                    'title' => 'United Kingdom Visa',
                    'title_vn' => 'Visa Vương quốc Anh',
                    'summary' => 'Guidance for visitor visa applications to the United Kingdom.',
                    'summary_vn' => 'Hướng dẫn hồ sơ visa thăm viếng Vương quốc Anh.',
                    'focus' => 'Purpose of travel, accommodation and supporting documents.',
                    'focus_vn' => 'Mục đích chuyến đi, chỗ ở và hồ sơ chứng minh.',
                ],
                'canada' => [
                    'title' => 'Canada Visa',
                    'title_vn' => 'Visa Canada',
                    'summary' => 'Consultation for visitor visa and travel-document preparation.',
                    'summary_vn' => 'Tư vấn visa thăm viếng và chuẩn bị hồ sơ du lịch.',
                    'focus' => 'Trip purpose, personal profile and supporting evidence.',
                    'focus_vn' => 'Mục đích chuyến đi, hồ sơ cá nhân và giấy tờ chứng minh.',
                ],
                'australia' => [
                    'title' => 'Australia Visa',
                    'title_vn' => 'Visa Úc',
                    'summary' => 'Support for visitor visa consultation and application planning.',
                    'summary_vn' => 'Hỗ trợ tư vấn visa thăm viếng và lập kế hoạch hồ sơ.',
                    'focus' => 'Travel itinerary, financial evidence and personal ties.',
                    'focus_vn' => 'Lịch trình, chứng minh tài chính và ràng buộc cá nhân.',
                ],
                'new-zealand' => [
                    'title' => 'New Zealand Visa',
                    'title_vn' => 'Visa New Zealand',
                    'summary' => 'Consultation for visitors planning travel to New Zealand.',
                    'summary_vn' => 'Tư vấn cho khách chuẩn bị hồ sơ đi New Zealand.',
                    'focus' => 'Purpose of visit, trip plan and supporting documents.',
                    'focus_vn' => 'Mục đích lưu trú, kế hoạch chuyến đi và hồ sơ chứng minh.',
                ],
            ],
        ],
        'schengen' => [
            'title' => 'Europe Countries – Schengen Visa',
            'title_vn' => 'Visa châu Âu – Schengen',
            'short' => 'Guidance for travel within the Schengen area.',
            'short_vn' => 'Hướng dẫn hồ sơ du lịch trong khu vực Schengen.',
            'image' => 'https://images.unsplash.com/photo-1467269204594-9661b134dd2b?auto=format&fit=crop&w=1400&q=85',
            'countries' => [
                'france' => ['title' => 'France Visa', 'title_vn' => 'Visa Pháp', 'summary' => 'Consultation for travel and business visits to France.', 'summary_vn' => 'Tư vấn hồ sơ du lịch và công tác tại Pháp.', 'focus' => 'Travel itinerary and Schengen supporting documents.', 'focus_vn' => 'Lịch trình và hồ sơ chứng minh theo diện Schengen.'],
                'germany' => ['title' => 'Germany Visa', 'title_vn' => 'Visa Đức', 'summary' => 'Guidance for visitor and business travel to Germany.', 'summary_vn' => 'Hướng dẫn hồ sơ thăm viếng và công tác tại Đức.', 'focus' => 'Trip purpose, itinerary and supporting evidence.', 'focus_vn' => 'Mục đích chuyến đi, lịch trình và giấy tờ chứng minh.'],
                'spain' => ['title' => 'Spain Visa', 'title_vn' => 'Visa Tây Ban Nha', 'summary' => 'Consultation for tourism and short visits to Spain.', 'summary_vn' => 'Tư vấn visa du lịch và lưu trú ngắn hạn tại Tây Ban Nha.', 'focus' => 'Travel schedule and Schengen application documents.', 'focus_vn' => 'Lịch trình và hồ sơ theo yêu cầu Schengen.'],
                'italy' => ['title' => 'Italy Visa', 'title_vn' => 'Visa Ý', 'summary' => 'Guidance for tourism, family visits and business travel to Italy.', 'summary_vn' => 'Hướng dẫn hồ sơ du lịch, thăm thân và công tác tại Ý.', 'focus' => 'Travel purpose and supporting personal documents.', 'focus_vn' => 'Mục đích chuyến đi và hồ sơ cá nhân liên quan.'],
                'greece' => ['title' => 'Greece Visa', 'title_vn' => 'Visa Hy Lạp', 'summary' => 'Consultation for short-stay visits to Greece.', 'summary_vn' => 'Tư vấn hồ sơ lưu trú ngắn hạn tại Hy Lạp.', 'focus' => 'Itinerary, accommodation and supporting evidence.', 'focus_vn' => 'Lịch trình, chỗ ở và giấy tờ chứng minh.'],
            ],
        ],
        'china' => [
            'title' => 'China Visa',
            'title_vn' => 'Visa Trung Quốc',
            'short' => 'Visa consultation for travel, business and visits to China.',
            'short_vn' => 'Tư vấn visa du lịch, công tác và thăm thân tại Trung Quốc.',
            'image' => 'https://images.unsplash.com/photo-1528360983277-13d401cdc186?auto=format&fit=crop&w=1400&q=85',
            'countries' => [
                'china' => ['title' => 'China Visa', 'title_vn' => 'Visa Trung Quốc', 'summary' => 'Guidance for China travel and business visa consultation.', 'summary_vn' => 'Hướng dẫn hồ sơ visa du lịch và công tác Trung Quốc.', 'focus' => 'Travel purpose, invitation documents where applicable, and itinerary.', 'focus_vn' => 'Mục đích chuyến đi, thư mời khi cần và lịch trình.'],
            ],
        ],
        'asia' => [
            'title' => 'Asia Countries Visa',
            'title_vn' => 'Visa các nước châu Á',
            'short' => 'Visa consultation for popular destinations across Asia.',
            'short_vn' => 'Tư vấn visa cho các điểm đến phổ biến tại châu Á.',
            'image' => 'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?auto=format&fit=crop&w=1400&q=85',
            'countries' => [
                'japan' => ['title' => 'Japan Visa', 'title_vn' => 'Visa Nhật Bản', 'summary' => 'Consultation for travel and business visits to Japan.', 'summary_vn' => 'Tư vấn hồ sơ du lịch và công tác tại Nhật Bản.', 'focus' => 'Travel plan, employment or business profile, and supporting documents.', 'focus_vn' => 'Kế hoạch chuyến đi, hồ sơ công việc hoặc kinh doanh và giấy tờ chứng minh.'],
                'south-korea' => ['title' => 'South Korea Visa', 'title_vn' => 'Visa Hàn Quốc', 'summary' => 'Guidance for visitor visa applications to South Korea.', 'summary_vn' => 'Hướng dẫn hồ sơ visa thăm viếng Hàn Quốc.', 'focus' => 'Travel purpose, financial profile and personal documents.', 'focus_vn' => 'Mục đích chuyến đi, hồ sơ tài chính và giấy tờ cá nhân.'],
                'india' => ['title' => 'India Visa', 'title_vn' => 'Visa Ấn Độ', 'summary' => 'Support for India visa consultation and travel-document preparation.', 'summary_vn' => 'Hỗ trợ tư vấn visa Ấn Độ và chuẩn bị hồ sơ du lịch.', 'focus' => 'Travel purpose and the appropriate application route.', 'focus_vn' => 'Mục đích chuyến đi và phương án nộp hồ sơ phù hợp.'],
                'taiwan' => ['title' => 'Taiwan Visa', 'title_vn' => 'Visa Đài Loan', 'summary' => 'Consultation for Taiwan tourism, visits and business travel.', 'summary_vn' => 'Tư vấn visa du lịch, thăm thân và công tác Đài Loan.', 'focus' => 'Travel itinerary and supporting personal documents.', 'focus_vn' => 'Lịch trình và giấy tờ chứng minh cá nhân.'],
                'hong-kong' => ['title' => 'Hong Kong Visa', 'title_vn' => 'Visa Hồng Kông', 'summary' => 'Guidance for travel and business visits to Hong Kong.', 'summary_vn' => 'Hướng dẫn hồ sơ du lịch và công tác tại Hồng Kông.', 'focus' => 'Travel purpose and supporting application information.', 'focus_vn' => 'Mục đích chuyến đi và thông tin hồ sơ liên quan.'],
                'singapore' => ['title' => 'Singapore Visa', 'title_vn' => 'Visa Singapore', 'summary' => 'Consultation for short visits to Singapore.', 'summary_vn' => 'Tư vấn hồ sơ lưu trú ngắn hạn tại Singapore.', 'focus' => 'Visitor profile, travel plan and supporting documents.', 'focus_vn' => 'Hồ sơ người đi, kế hoạch chuyến đi và giấy tờ chứng minh.'],
                'thailand' => ['title' => 'Thailand Visa', 'title_vn' => 'Visa Thái Lan', 'summary' => 'Guidance for travellers who require a Thailand visa.', 'summary_vn' => 'Hướng dẫn hồ sơ cho khách cần xin visa Thái Lan.', 'focus' => 'Purpose of stay and the suitable visa pathway.', 'focus_vn' => 'Mục đích lưu trú và diện visa phù hợp.'],
            ],
        ],
    ];

    try {
        $pdo = db();
        $regions = $pdo->query('SELECT * FROM visa_regions WHERE is_active = 1 ORDER BY sort_order, id')->fetchAll(PDO::FETCH_ASSOC);

        $countryCount = (int) $pdo->query('SELECT COUNT(*) FROM visa_countries WHERE is_active = 1')->fetchColumn();

        if ($regions && $countryCount > 0) {
            $catalog = [];
            $countryStatement = $pdo->prepare('SELECT * FROM visa_countries WHERE region_id = ? AND is_active = 1 ORDER BY sort_order, id');
            $mediaStatement = $pdo->query(
                'SELECT region_id, country_id, media_type, file_path, title, alt_text
                 FROM visa_media WHERE is_active = 1 ORDER BY sort_order, id'
            );
            $mediaByRegion = [];
            $mediaByCountry = [];
            $contentByOwner = [];

            foreach ($mediaStatement->fetchAll(PDO::FETCH_ASSOC) as $media) {
                if ($media['region_id']) {
                    $mediaByRegion[(int) $media['region_id']][] = $media;
                }
                if ($media['country_id']) {
                    $mediaByCountry[(int) $media['country_id']][] = $media;
                }
            }

            try {
                $contentStatement = $pdo->query('SELECT owner_type, owner_id, item_type, title, title_vn, content, content_vn FROM visa_content_items WHERE is_active = 1 ORDER BY sort_order, id');
                foreach ($contentStatement->fetchAll(PDO::FETCH_ASSOC) as $content) {
                    $contentByOwner[$content['owner_type']][(int) $content['owner_id']][$content['item_type']][] = $content;
                }
            } catch (Throwable $exception) {
                // Optional content tables may not yet be migrated.
            }

            foreach ($regions as $region) {
                $countryStatement->execute([(int) $region['id']]);
                $countries = [];

                foreach ($countryStatement->fetchAll(PDO::FETCH_ASSOC) as $country) {
                    $countryMedia = $mediaByCountry[(int) $country['id']] ?? [];
                    $countryImage = '';
                    foreach ($countryMedia as $media) {
                        if ($media['media_type'] === 'image') {
                            $countryImage = $media['file_path'];
                            break;
                        }
                    }
                    $countries[$country['slug']] = [
                        'title' => $country['title'],
                        'title_vn' => $country['title_vn'] ?: $country['title'],
                        'summary' => $country['summary'] ?: '',
                        'summary_vn' => $country['summary_vn'] ?: ($country['summary'] ?: ''),
                        'focus' => $country['detail'] ?: ($country['summary'] ?: ''),
                        'focus_vn' => $country['detail_vn'] ?: ($country['summary_vn'] ?: ($country['summary'] ?: '')),
                        'image' => $countryImage,
                        'media' => $countryMedia,
                        'features' => $contentByOwner['country'][(int) $country['id']]['feature'] ?? [],
                        'steps' => $contentByOwner['country'][(int) $country['id']]['step'] ?? [],
                        'faqs' => $contentByOwner['country'][(int) $country['id']]['faq'] ?? [],
                    ];
                }

                $catalog[$region['slug']] = [
                    'title' => $region['title'],
                    'title_vn' => $region['title_vn'] ?: $region['title'],
                    'short' => $region['description'] ?: '',
                    'short_vn' => $region['description_vn'] ?: ($region['description'] ?: ''),
                    'image' => $region['image'] ?: '',
                    'media' => $mediaByRegion[(int) $region['id']] ?? [],
                    'countries' => $countries,
                    'features' => $contentByOwner['region'][(int) $region['id']]['feature'] ?? [],
                    'steps' => $contentByOwner['region'][(int) $region['id']]['step'] ?? [],
                    'faqs' => $contentByOwner['region'][(int) $region['id']]['faq'] ?? [],
                ];
            }
        }
    } catch (Throwable $exception) {
        // Keep the built-in catalogue until database tables are available.
    }

    return $catalog;
}
