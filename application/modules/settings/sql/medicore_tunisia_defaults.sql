-- MediCore runs in Tunis, Tunisia, and bills in Tunisian dinars.
-- The currency keeps a trailing space so views print "TND 500", not "TND500".
-- Amounts are not converted. Only the currency label changes.

UPDATE settings SET currency = 'TND ', timezone = 'Africa/Tunis';
UPDATE website_settings SET currency = 'TND ';
UPDATE site_settings SET currency = 'TND ';

-- Platform contact details.
UPDATE settings s
JOIN superadmin a ON a.phone <> ''
SET s.address = a.address, s.phone = a.phone
WHERE s.hospital_id = 'superadmin';

UPDATE website_settings w
JOIN superadmin a ON a.phone <> ''
SET w.address = a.address, w.phone = a.phone;

-- Each hospital shows the address and phone of its own record.
UPDATE settings s
JOIN hospital h ON s.hospital_id = CAST(h.id AS CHAR)
SET s.address = h.address, s.phone = h.phone
WHERE h.address LIKE '%Tuni%' OR h.country = 'Tunisia';

UPDATE site_settings s
JOIN hospital h ON s.hospital_id = CAST(h.id AS CHAR)
SET s.address = h.address, s.phone = h.phone
WHERE h.address LIKE '%Tuni%' OR h.country = 'Tunisia';
