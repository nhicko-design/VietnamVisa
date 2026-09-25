<?php
$current_lang = 'en';
$LANG = [];

function set_language(string $lang): void
{
    global $current_lang, $LANG;
    $current_lang = in_array($lang, ['en', 'vn'], true) ? $lang : 'en';
    $file = __DIR__ . '/../resources/' . $current_lang . '.php';
    if (is_file($file)) require $file;
    else $LANG = [];
}

function __($key): string
{
    global $LANG;
    return $LANG[$key] ?? $key;
}
