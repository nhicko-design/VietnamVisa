<?php

require_once __DIR__ . '/database.php';


function start_public_form_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    $isHttps = !empty($_SERVER['HTTPS'])
        && $_SERVER['HTTPS'] !== 'off';

    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    session_start();
}


function public_form_csrf_token(): string
{
    start_public_form_session();

    if (empty($_SESSION['public_form_csrf_token'])) {
        $_SESSION['public_form_csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['public_form_csrf_token'];
}


function verify_public_form_csrf_token(?string $token): bool
{
    start_public_form_session();

    return !empty($token)
        && !empty($_SESSION['public_form_csrf_token'])
        && hash_equals($_SESSION['public_form_csrf_token'], $token);
}


function public_form_value(string $key, int $maximumLength = 255): string
{
    $value = trim((string) ($_POST[$key] ?? ''));

    return mb_substr($value, 0, $maximumLength, 'UTF-8');
}


function submit_public_inquiry(string $type, string $language): array
{
    start_public_form_session();

    $errors = [];

    if (!in_array($type, ['contact', 'application'], true)) {
        return ['success' => false, 'errors' => ['Invalid form type.']];
    }

    if (!verify_public_form_csrf_token($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Your form session has expired. Please refresh and try again.';
    }

    if (trim((string) ($_POST['website'] ?? '')) !== '') {
        $errors[] = 'Unable to send this request.';
    }

    $lastSubmission = (int) ($_SESSION['public_form_last_submission'] ?? 0);

    if ($lastSubmission > 0 && time() - $lastSubmission < 10) {
        $errors[] = 'Please wait a moment before sending another request.';
    }

    $fullName = public_form_value('full_name');
    $email = public_form_value('email');
    $phone = public_form_value('phone', 50);
    $service = public_form_value('service');
    $nationality = public_form_value('nationality', 100);
    $entryDate = public_form_value('entry_date', 10);
    $entryPoint = public_form_value('entry_point');
    $message = public_form_value('message', 4000);

    if ($fullName === '' || mb_strlen($fullName, 'UTF-8') < 2) {
        $errors[] = 'Please enter your full name.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if ($type === 'application' && $nationality === '') {
        $errors[] = 'Please enter your nationality.';
    }

    if ($entryDate !== '') {
        $date = DateTime::createFromFormat('Y-m-d', $entryDate);

        if (!$date || $date->format('Y-m-d') !== $entryDate) {
            $errors[] = 'Please enter a valid expected entry date.';
        }
    }

    if ($errors) {
        return ['success' => false, 'errors' => $errors];
    }

    $sourceUrl = mb_substr(
        (string) ($_SERVER['REQUEST_URI'] ?? ''),
        0,
        1000,
        'UTF-8'
    );

    $ipAddress = (string) ($_SERVER['REMOTE_ADDR'] ?? '');
    $ipHash = $ipAddress === ''
        ? null
        : hash('sha256', $ipAddress);

    try {
        $statement = db()->prepare(
            'INSERT INTO inquiries (
                inquiry_type, full_name, email, phone, service, nationality,
                entry_date, entry_point, message, language_code, source_url, ip_hash
            ) VALUES (
                :inquiry_type, :full_name, :email, :phone, :service, :nationality,
                :entry_date, :entry_point, :message, :language_code, :source_url, :ip_hash
            )'
        );

        $statement->execute([
            'inquiry_type' => $type,
            'full_name' => $fullName,
            'email' => $email,
            'phone' => $phone ?: null,
            'service' => $service ?: null,
            'nationality' => $nationality ?: null,
            'entry_date' => $entryDate ?: null,
            'entry_point' => $entryPoint ?: null,
            'message' => $message ?: null,
            'language_code' => $language === 'vn' ? 'vn' : 'en',
            'source_url' => $sourceUrl ?: null,
            'ip_hash' => $ipHash,
        ]);
    } catch (Throwable $exception) {
        error_log('Unable to store public inquiry: ' . $exception->getMessage());

        return [
            'success' => false,
            'errors' => ['We could not save your request. Please try again later.'],
        ];
    }

    $_SESSION['public_form_last_submission'] = time();

    return ['success' => true, 'errors' => []];
}
