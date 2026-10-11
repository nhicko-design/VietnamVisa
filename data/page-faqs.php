<?php

$pageFaqs = [];

if (!empty($page_faq_key)) {
    try {
        $statement = db()->prepare(
            'SELECT question, answer, question_vn, answer_vn
             FROM page_faq
             WHERE page_key = :page_key AND is_active = 1
             ORDER BY sort_order ASC, id ASC'
        );
        $statement->execute(['page_key' => $page_faq_key]);
        $pageFaqs = $statement->fetchAll(PDO::FETCH_ASSOC);
    } catch (Throwable $exception) {
        $pageFaqs = [];
    }
}
