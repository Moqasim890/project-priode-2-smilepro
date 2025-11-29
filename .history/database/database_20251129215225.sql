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
    medewerkerid INT NOT NULL,
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
    CONSTRAINT fk_afspraken_medewerker FOREIGN KEY (medewerkerid) REFERENCES medewerker(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- Nep users voor Laravel authenticatie
INSERT INTO users (name, email, password, created_at, updated_at) VALUES
('Jan de Vries', 'jan.devries@smilepro.nl', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TuBkNWJJnqsGl0b9c0Z9oK9i.9K.', NOW(), NOW()),
('Sarah Bakker', 'sarah.bakker@smilepro.nl', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TuBkNWJJnqsGl0b9c0Z9oK9i.9K.', NOW(), NOW()),
('Mohammed Ali', 'mohammed.ali@smilepro.nl', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TuBkNWJJnqsGl0b9c0Z9oK9i.9K.', NOW(), NOW()),
('Emma Peters', 'emma.peters@smilepro.nl', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TuBkNWJJnqsGl0b9c0Z9oK9i.9K.', NOW(), NOW()),
('Lucas van Dam', 'lucas.vandam@smilepro.nl', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TuBkNWJJnqsGl0b9c0Z9oK9i.9K.', NOW(), NOW());

-- Personen gelinkt aan users en standalone patienten
INSERT INTO persoon (gebruikerid, voornaam, tussenvoegsel, achternaam, geboortedatum, isactief) VALUES
-- Gelinkt aan users (IDs 4-8 als er al 3 users bestaan van seeders)
((SELECT id FROM users WHERE email = 'jan.devries@smilepro.nl'), 'Jan', 'de', 'Vries', '1985-03-15', 1),
((SELECT id FROM users WHERE email = 'sarah.bakker@smilepro.nl'), 'Sarah', NULL, 'Bakker', '1992-07-22', 1),
((SELECT id FROM users WHERE email = 'mohammed.ali@smilepro.nl'), 'Mohammed', NULL, 'Ali', '1978-11-08', 1),
((SELECT id FROM users WHERE email = 'emma.peters@smilepro.nl'), 'Emma', NULL, 'Peters', '1995-05-12', 1),
((SELECT id FROM users WHERE email = 'lucas.vandam@smilepro.nl'), 'Lucas', 'van', 'Dam', '1988-09-30', 1),
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

-- Contactgegevens voor patienten
INSERT INTO contact (patientid, straatnaam, huisnummer, postcode, plaats, mobiel, email, isactief) VALUES
(1, 'Hoofdstraat', '123', '1234AB', 'Utrecht', '0612345678', 'jan.devries@smilepro.nl', 1),
(2, 'Kerkstraat', '45', '5678CD', 'Utrecht', '0687654321', 'sarah.bakker@smilepro.nl', 1),
(3, 'Plein', '7', '9012EF', 'Utrecht', '0698765432', 'mohammed.ali@smilepro.nl', 1),
(4, 'Laan', '89', '3456GH', 'Utrecht', '0645678901', 'emma.peters@smilepro.nl', 1),
(5, 'Singel', '12', '7890IJ', 'Utrecht', '0656789012', 'lucas.vandam@smilepro.nl', 1),
(6, 'Dreef', '34', '2345KL', 'Utrecht', '0667890123', 'sophie.j@example.com', 1),
(7, 'Park', '56', '6789MN', 'Utrecht', '0678901234', 'david.vdb@example.com', 1);

-- Behandelingen
INSERT INTO behandeling (medewerkerid, patientid, datum, tijd, behandelingtype, omschrijving, kosten, status, isactief) VALUES
-- Patient 1 (Jan)
(NULL, 1, '2024-11-01', '10:00:00', 'Controles', 'Halfjaarlijkse controle', 75.00, 'Behandeld', 1),
(NULL, 1, '2024-11-15', '14:30:00', 'Gebitsreiniging', 'Tandsteen verwijderen', 85.00, 'Behandeld', 1),
(NULL, 1, '2024-12-10', '11:00:00', 'Vullingen', 'Vulling kies rechtsonder', 150.00, 'Onbehandeld', 1),
-- Patient 2 (Sarah)
(NULL, 2, '2024-10-20', '09:30:00', 'Controles', 'Jaarlijkse controle', 75.00, 'Behandeld', 1),
(NULL, 2, '2024-11-05', '15:00:00', 'Vullingen', 'Twee vullingen boventanden', 275.00, 'Behandeld', 1),
(NULL, 2, '2024-12-01', '10:30:00', 'Gebitsreiniging', 'Reguliere reiniging', 85.00, 'Onbehandeld', 1),
-- Patient 3 (Mohammed)
(NULL, 3, '2024-09-15', '13:00:00', 'Controles', 'Controle na orthodontie', 75.00, 'Behandeld', 1),
(NULL, 3, '2024-10-30', '16:00:00', 'Wortelkanaalbehandelingen', 'Wortelkanaalbehandeling kies', 450.00, 'Behandeld', 1),
(NULL, 3, '2024-11-20', '14:00:00', 'Gebitsreiniging', 'Grondige reiniging', 85.00, 'Behandeld', 1),
-- Patient 4 (Emma)
(NULL, 4, '2024-11-10', '08:00:00', 'Controles', 'Controle met extra zorg voor angstpatiënt', 75.00, 'Behandeld', 1),
(NULL, 4, '2024-11-28', '08:00:00', 'Gebitsreiniging', 'Voorzichtige reiniging', 85.00, 'Behandeld', 1),
-- Patient 5 (Lucas)
(NULL, 5, '2024-10-05', '13:30:00', 'Controles', 'Kaakgewricht controle', 75.00, 'Behandeld', 1),
(NULL, 5, '2024-11-12', '14:00:00', 'Vullingen', 'Vulling linksboven', 150.00, 'Behandeld', 1),
-- Patient 6 (Sophie)
(NULL, 6, '2024-09-20', '09:00:00', 'Controles', 'Zwangerschapscontrole', 75.00, 'Behandeld', 1),
(NULL, 6, '2024-11-25', '10:00:00', 'Gebitsreiniging', 'Extra fluoride behandeling', 95.00, 'Behandeld', 1),
-- Patient 7 (David)
(NULL, 7, '2024-10-10', '15:30:00', 'Controles', 'Diabetes controle tandvlees', 75.00, 'Behandeld', 1),
(NULL, 7, '2024-11-18', '16:00:00', 'Gebitsreiniging', 'Tandvlees behandeling', 95.00, 'Behandeld', 1),
(NULL, 7, '2024-12-05', '15:00:00', 'Vullingen', 'Twee vullingen', 275.00, 'Onbehandeld', 1);

-- Facturen
INSERT INTO factuur (patientid, nummer, datum, bedrag, status, isactief) VALUES
-- Patient 1 (Jan)
(1, 'F2024-0001', '2024-11-02', 160.00, 'Betaald', 1),
(1, 'F2024-0002', '2024-12-11', 150.00, 'Verzonden', 1),
-- Patient 2 (Sarah)
(2, 'F2024-0003', '2024-10-21', 75.00, 'Betaald', 1),
(2, 'F2024-0004', '2024-11-06', 275.00, 'Onbetaald', 1),
-- Patient 3 (Mohammed)
(3, 'F2024-0005', '2024-09-16', 75.00, 'Betaald', 1),
(3, 'F2024-0006', '2024-11-01', 535.00, 'Verzonden', 1),
-- Patient 4 (Emma)
(4, 'F2024-0007', '2024-11-11', 160.00, 'Betaald', 1),
-- Patient 5 (Lucas)
(5, 'F2024-0008', '2024-10-06', 75.00, 'Betaald', 1),
(5, 'F2024-0009', '2024-11-13', 150.00, 'Verzonden', 1),
-- Patient 6 (Sophie)
(6, 'F2024-0010', '2024-09-21', 75.00, 'Betaald', 1),
(6, 'F2024-0011', '2024-11-26', 95.00, 'Onbetaald', 1),
-- Patient 7 (David)
(7, 'F2024-0012', '2024-10-11', 75.00, 'Betaald', 1),
(7, 'F2024-0013', '2024-11-19', 95.00, 'Verzonden', 1);

-- Factuur behandeling koppelingen
INSERT INTO factuur_behandeling (factuurid, behandelingid, isactief) VALUES
-- Factuur 1 (Patient 1) - Controle + Gebitsreiniging
(1, 1, 1),
(1, 2, 1),
-- Factuur 2 (Patient 1) - Vulling
(2, 3, 1),
-- Factuur 3 (Patient 2) - Controle
(3, 4, 1),
-- Factuur 4 (Patient 2) - Vullingen
(4, 5, 1),
-- Factuur 5 (Patient 3) - Controle
(5, 7, 1),
-- Factuur 6 (Patient 3) - Wortelkanaalbehandeling + Gebitsreiniging
(6, 8, 1),
(6, 9, 1),
-- Factuur 7 (Patient 4) - Controle + Gebitsreiniging
(7, 10, 1),
(7, 11, 1),
-- Factuur 8 (Patient 5) - Controle
(8, 12, 1),
-- Factuur 9 (Patient 5) - Vulling
(9, 13, 1),
-- Factuur 10 (Patient 6) - Controle
(10, 14, 1),
-- Factuur 11 (Patient 6) - Gebitsreiniging met fluoride
(11, 15, 1),
-- Factuur 12 (Patient 7) - Controle
(12, 16, 1),
-- Factuur 13 (Patient 7) - Gebitsreiniging
(13, 17, 1);

-- Afspraken
INSERT INTO afspraken (patientid, medewerkerid, datum, tijd, status, isactief) VALUES
(1, NULL, '2025-01-15', '10:00:00', 'Bevestigd', 1),
(2, NULL, '2025-01-20', '14:30:00', 'Bevestigd', 1),
(3, NULL, '2025-02-01', '09:00:00', 'Bevestigd', 1),
(4, NULL, '2025-01-08', '08:00:00', 'Bevestigd', 1),
(5, NULL, '2025-02-15', '13:30:00', 'Bevestigd', 1),
(6, NULL, '2025-01-25', '09:30:00', 'Bevestigd', 1),
(7, NULL, '2025-02-10', '15:30:00', 'Bevestigd', 1),
(1, NULL, '2025-02-10', '11:00:00', 'Bevestigd', 1);

-- Communicatie
INSERT INTO communicatie (patientid, medewerkerid, bericht, verzonden_datum, isactief) VALUES
(1, NULL, 'Herinnering: uw afspraak op 15 januari 2025 om 10:00', '2025-01-10 09:00:00', 1),
(2, NULL, 'Bevestiging afspraak 20 januari 2025 om 14:30', '2024-12-15 10:30:00', 1),
(3, NULL, 'Uw behandeling is voltooid. Controle over 6 maanden aanbevolen.', '2024-11-21 16:15:00', 1),
(4, NULL, 'Herinnering: uw afspraak op 8 januari 2025 om 08:00', '2025-01-03 09:00:00', 1),
(5, NULL, 'Bevestiging afspraak 15 februari 2025 om 13:30', '2025-01-20 10:00:00', 1),
(6, NULL, 'Zwangerschapscontrole succesvol afgerond', '2024-11-26 14:00:00', 1),
(7, NULL, 'Let op tandvlees gezondheid bij diabetes. Volgende controle februari.', '2024-11-19 16:30:00', 1);

-- Feedback
INSERT INTO feedback (patientid, beoordeling, praktijkemail, praktijktelefoon, opmerking, isactief) VALUES
(1, 5, 'info@smilepro.nl', '030-1234567', 'Uitstekende service, zeer tevreden!', 1),
(2, 4, 'info@smilepro.nl', '030-1234567', 'Goede behandeling, vriendelijk personeel', 1),
(3, 5, 'info@smilepro.nl', '030-1234567', 'Professioneel en pijnloos', 1),
(4, 5, 'info@smilepro.nl', '030-1234567', 'Geweldig geduld met angstpatiënt, super!', 1),
(5, 4, 'info@smilepro.nl', '030-1234567', 'Goed geholpen met kaakklachten', 1),
(6, 5, 'info@smilepro.nl', '030-1234567', 'Perfecte zorg tijdens zwangerschap', 1),
(7, 5, 'info@smilepro.nl', '030-1234567', 'Goede aandacht voor diabeteszorg', 1);

