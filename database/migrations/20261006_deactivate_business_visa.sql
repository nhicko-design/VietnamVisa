-- Business Visa is retained in Admin as an inactive service.
UPDATE services
SET is_active = 0
WHERE slug = 'business-visa';
