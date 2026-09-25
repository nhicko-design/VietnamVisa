<?php

function request_path(): string
{
    $path = trim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');
    $base = trim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
    if ($base !== '' && strpos($path, $base) === 0) {
        $path = trim(substr($path, strlen($base)), '/');
    }
    return $path;
}

function app_base(): string
{
    $base = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    return $base === '/' ? '' : rtrim($base, '/');
}

function url(string $path = '', ?string $lang = null): string
{
    global $current_lang;
    $lang = $lang ?: ($current_lang ?? 'en');
    $path = trim($path, '/');
    return app_base() . '/' . $lang . ($path ? '/' . $path : '');
}

function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function field(array $item, string $key, ?string $lang = null): string
{
    global $current_lang;
    $lang = $lang ?: ($current_lang ?? 'en');
    $value = $item[$key . '_' . $lang] ?? $item[$key . '_en'] ?? $item[$key] ?? '';
    return is_string($value) ? $value : '';
}

function field_array(array $item, string $key, ?string $lang = null): array
{
    global $current_lang;
    $lang = $lang ?: ($current_lang ?? 'en');
    $value = $item[$key . '_' . $lang] ?? $item[$key . '_en'] ?? $item[$key] ?? [];
    return is_array($value) ? $value : [];
}

function find_service(string $slug): ?array
{
    global $SERVICES;
    foreach ($SERVICES as $service) if (($service['slug'] ?? '') === $slug) return $service;
    return null;
}

function find_news(string $slug): ?array
{
    global $NEWS;
    foreach ($NEWS as $article) if (($article['slug'] ?? '') === $slug) return $article;
    return null;
}

function active(string $route): string
{
    global $route;
    return $route === $route ? 'is-active' : '';
}

function format_date(string $date): string
{
    $time = strtotime($date);
    return $time ? date('M d, Y', $time) : $date;
}
