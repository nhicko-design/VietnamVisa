<?php
require_once __DIR__ . '/common/rich-content.php';
$debugMode = filter_var(
    getenv('SUNVIET_APP_DEBUG') ?: false,
    FILTER_VALIDATE_BOOL
);

error_reporting(E_ALL);
ini_set('display_errors', $debugMode ? '1' : '0');
ini_set('display_startup_errors', $debugMode ? '1' : '0');
ini_set('log_errors', '1');
require_once __DIR__ . '/common/config.php';
require_once __DIR__ . '/common/helpers.php';
require_once __DIR__ . '/common/language.php';
require_once __DIR__ . '/data/services-db.php';
require_once __DIR__ . '/data/news-db.php';
require_once __DIR__ . '/data/home-media.php';
require_once __DIR__ . '/data/page-content.php';

$path = request_path();
$segments = $path === '' ? [] : explode('/', $path);
$lang = isset($segments[0]) && in_array($segments[0], ['en', 'vn'], true)
    ? $segments[0]
    : DEFAULT_LANGUAGE;
$page = isset($segments[1]) && $segments[1] !== '' ? $segments[1] : 'home';
$slug = isset($segments[2]) ? $segments[2] : '';
$sub_slug = isset($segments[3]) ? $segments[3] : '';

set_language($lang);

$route = 'home';
$template = __DIR__ . '/pages/home.php';
$page_title = __('site_name');
$page_description = __('site_description');
$canonical_path = '';
$page_robots = 'index,follow';
$page_faq_key = '';

switch ($page) {
    case 'services':
        $route = 'services';
        $template = __DIR__ . '/pages/services.php';
        $page_title = __('services_page_title');
        $canonical_path = 'services';
        break;
    case 'vietnam-visa':
        $route = 'vietnam-visa';
        $template = __DIR__ . '/pages/vietnam-visa.php';
        $page_title = __('visa_page_title');
        $canonical_path = 'vietnam-visa';
        break;
    case 'temporary-resident-card':
        $route = 'temporary-resident-card';
        $template = __DIR__ . '/pages/temporary-resident-card.php';
        $page_title = 'Temporary Resident Card';
        $page_description = 'Long-term residence guidance for eligible spouses, investors and foreign employees in Vietnam.';
        $canonical_path = 'temporary-resident-card';
        break;
    case 'work-permit':
        $route = 'work-permit';
        $template = __DIR__ . '/pages/service-detail.php';
        $service = find_service('work-permit');

        if (!$service) {
            http_response_code(404);
            $route = '404';
            $template = __DIR__ . '/pages/404.php';
            $page_title = __('not_found');
        } else {
            $page_title = field($service, 'title');
            $page_description = field($service, 'short');
            $canonical_path = 'work-permit';
        }
        break;
    case 'other-countries-visa':
        $route = 'other-countries-visa';
        $template = __DIR__ . '/pages/other-countries-visa.php';
        $page_title = 'Other Countries Visa';
        $page_description = 'Visa support and travel documentation for destinations outside Vietnam.';
        $canonical_path = 'other-countries-visa'
            . ($slug !== '' ? '/' . $slug : '')
            . ($sub_slug !== '' ? '/' . $sub_slug : '');
        break;
    case 'other-services':
        $route = 'other-services';
        $template = __DIR__ . '/pages/other-services.php';
        $page_title = 'Other Services';
        $page_description = 'Practical support services for a more convenient journey.';
        $canonical_path = 'other-services';
        break;
    case 'service':
        $route = 'service-detail';
        $template = __DIR__ . '/pages/service-detail.php';
        $service = find_service($slug);
        if (!$service) { http_response_code(404); $route = '404'; $template = __DIR__ . '/pages/404.php'; $page_title = __('not_found'); }
        else { $page_title = field($service, 'title'); $page_description = field($service, 'short'); $canonical_path = 'service/' . $service['slug']; }
        break;
    case 'visa':
        $route = 'visa';
        $template = __DIR__ . '/pages/visa.php';
        $page_title = __('visa_page_title');
        $canonical_path = 'visa';
        break;
    case 'support':
        $route = 'support';
        $template = __DIR__ . '/pages/support.php';
        $page_title = __('support_page_title');
        $canonical_path = 'support';
        break;
    case 'about':
        $route = 'about';
        $template = __DIR__ . '/pages/about.php';
        $page_title = __('about_page_title');
        $canonical_path = 'about';
        break;
    case 'news':
        $route = 'news';
        $template = __DIR__ . '/pages/news.php';
        $page_title = __('news_page_title');
        $canonical_path = 'news';
        break;
    case 'article':
        $route = 'news-detail';
        $template = __DIR__ . '/pages/news-detail.php';
        $article = find_news($slug);
        if (!$article) { http_response_code(404); $route = '404'; $template = __DIR__ . '/pages/404.php'; $page_title = __('not_found'); }
        else { $page_title = field($article, 'title'); $page_description = field($article, 'excerpt'); $canonical_path = 'article/' . $article['slug']; }
        break;
    case 'apply':
        $route = 'apply';
        $template = __DIR__ . '/pages/apply.php';
        $page_title = __('apply_page_title');
        $canonical_path = 'apply';
        break;
    case 'contact':
        $route = 'contact';
        $template = __DIR__ . '/pages/contact.php';
        $page_title = __('contact_page_title');
        $canonical_path = 'contact';
        break;
    case 'quotation':
        $route = 'quotation';
        $template = __DIR__ . '/pages/quotation.php';
        $page_title = __('quotation_page_title');
        $canonical_path = 'quotation';
        break;
    case 'home':
        break;
    default:
        http_response_code(404);
        $route = '404';
        $template = __DIR__ . '/pages/404.php';
        $page_title = __('not_found');
        $page_robots = 'noindex,nofollow';
        break;
}

if ($route !== 'home' && $route !== 'service-detail') {
    $page_faq_key = match ($route) {
        'vietnam-visa' => 'vietnam-visa',
        'temporary-resident-card' => 'temporary-resident-card',
        'work-permit' => 'work-permit',
        'other-countries-visa' => 'other-countries-visa',
        'other-services' => 'other-services',
        default => $page,
    };
}

require_once __DIR__ . '/data/page-faqs.php';

include __DIR__ . '/header.php';
include __DIR__ . '/components/navbar.php';
include $template;

if ($route === 'news-detail') {
    include __DIR__ . '/pages/news-media.php';
}

include __DIR__ . '/components/footer.php';
include __DIR__ . '/footer.php';
