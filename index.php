<?php
require_once __DIR__ . '/common/config.php';
require_once __DIR__ . '/common/helpers.php';
require_once __DIR__ . '/common/language.php';
require_once __DIR__ . '/data/services.php';
require_once __DIR__ . '/data/news.php';

$path = request_path();
$segments = $path === '' ? [] : explode('/', $path);
$lang = isset($segments[0]) && in_array($segments[0], ['en', 'vn'], true) ? $segments[0] : 'en';
$page = isset($segments[1]) && $segments[1] !== '' ? $segments[1] : 'home';
$slug = isset($segments[2]) ? $segments[2] : '';

set_language($lang);

$route = 'home';
$template = __DIR__ . '/pages/home.php';
$page_title = __('site_name');
$page_description = __('site_description');

switch ($page) {
    case 'services':
        $route = 'services';
        $template = __DIR__ . '/pages/services.php';
        $page_title = __('services_page_title');
        break;
    case 'service':
        $route = 'service-detail';
        $template = __DIR__ . '/pages/service-detail.php';
        $service = find_service($slug);
        if (!$service) { http_response_code(404); $route = '404'; $template = __DIR__ . '/pages/404.php'; $page_title = __('not_found'); }
        else { $page_title = field($service, 'title'); $page_description = field($service, 'short'); }
        break;
    case 'visa':
        $route = 'visa';
        $template = __DIR__ . '/pages/visa.php';
        $page_title = __('visa_page_title');
        break;
    case 'support':
        $route = 'support';
        $template = __DIR__ . '/pages/support.php';
        $page_title = __('support_page_title');
        break;
    case 'about':
        $route = 'about';
        $template = __DIR__ . '/pages/about.php';
        $page_title = __('about_page_title');
        break;
    case 'news':
        $route = 'news';
        $template = __DIR__ . '/pages/news.php';
        $page_title = __('news_page_title');
        break;
    case 'article':
        $route = 'news-detail';
        $template = __DIR__ . '/pages/news-detail.php';
        $article = find_news($slug);
        if (!$article) { http_response_code(404); $route = '404'; $template = __DIR__ . '/pages/404.php'; $page_title = __('not_found'); }
        else { $page_title = field($article, 'title'); $page_description = field($article, 'excerpt'); }
        break;
    case 'apply':
        $route = 'apply';
        $template = __DIR__ . '/pages/apply.php';
        $page_title = __('apply_page_title');
        break;
    case 'contact':
        $route = 'contact';
        $template = __DIR__ . '/pages/contact.php';
        $page_title = __('contact_page_title');
        break;
    case 'quotation':
        $route = 'quotation';
        $template = __DIR__ . '/pages/quotation.php';
        $page_title = __('quotation_page_title');
        break;
    case 'home':
        break;
    default:
        http_response_code(404);
        $route = '404';
        $template = __DIR__ . '/pages/404.php';
        $page_title = __('not_found');
        break;
}

include __DIR__ . '/header.php';
include __DIR__ . '/components/navbar.php';
include $template;
include __DIR__ . '/components/footer.php';
include __DIR__ . '/footer.php';
