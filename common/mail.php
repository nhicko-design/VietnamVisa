<?php

/**
 * Sends transactional emails through Brevo's HTTPS API.
 * The provider credentials stay in mail.credentials.php, outside version control.
 */
function inquiry_mail_settings(): array
{
    $credentialsFile = __DIR__ . '/mail.credentials.php';

    if (!is_file($credentialsFile)) {
        return [];
    }

    $settings = require $credentialsFile;

    return is_array($settings)
        ? $settings
        : [];
}


function inquiry_mail_value(array $inquiry, string $key): string
{
    return trim((string) ($inquiry[$key] ?? ''));
}


function inquiry_mail_html_row(string $label, string $value): string
{
    if ($value === '') {
        return '';
    }

    $safeLabel = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
    $safeValue = nl2br(
        htmlspecialchars($value, ENT_QUOTES, 'UTF-8')
    );

    return '<tr>'
        . '<th style="padding:8px 12px;text-align:left;vertical-align:top;background:#f5f8fc;">'
        . $safeLabel
        . '</th>'
        . '<td style="padding:8px 12px;">'
        . $safeValue
        . '</td>'
        . '</tr>';
}


function send_inquiry_email_notification(array $inquiry): bool
{
    $settings = inquiry_mail_settings();

    $apiKey = trim((string) ($settings['brevo_api_key'] ?? ''));
    $recipientEmail = trim((string) ($settings['recipient_email'] ?? ''));
    $senderEmail = trim((string) ($settings['sender_email'] ?? ''));
    $senderName = trim((string) ($settings['sender_name'] ?? 'SunViet Website'));

    if (
        $apiKey === '' ||
        !filter_var($recipientEmail, FILTER_VALIDATE_EMAIL) ||
        !filter_var($senderEmail, FILTER_VALIDATE_EMAIL)
    ) {
        error_log('Inquiry email notification is not configured.');

        return false;
    }

    if (!function_exists('curl_init')) {
        error_log('Inquiry email notification requires the PHP cURL extension.');

        return false;
    }

    $fullName = inquiry_mail_value($inquiry, 'full_name');
    $customerEmail = inquiry_mail_value($inquiry, 'email');
    $inquiryType = inquiry_mail_value($inquiry, 'inquiry_type');
    $typeLabel = $inquiryType === 'application'
        ? 'New Apply Online request'
        : 'New contact request';

    $rows = [
        inquiry_mail_html_row('Full name', $fullName),
        inquiry_mail_html_row('Email', $customerEmail),
        inquiry_mail_html_row('Phone', inquiry_mail_value($inquiry, 'phone')),
        inquiry_mail_html_row('Service', inquiry_mail_value($inquiry, 'service')),
        inquiry_mail_html_row('Nationality', inquiry_mail_value($inquiry, 'nationality')),
        inquiry_mail_html_row('Expected entry date', inquiry_mail_value($inquiry, 'entry_date')),
        inquiry_mail_html_row('Entry point', inquiry_mail_value($inquiry, 'entry_point')),
        inquiry_mail_html_row('Additional information', inquiry_mail_value($inquiry, 'message')),
        inquiry_mail_html_row('Language', strtoupper(inquiry_mail_value($inquiry, 'language_code'))),
    ];

    $payload = [
        'sender' => [
            'email' => $senderEmail,
            'name' => $senderName !== '' ? $senderName : 'SunViet Website',
        ],
        'to' => [[
            'email' => $recipientEmail,
        ]],
        'replyTo' => filter_var($customerEmail, FILTER_VALIDATE_EMAIL)
            ? [
                'email' => $customerEmail,
                'name' => $fullName,
            ]
            : null,
        'subject' => '[SunViet] ' . $typeLabel . ($fullName !== '' ? ' — ' . $fullName : ''),
        'htmlContent' => '<!doctype html><html><body style="font-family:Arial,sans-serif;color:#17233a;">'
            . '<h2 style="margin:0 0 16px;">' . htmlspecialchars($typeLabel, ENT_QUOTES, 'UTF-8') . '</h2>'
            . '<p>A customer has submitted a request through the SunViet website.</p>'
            . '<table cellspacing="0" cellpadding="0" style="border-collapse:collapse;border:1px solid #dbe4f0;width:100%;max-width:680px;">'
            . implode('', array_filter($rows))
            . '</table></body></html>',
        'textContent' => $typeLabel . "\n\n"
            . 'Full name: ' . $fullName . "\n"
            . 'Email: ' . $customerEmail . "\n"
            . 'Phone: ' . inquiry_mail_value($inquiry, 'phone') . "\n"
            . 'Service: ' . inquiry_mail_value($inquiry, 'service') . "\n"
            . 'Nationality: ' . inquiry_mail_value($inquiry, 'nationality') . "\n"
            . 'Expected entry date: ' . inquiry_mail_value($inquiry, 'entry_date') . "\n"
            . 'Entry point: ' . inquiry_mail_value($inquiry, 'entry_point') . "\n\n"
            . inquiry_mail_value($inquiry, 'message'),
        'tags' => ['website-inquiry', $inquiryType !== '' ? $inquiryType : 'contact'],
    ];

    $payload = array_filter(
        $payload,
        static fn ($value): bool => $value !== null
    );

    $request = curl_init('https://api.brevo.com/v3/smtp/email');

    curl_setopt_array($request, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        CURLOPT_HTTPHEADER => [
            'accept: application/json',
            'api-key: ' . $apiKey,
            'content-type: application/json',
        ],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT => 20,
    ]);

    $response = curl_exec($request);
    $statusCode = (int) curl_getinfo($request, CURLINFO_RESPONSE_CODE);
    $curlError = curl_error($request);

    curl_close($request);

    if ($response === false || $statusCode < 200 || $statusCode >= 300) {
        error_log(
            'Unable to send inquiry email notification. HTTP '
            . $statusCode
            . ($curlError !== '' ? ': ' . $curlError : '')
        );

        return false;
    }

    return true;
}
