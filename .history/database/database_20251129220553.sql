-- Active: 1764442526270@@127.0.0.1@3333@tandarts
-- Zorg dat we schone lei hebben (drop in juiste volgorde)
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS feedback;

DROP TABLE IF EXISTS factuur_behandeling;

DROP TABLE IF EXISTS communicatie;
DROP TABLE IF EXISTS factuur;
DROP TABLE IF EXISTS behandeling;
DROP TABLE IF EXISTS afspraken;
DROP TABLE IF EXISTS contact;
DROP TABLE IF EXISTS beschikbaarheid;
DROP TABLE IF EXISTS medewerker;
DROP TABLE IF EXISTS patient;
DROP TABLE IF EXISTS persoon;
SET FOREIGN_KEY_CHECKS = 1;

-- =========================================
-- 1. PERSOON
-- =========================================
CREATE TABLE persoon (
    id INT PRIMARY KEY AUTO_INCREMENT,
    gebruikerid BIGINT UNSIGNED DEFAULT NULL,
    voornaam VARCHAR(100) NOT NULL,
    tussenvoegsel VARCHAR(20),
    achternaam VARCHAR(100) NOT NULL,
    geboortedatum DATE NOT NULL,
    isactief TINYINT(1) DEFAULT 1,
    opmerking TEXT,
    datumaangemaakt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    datumgewijzigd DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX ix_persoon_gebruikerid (gebruikerid),
    CONSTRAINT fk_persoon_users FOREIGN KEY (gebruikerid) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- 2. PATIENT
-- =========================================
CREATE TABLE patient (
    id INT PRIMARY KEY AUTO_INCREMENT,
    persoonid INT NOT NULL,
    nummer VARCHAR(50) NOT NULL,
    medischdossier TEXT,
    isactief TINYINT(1) DEFAULT 1,
    opmerking TEXT,
    datumaangemaakt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    datumgewijzigd DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY ux_patient_nummer (nummer),
    INDEX ix_patient_persoonid (persoonid),
    CONSTRAINT fk_patient_persoon FOREIGN KEY (persoonid) REFERENCES persoon(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- 4. MEDEWERKER
-- =========================================
CREATE TABLE medewerker (
    id INT PRIMARY KEY AUTO_INCREMENT,
    persoonid INT NOT NULL,
    nummer VARCHAR(50) NOT NULL,
    medewerkertype ENUM('Assistent','Mondhygiënist','Tandarts','Praktijkmanagement') NOT NULL,
    specialisatie VARCHAR(255),
    beschikbaarheid TEXT,
    isactief TINYINT(1) DEFAULT 1,
    opmerking TEXT,
    datumaangemaakt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    datumgewijzigd DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY ux_medewerker_nummer (nummer),
    INDEX ix_medewerker_persoonid (persoonid),
    CONSTRAINT fk_medewerker_persoon FOREIGN KEY (persoonid) REFERENCES persoon(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- 5. BESCHIKBAARHEID
-- =========================================
CREATE TABLE beschikbaarheid (
    id INT PRIMARY KEY AUTO_INCREMENT,
    medewerkerid INT NOT NULL,
    datumvanaf DATE NOT NULL,
    datumtotmet DATE NOT NULL,
    tijdvanaf TIME NOT NULL,
    tijdtotmet TIME NOT NULL,
    status ENUM('Aanwezig','Afwezig','Verlof','Ziek') NOT NULL,
    isactief TINYINT(1) DEFAULT 1,
    opmerking TEXT,
    datumaangemaakt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    datumgewijzigd DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX ix_beschikbaarheid_medewerkerid (medewerkerid),
    CONSTRAINT fk_beschikbaarheid_medewerker FOREIGN KEY (medewerkerid) REFERENCES medewerker(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- 6. CONTACT
-- =========================================
CREATE TABLE contact (
    id INT PRIMARY KEY AUTO_INCREMENT,
    patientid INT NOT NULL,
    straatnaam VARCHAR(100) NOT NULL,
    huisnummer VARCHAR(10) NOT NULL,
    toevoeging VARCHAR(10),
    postcode VARCHAR(10) NOT NULL,
    plaats VARCHAR(100) NOT NULL,
    mobiel VARCHAR(20),
    email VARCHAR(255),
    isactief TINYINT(1) DEFAULT 1,
    opmerking TEXT,
    datumaangemaakt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    datumgewijzigd DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX ix_contact_patientid (patientid),
    CONSTRAINT fk_contact_patient FOREIGN KEY (patientid) REFERENCES patient(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- 7. AFSPRAKEN
-- =========================================
CREATE TABLE afspraken (
    id INT PRIMARY KEY AUTO_INCREMENT,
    patientid INT NOT NULL,
    medewerkerid INT,
    datum DATE NOT NULL,
    tijd TIME NOT NULL,
    status ENUM('Bevestigd','Geannuleerd') NOT NULL DEFAULT 'Bevestigd',
    isactief TINYINT(1) DEFAULT 1,
    opmerking TEXT,
    datumaangemaakt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    datumgewijzigd DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX ix_afspraken_patientid (patientid),
    INDEX ix_afspraken_medewerkerid (medewerkerid),
    CONSTRAINT fk_afspraken_patient FOREIGN KEY (patientid) REFERENCES patient(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_afspraken_medewerker FOREIGN KEY (medewerkerid) REFERENCES medewerker(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Pas medewerkerid nullable voor SET NULL
ALTER TABLE afspraken MODIFY COLUMN medewerkerid INT DEFAULT NULL;

-- =========================================
-- 8. BEHANDELING
-- =========================================
CREATE TABLE behandeling (
    id INT PRIMARY KEY AUTO_INCREMENT,
    medewerkerid INT,
    patientid INT NOT NULL,
    datum DATE NOT NULL,
    tijd TIME NOT NULL,
    behandelingtype ENUM('Controles','Vullingen','Gebitsreiniging','Orthodontie','Wortelkanaalbehandelingen') NOT NULL,
    omschrijving TEXT,
    kosten DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    status ENUM('Behandeld','Onbehandeld','Uitgesteld') NOT NULL DEFAULT 'Onbehandeld',
    isactief TINYINT(1) DEFAULT 1,
    opmerking TEXT,
    datumaangemaakt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    datumgewijzigd DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX ix_behandeling_medewerkerid (medewerkerid),
    INDEX ix_behandeling_patientid (patientid),
    CONSTRAINT fk_behandeling_medewerker FOREIGN KEY (medewerkerid) REFERENCES medewerker(id) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_behandeling_patient FOREIGN KEY (patientid) REFERENCES patient(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Opmerking: ON DELETE SET NULL vereist dat kolom nullable - dus we passen kolom aan:
ALTER TABLE behandeling MODIFY COLUMN medewerkerid INT DEFAULT NULL;
-- Herstel FK (verwijder en maak opnieuw om SET NULL toe te passen)
ALTER TABLE behandeling DROP FOREIGN KEY fk_behandeling_medewerker;
ALTER TABLE behandeling ADD CONSTRAINT fk_behandeling_medewerker FOREIGN KEY (medewerkerid) REFERENCES medewerker(id) ON DELETE SET NULL ON UPDATE CASCADE;

-- =========================================
-- 9. FACTUUR
-- =========================================
CREATE TABLE factuur (
    id INT PRIMARY KEY AUTO_INCREMENT,
    patientid INT NOT NULL,
    nummer VARCHAR(50) NOT NULL,
    datum DATE NOT NULL,
    bedrag DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    status ENUM('Verzonden','Niet-Verzonden','Betaald','Onbetaald') NOT NULL DEFAULT 'Niet-Verzonden',
    isactief TINYINT(1) DEFAULT 1,
    opmerking TEXT,
    datumaangemaakt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    datumgewijzigd DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY ux_factuur_nummer (nummer),
    INDEX ix_factuur_patientid (patientid),
    CONSTRAINT fk_factuur_patient FOREIGN KEY (patientid) REFERENCES patient(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- 9B. FACTUUR_BEHANDELING (Tussentabel)
-- =========================================
CREATE TABLE factuur_behandeling (
    id INT PRIMARY KEY AUTO_INCREMENT,
    factuurid INT NOT NULL,
    behandelingid INT NOT NULL,
    isactief TINYINT(1) DEFAULT 1,
    datumaangemaakt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    datumgewijzigd DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX ix_factuur_behandeling_factuurid (factuurid),
    INDEX ix_factuur_behandeling_behandelingid (behandelingid),
    UNIQUE KEY ux_factuur_behandeling (factuurid, behandelingid),
    CONSTRAINT fk_factuur_behandeling_factuur FOREIGN KEY (factuurid) REFERENCES factuur(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_factuur_behandeling_behandeling FOREIGN KEY (behandelingid) REFERENCES behandeling(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- 10. COMMUNICATIE
-- =========================================
CREATE TABLE communicatie (
    id INT PRIMARY KEY AUTO_INCREMENT,
    patientid INT NOT NULL,
    medewerkerid INT,
    bericht TEXT NOT NULL,
    verzonden_datum DATETIME NOT NULL,
    isactief TINYINT(1) DEFAULT 1,
    opmerking TEXT,
    datumaangemaakt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    datumgewijzigd DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX ix_communicatie_patientid (patientid),
    INDEX ix_communicatie_medewerkerid (medewerkerid),
    CONSTRAINT fk_communicatie_patient FOREIGN KEY (patientid) REFERENCES patient(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_communicatie_medewerker FOREIGN KEY (medewerkerid) REFERENCES medewerker(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Pas medewerkerid nullable voor SET NULL
ALTER TABLE communicatie MODIFY COLUMN medewerkerid INT DEFAULT NULL;
ALTER TABLE communicatie DROP FOREIGN KEY fk_communicatie_medewerker;
ALTER TABLE communicatie ADD CONSTRAINT fk_communicatie_medewerker FOREIGN KEY (medewerkerid) REFERENCES medewerker(id) ON DELETE SET NULL ON UPDATE CASCADE;

-- =========================================
-- 11. FEEDBACK
-- =========================================
CREATE TABLE feedback (
    id INT PRIMARY KEY AUTO_INCREMENT,
    patientid INT NOT NULL,
    beoordeling INT NOT NULL,
    praktijkemail VARCHAR(255),
    praktijktelefoon VARCHAR(20),
    isactief TINYINT(1) DEFAULT 1,
    opmerking TEXT,
    datumaangemaakt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    datumgewijzigd DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CHECK (beoordeling BETWEEN 1 AND 5),
    INDEX ix_feedback_patientid (patientid),
    CONSTRAINT fk_feedback_patient FOREIGN KEY (patientid) REFERENCES patient(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- STORED PROCEDURES
-- =========================================

-- Procedure om totaal factuurbedrag te berekenen
DROP PROCEDURE IF EXISTS SP_GetTotaalFactuurBedrag;

DELIMITER $$

CREATE PROCEDURE SP_GetTotaalFactuurBedrag(
    IN p_status VARCHAR(50)
)
BEGIN
    IF p_status IS NULL OR p_status = '' THEN
        -- Alle actieve facturen
        SELECT 
            COUNT(*) as aantal_facturen,
            COALESCE(SUM(bedrag), 0) as totaal_bedrag
        FROM factuur
        WHERE isactief = 1;
    ELSE
        -- Facturen met specifieke status
        SELECT 
            COUNT(*) as aantal_facturen,
            COALESCE(SUM(bedrag), 0) as totaal_bedrag
        FROM factuur
        WHERE isactief = 1 AND status = p_status;
    END IF;
END$$

DELIMITER ;

-- Procedure om factuur overzicht per patient te krijgen
DROP PROCEDURE IF EXISTS SP_GetFacturenPerPatient;

DELIMITER $$

CREATE PROCEDURE SP_GetFacturenPerPatient(
    IN p_patientid INT
)
BEGIN
    SELECT 
        f.id,
        f.nummer,
        f.datum,
        f.bedrag,
        f.status,
        GROUP_CONCAT(b.behandelingtype SEPARATOR ', ') as behandelingen,
        CONCAT(p.voornaam, ' ', COALESCE(p.tussenvoegsel, ''), ' ', p.achternaam) as patient_naam
    FROM factuur f
    INNER JOIN patient pat ON f.patientid = pat.id
    INNER JOIN persoon p ON pat.persoonid = p.id
    LEFT JOIN factuur_behandeling fb ON f.id = fb.factuurid AND fb.isactief = 1
    LEFT JOIN behandeling b ON fb.behandelingid = b.id
    WHERE f.patientid = p_patientid 
        AND f.isactief = 1
    GROUP BY f.id, f.nummer, f.datum, f.bedrag, f.status, p.voornaam, p.tussenvoegsel, p.achternaam
    ORDER BY f.datum DESC;
END$$

DELIMITER ;

-- =========================================
-- SEED DATA
-- =========================================

-- Nep users voor Laravel authenticatie (alleen als ze nog niet bestaan)
INSERT IGNORE INTO users (name, email, password, created_at, updated_at) VALUES
('Jan de Vries', 'jan.devries@patient.nl', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TuBkNWJJnqsGl0b9c0Z9oK9i.9K.', NOW(), NOW()),
('Sarah Bakker', 'sarah.bakker@patient.nl', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TuBkNWJJnqsGl0b9c0Z9oK9i.9K.', NOW(), NOW()),
('Mohammed Ali', 'mohammed.ali@patient.nl', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TuBkNWJJnqsGl0b9c0Z9oK9i.9K.', NOW(), NOW()),
('Emma Peters', 'emma.peters@patient.nl', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TuBkNWJJnqsGl0b9c0Z9oK9i.9K.', NOW(), NOW()),
('Lucas van Dam', 'lucas.vandam@patient.nl', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TuBkNWJJnqsGl0b9c0Z9oK9i.9K.', NOW(), NOW());

-- Personen gelinkt aan users en standalone patienten
INSERT INTO persoon (gebruikerid, voornaam, tussenvoegsel, achternaam, geboortedatum, isactief) VALUES
-- Gelinkt aan users
((SELECT id FROM users WHERE email = 'jan.devries@patient.nl'), 'Jan', 'de', 'Vries', '1985-03-15', 1),
((SELECT id FROM users WHERE email = 'sarah.bakker@patient.nl'), 'Sarah', NULL, 'Bakker', '1992-07-22', 1),
((SELECT id FROM users WHERE email = 'mohammed.ali@patient.nl'), 'Mohammed', NULL, 'Ali', '1978-11-08', 1),
((SELECT id FROM users WHERE email = 'emma.peters@patient.nl'), 'Emma', NULL, 'Peters', '1995-05-12', 1),
((SELECT id FROM users WHERE email = 'lucas.vandam@patient.nl'), 'Lucas', 'van', 'Dam', '1988-09-30', 1),
-- Standalone patienten zonder user account
(NULL, 'Sophie', NULL, 'Jansen', '1990-02-14', 1),
(NULL, 'David', 'van der', 'Berg', '1982-06-25', 1);

-- Patienten (gebruik de zojuist aangemaakte personen)
INSERT INTO patient (persoonid, nummer, medischdossier, isactief) 
SELECT id, CONCAT('P', LPAD(id, 5, '0')), 
    CASE 
        WHEN voornaam = 'Jan' THEN 'Regelmatige controles, geen bijzonderheden'
        WHEN voornaam = 'Sarah' THEN 'Gevoelige tanden, regelmatig tandsteen'
        WHEN voornaam = 'Mohammed' THEN 'Orthodontie behandeling in 2023 afgerond'
        WHEN voornaam = 'Emma' THEN 'Angstpatiënt, bij voorkeur 08:00 afspraken'
        WHEN voornaam = 'Lucas' THEN 'Kaakgewricht klachten, onder controle'
        WHEN voornaam = 'Sophie' THEN 'Zwangerschap, extra fluoride aanbevolen'
        WHEN voornaam = 'David' THEN 'Diabetes type 2, tandvlees goed monitoren'
        ELSE 'Standaard patiënt'
    END,
    1
FROM persoon 
WHERE id > 3  -- Skip eerste 3 personen van Laravel seeders
ORDER BY id;

-- Contactgegevens voor patienten (gebruik patient IDs dynamisch)
INSERT INTO contact (patientid, straatnaam, huisnummer, postcode, plaats, mobiel, email, isactief)
SELECT 
    pt.id,
    CASE 
        WHEN ps.voornaam = 'Jan' THEN 'Hoofdstraat'
        WHEN ps.voornaam = 'Sarah' THEN 'Kerkstraat'
        WHEN ps.voornaam = 'Mohammed' THEN 'Plein'
        WHEN ps.voornaam = 'Emma' THEN 'Laan'
        WHEN ps.voornaam = 'Lucas' THEN 'Singel'
        WHEN ps.voornaam = 'Sophie' THEN 'Dreef'
        WHEN ps.voornaam = 'David' THEN 'Park'
    END,
    CASE 
        WHEN ps.voornaam = 'Jan' THEN '123'
        WHEN ps.voornaam = 'Sarah' THEN '45'
        WHEN ps.voornaam = 'Mohammed' THEN '7'
        WHEN ps.voornaam = 'Emma' THEN '89'
        WHEN ps.voornaam = 'Lucas' THEN '12'
        WHEN ps.voornaam = 'Sophie' THEN '34'
        WHEN ps.voornaam = 'David' THEN '56'
    END,
    CONCAT(SUBSTRING('1234567890', pt.id, 4), 'AB'),
    'Utrecht',
    CONCAT('06', LPAD(pt.id * 12345678, 8, '0')),
    CASE 
        WHEN ps.gebruikerid IS NOT NULL THEN LOWER(CONCAT(ps.voornaam, '.', REPLACE(CONCAT(COALESCE(ps.tussenvoegsel, ''), ps.achternaam), ' ', ''), '@patient.nl'))
        ELSE CONCAT(LOWER(ps.voornaam), '.', LOWER(SUBSTRING(ps.achternaam, 1, 1)), '@example.com')
    END,
    1
FROM patient pt
INNER JOIN persoon ps ON pt.persoonid = ps.id
WHERE ps.id > 3;

-- Behandelingen (gebruik patient IDs dynamisch)
INSERT INTO behandeling (medewerkerid, patientid, datum, tijd, behandelingtype, omschrijving, kosten, status, isactief)
SELECT 
    NULL,
    pt.id,
    DATE_SUB(CURDATE(), INTERVAL (pt.id * 7) DAY),
    TIME(CONCAT(LPAD(8 + (pt.id % 9), 2, '0'), ':00:00')),
    'Controles',
    CONCAT('Controle voor ', ps.voornaam),
    75.00,
    'Behandeld',
    1
FROM patient pt
INNER JOIN persoon ps ON pt.persoonid = ps.id
WHERE ps.id > 3
UNION ALL
SELECT 
    NULL,
    pt.id,
    DATE_SUB(CURDATE(), INTERVAL (pt.id * 5) DAY),
    TIME(CONCAT(LPAD(9 + (pt.id % 8), 2, '0'), ':30:00')),
    CASE WHEN pt.id % 2 = 0 THEN 'Gebitsreiniging' ELSE 'Vullingen' END,
    CASE WHEN pt.id % 2 = 0 THEN 'Tandsteen verwijderen' ELSE 'Vulling plaatsen' END,
    CASE WHEN pt.id % 2 = 0 THEN 85.00 ELSE 150.00 END,
    'Behandeld',
    1
FROM patient pt
INNER JOIN persoon ps ON pt.persoonid = ps.id
WHERE ps.id > 3 AND pt.id <= (SELECT MIN(id) + 4 FROM patient WHERE persoonid > 3)
UNION ALL
SELECT 
    NULL,
    pt.id,
    DATE_ADD(CURDATE(), INTERVAL (pt.id * 3) DAY),
    TIME(CONCAT(LPAD(10 + (pt.id % 7), 2, '0'), ':00:00')),
    'Gebitsreiniging',
    'Geplande reiniging',
    85.00,
    'Onbehandeld',
    1
FROM patient pt
INNER JOIN persoon ps ON pt.persoonid = ps.id
WHERE ps.id > 3 AND pt.id <= (SELECT MIN(id) + 2 FROM patient WHERE persoonid > 3);

-- Facturen (gebruik patient IDs dynamisch)
INSERT INTO factuur (patientid, nummer, datum, bedrag, status, isactief)
SELECT 
    pt.id,
    CONCAT('F2024-', LPAD(pt.id, 4, '0')),
    DATE_SUB(CURDATE(), INTERVAL (pt.id * 6) DAY),
    CASE 
        WHEN pt.id % 3 = 0 THEN 160.00
        WHEN pt.id % 3 = 1 THEN 75.00
        ELSE 150.00
    END,
    CASE 
        WHEN pt.id % 4 = 0 THEN 'Betaald'
        WHEN pt.id % 4 = 1 THEN 'Verzonden'
        WHEN pt.id % 4 = 2 THEN 'Onbetaald'
        ELSE 'Niet-Verzonden'
    END,
    1
FROM patient pt
INNER JOIN persoon ps ON pt.persoonid = ps.id
WHERE ps.id > 3;

-- Factuur behandeling koppelingen (koppel eerste behandeling aan factuur)
INSERT INTO factuur_behandeling (factuurid, behandelingid, isactief)
SELECT 
    f.id,
    b.id,
    1
FROM factuur f
INNER JOIN behandeling b ON f.patientid = b.patientid
WHERE b.id = (
    SELECT MIN(b2.id) 
    FROM behandeling b2 
    WHERE b2.patientid = f.patientid
);

-- Afspraken (gebruik patient IDs dynamisch)
INSERT INTO afspraken (patientid, medewerkerid, datum, tijd, status, isactief)
SELECT 
    pt.id,
    NULL,
    DATE_ADD(CURDATE(), INTERVAL (pt.id * 10) DAY),
    TIME(CONCAT(LPAD(9 + (pt.id % 8), 2, '0'), ':00:00')),
    'Bevestigd',
    1
FROM patient pt
INNER JOIN persoon ps ON pt.persoonid = ps.id
WHERE ps.id > 3;

-- Communicatie (gebruik patient IDs dynamisch)
INSERT INTO communicatie (patientid, medewerkerid, bericht, verzonden_datum, isactief)
SELECT 
    pt.id,
    NULL,
    CONCAT('Herinnering: uw afspraak is ingepland voor ', ps.voornaam),
    DATE_SUB(NOW(), INTERVAL (pt.id * 2) DAY),
    1
FROM patient pt
INNER JOIN persoon ps ON pt.persoonid = ps.id
WHERE ps.id > 3;

-- Feedback (gebruik patient IDs dynamisch)
INSERT INTO feedback (patientid, beoordeling, praktijkemail, praktijktelefoon, opmerking, isactief)
SELECT 
    pt.id,
    4 + (pt.id % 2),
    'info@smilepro.nl',
    '030-1234567',
    CONCAT('Goede ervaring met behandeling voor ', ps.voornaam),
    1
FROM patient pt
INNER JOIN persoon ps ON pt.persoonid = ps.id
WHERE ps.id > 3;

