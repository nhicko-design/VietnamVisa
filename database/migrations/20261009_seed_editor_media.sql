INSERT INTO editor_media (media_type, file_path, title, alt_text)
SELECT source.media_type, source.file_path, source.title, source.alt_text
FROM (
    SELECT media_type, file_path, title, alt_text FROM service_media
    UNION ALL
    SELECT media_type, file_path, title, alt_text FROM news_media
    UNION ALL
    SELECT media_type, file_path, title, alt_text FROM visa_media
) AS source
WHERE NOT EXISTS (
    SELECT 1 FROM editor_media library WHERE library.file_path = source.file_path
);
