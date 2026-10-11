<?php

/** Render editor HTML while allowing only a small set of formatting elements. */
function rich_content_html(?string $content): string
{
    $content = trim((string) $content);

    if ($content === '') {
        return '';
    }

    if (!preg_match('/<\s*[a-z][^>]*>/i', $content)) {
        return '<p>' . nl2br(htmlspecialchars($content, ENT_QUOTES, 'UTF-8')) . '</p>';
    }

    $allowed = [
        'p', 'br', 'h2', 'h3', 'h4', 'strong', 'b', 'em', 'i', 'u',
        'ul', 'ol', 'li', 'blockquote', 'table', 'thead', 'tbody',
        'tr', 'th', 'td', 'a', 'figure', 'figcaption', 'img', 'video', 'source',
    ];
    $dom = new DOMDocument('1.0', 'UTF-8');
    $previous = libxml_use_internal_errors(true);
    $dom->loadHTML('<?xml encoding="utf-8"?><div id="rich-root">' . $content . '</div>');
    libxml_clear_errors();
    libxml_use_internal_errors($previous);
    $root = $dom->getElementById('rich-root');

    if (!$root) {
        return '';
    }

    $clean = static function (DOMNode $node) use (&$clean, $allowed): string {
        if ($node instanceof DOMText) {
            return htmlspecialchars($node->textContent, ENT_QUOTES, 'UTF-8');
        }

        if (!$node instanceof DOMElement) {
            return '';
        }

        $tag = strtolower($node->tagName);

        if (in_array($tag, ['script', 'style', 'iframe', 'object', 'svg', 'form'], true)) {
            return '';
        }

        $inner = '';

        foreach ($node->childNodes as $child) {
            $inner .= $clean($child);
        }

        if (!in_array($tag, $allowed, true)) {
            return $inner;
        }

        if ($tag === 'br') {
            return '<br>';
        }

        $attributes = '';

        $safeUrl = static function (string $value): bool {
            return (bool) preg_match('~^(https?://|/)(?!/)~i', trim($value));
        };

        if ($tag === 'a') {
            $href = trim($node->getAttribute('href'));
            if ($safeUrl($href)) {
                $attributes = ' href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '" rel="noopener noreferrer"';
            }
        }

        if ($tag === 'img') {
            $src = trim($node->getAttribute('src'));
            if (!$safeUrl($src)) {
                return '';
            }
            $alt = trim($node->getAttribute('alt'));
            $attributes = ' src="' . htmlspecialchars($src, ENT_QUOTES, 'UTF-8') . '"'
                . ' alt="' . htmlspecialchars($alt, ENT_QUOTES, 'UTF-8') . '"'
                . ' loading="lazy"';
        }

        if ($tag === 'video') {
            $src = trim($node->getAttribute('src'));
            $attributes = ' controls preload="metadata"';
            if ($src !== '' && $safeUrl($src)) {
                $attributes .= ' src="' . htmlspecialchars($src, ENT_QUOTES, 'UTF-8') . '"';
            }
        }

        if ($tag === 'source') {
            $src = trim($node->getAttribute('src'));
            if (!$safeUrl($src)) {
                return '';
            }
            $attributes = ' src="' . htmlspecialchars($src, ENT_QUOTES, 'UTF-8') . '"';
        }

        if ($tag === 'figure') {
            $width = $node->getAttribute('data-width');
            $width = max(10, min(100, (int) $width ?: 100));
            $attributes = ' class="editor-media-block" data-align="center"'
                . ' data-width="' . $width . '" style="width:' . $width . '%"';
        }

        return '<' . $tag . $attributes . '>' . $inner . '</' . $tag . '>';
    };

    $html = '';

    foreach ($root->childNodes as $child) {
        $html .= $clean($child);
    }

    return $html;
}
