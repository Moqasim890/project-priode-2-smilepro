-- =========================================
-- SMILEPRO DATABASE MIGRATIE
-- Versie: 1.0
-- Datum: 2026-01-04
-- =========================================
-- Dit bestand bevat alle tabellen en stored procedures
-- voor de SmilePro tandartspraktijk applicatie
-- =========================================

USE tandarts;

-- =========================================
-- STAP 1: VERWIJDER BESTAANDE TABELLEN
-- (in juiste volgorde vanwege foreign keys)
-- =========================================
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS feedback;
DROP TABLE IF EXISTS communicatie;
DROP TABLE IF EXISTS factuur_behandeling;
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
-- STAP 2: VERWIJDER BESTAANDE STORED PROCEDURES
-- =========================================
DROP PROCEDURE IF EXISTS SP_GetAllAfspraken;
DROP PROCEDURE IF EXISTS SP_GetAfspraakById;
DROP PROCEDURE IF EXISTS SP_CreateAfspraak;
DROP PROCEDURE IF EXISTS SP_UpdateAfspraak;
DROP PROCEDURE IF EXISTS SP_DeleteAfspraak;
DROP PROCEDURE IF EXISTS SP_GetAfsprakenStatistieken;
DROP PROCEDURE IF EXISTS SP_GetBeschikbareMedewerkers;
DROP PROCEDURE IF EXISTS SP_GetAllPatientenDropdown;
DROP PROCEDURE IF EXISTS SP_GetAllFacturen;
DROP PROCEDURE IF EXISTS SP_GetAllTotaalbedragFacturen;
DROP PROCEDURE IF EXISTS SP_GetAllPatienten;
DROP PROCEDURE IF EXISTS SP_GetAllUsers;
DROP PROCEDURE IF EXISTS SP_GetTotaalFactuurBedrag;
DROP PROCEDURE IF EXISTS SP_GetFacturenPerPatient;

-- =========================================
-- STAP 3: MAAK TABELLEN AAN
-- =========================================

-- =========================================
-- 1. PERSOON
-- Basis tabel voor alle personen (patiënten en medewerkers)
-- =========================================
CREATE TABLE persoon (
    id              INT UNSIGNED        PRIMARY KEY AUTO_INCREMENT,
    gebruikerid     BIGINT UNSIGNED     DEFAULT NULL,
    voornaam        VARCHAR(15)         NOT NULL,
    tussenvoegsel   VARCHAR(15)         DEFAULT NULL,
    achternaam      VARCHAR(25)         NOT NULL,
    geboortedatum   DATE                NOT NULL,
    isactief        BIT                 NOT NULL DEFAULT 1,
    opmerking       VARCHAR(255)        DEFAULT NULL,
    datumaangemaakt DATETIME(6)         NOT NULL DEFAULT NOW(6),
    datumgewijzigd  DATETIME(6)         NOT NULL DEFAULT NOW(6) ON UPDATE NOW(6),
    
    INDEX ix_persoon_gebruikerid (gebruikerid),
    INDEX ix_persoon_achternaam (achternaam),
    CONSTRAINT fk_persoon_users FOREIGN KEY (gebruikerid) 
        REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- 2. PATIENT
-- Patiënt gegevens gekoppeld aan persoon
-- =========================================
CREATE TABLE patient (
    id              INT UNSIGNED        PRIMARY KEY AUTO_INCREMENT,
    persoonid       INT UNSIGNED        NOT NULL,
    nummer          VARCHAR(10)         NOT NULL,
    medischdossier  LONGBLOB            DEFAULT NULL,
    isactief        BIT                 NOT NULL DEFAULT 1,
    opmerking       VARCHAR(255)        DEFAULT NULL,
    datumaangemaakt DATETIME(6)         NOT NULL DEFAULT NOW(6),
    datumgewijzigd  DATETIME(6)         NOT NULL DEFAULT NOW(6) ON UPDATE NOW(6),
    
    UNIQUE KEY ux_patient_nummer (nummer),
    INDEX ix_patient_persoonid (persoonid),
    CONSTRAINT fk_patient_persoon FOREIGN KEY (persoonid) 
        REFERENCES persoon(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- 3. MEDEWERKER
-- Medewerker gegevens gekoppeld aan persoon
-- =========================================
CREATE TABLE medewerker (
    id              INT UNSIGNED        PRIMARY KEY AUTO_INCREMENT,
    persoonid       INT UNSIGNED        NOT NULL,
    nummer          VARCHAR(10)         NOT NULL,
    medewerkertype  VARCHAR(15)         NOT NULL,
    specialisatie   VARCHAR(50)         NOT NULL,
    isactief        BIT                 NOT NULL DEFAULT 1,
    opmerking       VARCHAR(255)        DEFAULT NULL,
    datumaangemaakt DATETIME(6)         NOT NULL DEFAULT NOW(6),
    datumgewijzigd  DATETIME(6)         NOT NULL DEFAULT NOW(6) ON UPDATE NOW(6),
    
    UNIQUE KEY ux_medewerker_nummer (nummer),
    INDEX ix_medewerker_persoonid (persoonid),
    INDEX ix_medewerker_type (medewerkertype),
    CONSTRAINT fk_medewerker_persoon FOREIGN KEY (persoonid) 
        REFERENCES persoon(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- 4. BESCHIKBAARHEID
-- Beschikbaarheid van medewerkers
-- =========================================
CREATE TABLE beschikbaarheid (
    id              INT UNSIGNED        PRIMARY KEY AUTO_INCREMENT,
    medewerkerid    INT UNSIGNED        NOT NULL,
    datumvanaf      DATE                NOT NULL,
    datumtotmet     DATE                NOT NULL,
    tijdvanaf       TIME                NOT NULL,
    tijdtotmet      TIME                NOT NULL,
    status          VARCHAR(10)         NOT NULL,
    isactief        BIT                 NOT NULL DEFAULT 1,
    opmerking       VARCHAR(255)        DEFAULT NULL,
    datumaangemaakt DATETIME(6)         NOT NULL DEFAULT NOW(6),
    datumgewijzigd  DATETIME(6)         NOT NULL DEFAULT NOW(6) ON UPDATE NOW(6),
    
    INDEX ix_beschikbaarheid_medewerkerid (medewerkerid),
    INDEX ix_beschikbaarheid_datum (datumvanaf, datumtotmet),
    CONSTRAINT fk_beschikbaarheid_medewerker FOREIGN KEY (medewerkerid) 
        REFERENCES medewerker(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- 5. CONTACT
-- Contactgegevens van patiënten
-- =========================================
CREATE TABLE contact (
    id              INT UNSIGNED        PRIMARY KEY AUTO_INCREMENT,
    patientid       INT UNSIGNED        NOT NULL,
    straatnaam      VARCHAR(50)         NOT NULL,
    huisnummer      VARCHAR(8)          NOT NULL,
    toevoeging      VARCHAR(4)          DEFAULT NULL,
    postcode        VARCHAR(6)          NOT NULL,
    plaats          VARCHAR(30)         NOT NULL,
    mobiel          VARCHAR(12)         NOT NULL,
    email           VARCHAR(50)         NOT NULL,
    isactief        BIT                 NOT NULL DEFAULT 1,
    opmerking       VARCHAR(255)        DEFAULT NULL,
    datumaangemaakt DATETIME(6)         NOT NULL DEFAULT NOW(6),
    datumgewijzigd  DATETIME(6)         NOT NULL DEFAULT NOW(6) ON UPDATE NOW(6),
    
    INDEX ix_contact_patientid (patientid),
    INDEX ix_contact_email (email),
    CONSTRAINT fk_contact_patient FOREIGN KEY (patientid) 
        REFERENCES patient(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- 6. AFSPRAAK
-- Afspraken tussen patiënten en medewerkers
-- =========================================
CREATE TABLE afspraken (
    id              INT UNSIGNED        PRIMARY KEY AUTO_INCREMENT,
    patientid       INT UNSIGNED        NOT NULL,
    medewerkerid    INT UNSIGNED        DEFAULT NULL,
    datum           DATE                NOT NULL,
    tijd            TIME                NOT NULL,
    status          VARCHAR(15)         NOT NULL DEFAULT 'Bevestigd',
    isactief        BIT                 NOT NULL DEFAULT 1,
    opmerking       VARCHAR(255)        DEFAULT NULL,
    datumaangemaakt DATETIME(6)         NOT NULL DEFAULT NOW(6),
    datumgewijzigd  DATETIME(6)         NOT NULL DEFAULT NOW(6) ON UPDATE NOW(6),
    
    INDEX ix_afspraken_patientid (patientid),
    INDEX ix_afspraken_medewerkerid (medewerkerid),
    INDEX ix_afspraken_datum (datum),
    INDEX ix_afspraken_status (status),
    CONSTRAINT fk_afspraken_patient FOREIGN KEY (patientid) 
        REFERENCES patient(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_afspraken_medewerker FOREIGN KEY (medewerkerid) 
        REFERENCES medewerker(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- 7. BEHANDELING
-- Behandelingen uitgevoerd bij patiënten
-- =========================================
CREATE TABLE behandeling (
    id              INT UNSIGNED        PRIMARY KEY AUTO_INCREMENT,
    patientid       INT UNSIGNED        NOT NULL,
    medewerkerid    INT UNSIGNED        DEFAULT NULL,
    datum           DATE                NOT NULL,
    tijd            TIME                NOT NULL,
    behandelingtype VARCHAR(30)         NOT NULL,
    omschrijving    VARCHAR(15)         NOT NULL,
    kosten          DECIMAL(5,2)        NOT NULL,
    status          VARCHAR(15)         NOT NULL DEFAULT 'Onbehandeld',
    isactief        BIT                 NOT NULL DEFAULT 1,
    opmerking       VARCHAR(255)        DEFAULT NULL,
    datumaangemaakt DATETIME(6)         NOT NULL DEFAULT NOW(6),
    datumgewijzigd  DATETIME(6)         NOT NULL DEFAULT NOW(6) ON UPDATE NOW(6),
    
    INDEX ix_behandeling_patientid (patientid),
    INDEX ix_behandeling_medewerkerid (medewerkerid),
    INDEX ix_behandeling_datum (datum),
    INDEX ix_behandeling_status (status),
    CONSTRAINT fk_behandeling_patient FOREIGN KEY (patientid) 
        REFERENCES patient(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_behandeling_medewerker FOREIGN KEY (medewerkerid) 
        REFERENCES medewerker(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- 8. FACTUUR
-- Facturen voor patiënten
-- =========================================
CREATE TABLE factuur (
    id              INT UNSIGNED        PRIMARY KEY AUTO_INCREMENT,
    patientid       INT UNSIGNED        NOT NULL,
    behandelingid   INT UNSIGNED        DEFAULT NULL,
    nummer          VARCHAR(12)         NOT NULL,
    datum           DATE                NOT NULL,
    status          VARCHAR(30)         NOT NULL DEFAULT 'Niet-Verzonden',
    omschrijving    VARCHAR(15)         NOT NULL,
    isactief        BIT                 NOT NULL DEFAULT 1,
    opmerking       VARCHAR(255)        DEFAULT NULL,
    datumaangemaakt DATETIME(6)         NOT NULL DEFAULT NOW(6),
    datumgewijzigd  DATETIME(6)         NOT NULL DEFAULT NOW(6) ON UPDATE NOW(6),
    
    UNIQUE KEY ux_factuur_nummer (nummer),
    INDEX ix_factuur_patientid (patientid),
    INDEX ix_factuur_behandelingid (behandelingid),
    INDEX ix_factuur_datum (datum),
    INDEX ix_factuur_status (status),
    CONSTRAINT fk_factuur_patient FOREIGN KEY (patientid) 
        REFERENCES patient(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_factuur_behandeling FOREIGN KEY (behandelingid) 
        REFERENCES behandeling(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- 9. COMMUNICATIE
-- Berichten tussen medewerkers en patiënten
-- =========================================
CREATE TABLE communicatie (
    id              INT UNSIGNED        PRIMARY KEY AUTO_INCREMENT,
    patientid       INT UNSIGNED        NOT NULL,
    medewerkerid    INT UNSIGNED        DEFAULT NULL,
    bericht         VARCHAR(500)        NOT NULL,
    verzondendatum  DATE                NOT NULL,
    isactief        BIT                 NOT NULL DEFAULT 1,
    opmerking       VARCHAR(255)        DEFAULT NULL,
    datumaangemaakt DATETIME(6)         NOT NULL DEFAULT NOW(6),
    datumgewijzigd  DATETIME(6)         NOT NULL DEFAULT NOW(6) ON UPDATE NOW(6),
    
    INDEX ix_communicatie_patientid (patientid),
    INDEX ix_communicatie_medewerkerid (medewerkerid),
    INDEX ix_communicatie_datum (verzondendatum),
    CONSTRAINT fk_communicatie_patient FOREIGN KEY (patientid) 
        REFERENCES patient(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_communicatie_medewerker FOREIGN KEY (medewerkerid) 
        REFERENCES medewerker(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- 10. FEEDBACK
-- Feedback van patiënten
-- =========================================
CREATE TABLE feedback (
    id              INT UNSIGNED        PRIMARY KEY AUTO_INCREMENT,
    patientid       INT UNSIGNED        NOT NULL,
    beoordeling     VARCHAR(1)          NOT NULL,
    praktijkemail   VARCHAR(50)         NOT NULL,
    praktijktelefoon VARCHAR(10)        NOT NULL,
    verzondendatum  DATE                NOT NULL,
    isactief        BIT                 NOT NULL DEFAULT 1,
    opmerking       VARCHAR(255)        DEFAULT NULL,
    datumaangemaakt DATETIME(6)         NOT NULL DEFAULT NOW(6),
    datumgewijzigd  DATETIME(6)         NOT NULL DEFAULT NOW(6) ON UPDATE NOW(6),
    
    INDEX ix_feedback_patientid (patientid),
    CONSTRAINT fk_feedback_patient FOREIGN KEY (patientid) 
        REFERENCES patient(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =========================================
-- STAP 4: STORED PROCEDURES
-- =========================================

DELIMITER $$

-- =========================================
-- AFSPRAKEN STORED PROCEDURES
-- =========================================

-- SP_GetAllAfspraken
-- Haalt alle afspraken op met patiënt en medewerker info
CREATE PROCEDURE SP_GetAllAfspraken(
    IN p_limit INT, 
    IN p_offset INT
)
BEGIN
    SELECT 
        a.id,
        a.datum,
        a.tijd,
        a.status,
        a.opmerking,
        a.datumaangemaakt,
        -- Patiënt gegevens
        pt.id AS patientid,
        pt.nummer AS patientnummer,
        CONCAT_WS(' ', prs_patient.voornaam, prs_patient.tussenvoegsel, prs_patient.achternaam) AS patientnaam,
        -- Medewerker gegevens
        mw.id AS medewerkerid,
        mw.nummer AS medewerkernummer,
        CONCAT_WS(' ', prs_medewerker.voornaam, prs_medewerker.tussenvoegsel, prs_medewerker.achternaam) AS medewerkernaam,
        mw.medewerkertype,
        mw.specialisatie
    FROM afspraken AS a
    JOIN patient AS pt ON pt.id = a.patientid
    JOIN persoon AS prs_patient ON prs_patient.id = pt.persoonid
    LEFT JOIN medewerker AS mw ON mw.id = a.medewerkerid
    LEFT JOIN persoon AS prs_medewerker ON prs_medewerker.id = mw.persoonid
    WHERE a.isactief = 1
    ORDER BY a.datum DESC, a.tijd DESC
    LIMIT p_limit OFFSET p_offset;
END$$

-- SP_GetAfspraakById
-- Haalt één specifieke afspraak op
CREATE PROCEDURE SP_GetAfspraakById(
    IN p_id INT
)
BEGIN
    SELECT 
        a.id,
        a.datum,
        a.tijd,
        a.status,
        a.opmerking,
        a.datumaangemaakt,
        a.patientid,
        a.medewerkerid,
        pt.nummer AS patientnummer,
        CONCAT_WS(' ', prs_patient.voornaam, prs_patient.tussenvoegsel, prs_patient.achternaam) AS patientnaam,
        mw.nummer AS medewerkernummer,
        CONCAT_WS(' ', prs_medewerker.voornaam, prs_medewerker.tussenvoegsel, prs_medewerker.achternaam) AS medewerkernaam,
        mw.medewerkertype
    FROM afspraken AS a
    JOIN patient AS pt ON pt.id = a.patientid
    JOIN persoon AS prs_patient ON prs_patient.id = pt.persoonid
    LEFT JOIN medewerker AS mw ON mw.id = a.medewerkerid
    LEFT JOIN persoon AS prs_medewerker ON prs_medewerker.id = mw.persoonid
    WHERE a.id = p_id AND a.isactief = 1;
END$$

-- SP_CreateAfspraak
-- Maakt een nieuwe afspraak aan
CREATE PROCEDURE SP_CreateAfspraak(
    IN p_patientid INT,
    IN p_medewerkerid INT,
    IN p_datum DATE,
    IN p_tijd TIME,
    IN p_status VARCHAR(15),
    IN p_opmerking VARCHAR(255)
)
BEGIN
    DECLARE v_new_id INT;
    
    INSERT INTO afspraken (patientid, medewerkerid, datum, tijd, status, opmerking)
    VALUES (p_patientid, p_medewerkerid, p_datum, p_tijd, IFNULL(p_status, 'Bevestigd'), p_opmerking);
    
    SET v_new_id = LAST_INSERT_ID();
    
    SELECT v_new_id AS id;
END$$

-- SP_UpdateAfspraak
-- Wijzigt een bestaande afspraak
CREATE PROCEDURE SP_UpdateAfspraak(
    IN p_id INT,
    IN p_patientid INT,
    IN p_medewerkerid INT,
    IN p_datum DATE,
    IN p_tijd TIME,
    IN p_status VARCHAR(15),
    IN p_opmerking VARCHAR(255)
)
BEGIN
    UPDATE afspraken
    SET 
        patientid = p_patientid,
        medewerkerid = p_medewerkerid,
        datum = p_datum,
        tijd = p_tijd,
        status = p_status,
        opmerking = p_opmerking,
        datumgewijzigd = NOW(6)
    WHERE id = p_id AND isactief = 1;
    
    SELECT ROW_COUNT() AS affected_rows;
END$$

-- SP_DeleteAfspraak
-- Soft delete van een afspraak
CREATE PROCEDURE SP_DeleteAfspraak(
    IN p_id INT
)
BEGIN
    UPDATE afspraken
    SET 
        isactief = 0,
        datumgewijzigd = NOW(6)
    WHERE id = p_id;
    
    SELECT ROW_COUNT() AS affected_rows;
END$$

-- SP_GetAfsprakenStatistieken
-- Haalt statistieken op voor dashboard
CREATE PROCEDURE SP_GetAfsprakenStatistieken()
BEGIN
    SELECT 
        COUNT(*) AS totaal_afspraken,
        SUM(CASE WHEN datum = CURDATE() THEN 1 ELSE 0 END) AS afspraken_vandaag,
        SUM(CASE WHEN datum = CURDATE() + INTERVAL 1 DAY THEN 1 ELSE 0 END) AS afspraken_morgen,
        SUM(CASE WHEN datum >= CURDATE() AND datum <= CURDATE() + INTERVAL 7 DAY THEN 1 ELSE 0 END) AS afspraken_week,
        SUM(CASE WHEN status = 'Bevestigd' THEN 1 ELSE 0 END) AS bevestigd,
        SUM(CASE WHEN status = 'Geannuleerd' THEN 1 ELSE 0 END) AS geannuleerd
    FROM afspraken
    WHERE isactief = 1;
END$$

-- =========================================
-- MEDEWERKER STORED PROCEDURES
-- =========================================

-- SP_GetBeschikbareMedewerkers
-- Haalt alle actieve medewerkers op voor dropdown
CREATE PROCEDURE SP_GetBeschikbareMedewerkers()
BEGIN
    SELECT 
        mw.id,
        mw.nummer,
        mw.medewerkertype,
        mw.specialisatie,
        CONCAT_WS(' ', prs.voornaam, prs.tussenvoegsel, prs.achternaam) AS naam
    FROM medewerker AS mw
    JOIN persoon AS prs ON prs.id = mw.persoonid
    WHERE mw.isactief = 1
    ORDER BY prs.achternaam, prs.voornaam;
END$$

-- =========================================
-- PATIENT STORED PROCEDURES
-- =========================================

-- SP_GetAllPatientenDropdown
-- Haalt alle patiënten op voor dropdown selectie
CREATE PROCEDURE SP_GetAllPatientenDropdown()
BEGIN
    SELECT 
        pt.id,
        pt.nummer,
        CONCAT_WS(' ', prs.voornaam, prs.tussenvoegsel, prs.achternaam) AS naam
    FROM patient AS pt
    JOIN persoon AS prs ON prs.id = pt.persoonid
    WHERE pt.isactief = 1
    ORDER BY prs.achternaam, prs.voornaam;
END$$

-- SP_GetAllPatienten
-- Haalt alle patiënten op met volledige gegevens
CREATE PROCEDURE SP_GetAllPatienten(
    IN p_limit INT, 
    IN p_offset INT
)
BEGIN
    SELECT 
        pt.id,
        pt.nummer,
        pt.medischdossier,
        pt.isactief,
        pt.opmerking,
        pt.datumaangemaakt,
        prs.id AS persoonid,
        prs.voornaam,
        prs.tussenvoegsel,
        prs.achternaam,
        prs.geboortedatum,
        CONCAT_WS(' ', prs.voornaam, prs.tussenvoegsel, prs.achternaam) AS naam,
        c.straatnaam,
        c.huisnummer,
        c.toevoeging,
        c.postcode,
        c.plaats,
        c.mobiel,
        c.email
    FROM patient AS pt
    JOIN persoon AS prs ON prs.id = pt.persoonid
    LEFT JOIN contact AS c ON c.patientid = pt.id AND c.isactief = 1
    WHERE pt.isactief = 1
    ORDER BY prs.achternaam, prs.voornaam
    LIMIT p_limit OFFSET p_offset;
END$$

-- =========================================
-- FACTUUR STORED PROCEDURES
-- =========================================

-- SP_GetAllFacturen
-- Haalt alle facturen op met patiënt en behandeling info
CREATE PROCEDURE SP_GetAllFacturen(
    IN p_limit INT, 
    IN p_offset INT
)
BEGIN
    SELECT 
        f.id,
        f.nummer,
        f.datum,
        f.status,
        f.omschrijving,
        f.opmerking,
        f.datumaangemaakt,
        pt.id AS patientid,
        pt.nummer AS patientnummer,
        CONCAT_WS(' ', prs.voornaam, prs.tussenvoegsel, prs.achternaam) AS patientnaam,
        b.id AS behandelingid,
        b.behandelingtype,
        b.kosten
    FROM factuur AS f
    JOIN patient AS pt ON pt.id = f.patientid
    JOIN persoon AS prs ON prs.id = pt.persoonid
    LEFT JOIN behandeling AS b ON b.id = f.behandelingid
    WHERE f.isactief = 1
    ORDER BY f.datum DESC
    LIMIT p_limit OFFSET p_offset;
END$$

-- SP_GetAllTotaalbedragFacturen
-- Haalt totaalbedragen op per status
CREATE PROCEDURE SP_GetAllTotaalbedragFacturen()
BEGIN
    SELECT 
        f.status,
        COUNT(*) AS aantal,
        COALESCE(SUM(b.kosten), 0) AS totaalbedrag
    FROM factuur AS f
    LEFT JOIN behandeling AS b ON b.id = f.behandelingid
    WHERE f.isactief = 1
    GROUP BY f.status;
END$$

-- =========================================
-- USER STORED PROCEDURES
-- =========================================

-- SP_GetAllUsers
-- Haalt alle gebruikers op
CREATE PROCEDURE SP_GetAllUsers(
    IN p_limit INT, 
    IN p_offset INT
)
BEGIN
    SELECT 
        u.id,
        u.name,
        u.email,
        u.created_at,
        u.updated_at,
        GROUP_CONCAT(r.name SEPARATOR ', ') AS roles
    FROM users AS u
    LEFT JOIN role_user AS ru ON ru.user_id = u.id
    LEFT JOIN roles AS r ON r.id = ru.role_id
    GROUP BY u.id, u.name, u.email, u.created_at, u.updated_at
    ORDER BY u.name
    LIMIT p_limit OFFSET p_offset;
END$$

DELIMITER ;

-- =========================================
-- STAP 5: SEED DATA (Optioneel)
-- =========================================

-- Voeg test medewerkers toe
INSERT INTO persoon (voornaam, tussenvoegsel, achternaam, geboortedatum) VALUES
('Jan', 'van', 'Bergen', '1980-05-15'),
('Lisa', NULL, 'Jansen', '1985-08-22'),
('Mohammed', NULL, 'El Amrani', '1990-03-10'),
('Sophie', 'de', 'Vries', '1988-11-30');

INSERT INTO medewerker (persoonid, nummer, medewerkertype, specialisatie) VALUES
(1, 'MW001', 'Tandarts', 'Algemene tandheelkunde'),
(2, 'MW002', 'Mondhygiënist', 'Preventieve zorg'),
(3, 'MW003', 'Assistent', 'Algemeen'),
(4, 'MW004', 'Praktijkmanagement', 'Administratie');

-- Voeg test patiënten toe
INSERT INTO persoon (voornaam, tussenvoegsel, achternaam, geboortedatum) VALUES
('Pieter', NULL, 'Bakker', '1975-02-20'),
('Anna', 'van der', 'Berg', '1992-07-15'),
('Omar', NULL, 'Hassan', '1988-12-05'),
('Emma', NULL, 'Smit', '1995-04-18'),
('Lucas', 'de', 'Jong', '1982-09-25');

INSERT INTO patient (persoonid, nummer) VALUES
(5, 'P00001'),
(6, 'P00002'),
(7, 'P00003'),
(8, 'P00004'),
(9, 'P00005');

-- Voeg test contactgegevens toe
INSERT INTO contact (patientid, straatnaam, huisnummer, postcode, plaats, mobiel, email) VALUES
(1, 'Hoofdstraat', '123', '3511AB', 'Utrecht', '0612345678', 'pieter.bakker@email.nl'),
(2, 'Kerkstraat', '45', '3512BC', 'Utrecht', '0623456789', 'anna.vdberg@email.nl'),
(3, 'Plein', '7', '3513CD', 'Utrecht', '0634567890', 'omar.hassan@email.nl'),
(4, 'Laan', '89', '3514DE', 'Utrecht', '0645678901', 'emma.smit@email.nl'),
(5, 'Singel', '12', '3515EF', 'Utrecht', '0656789012', 'lucas.dejong@email.nl');

-- Voeg test afspraken toe
INSERT INTO afspraken (patientid, medewerkerid, datum, tijd, status, opmerking) VALUES
(1, 1, CURDATE() + INTERVAL 1 DAY, '09:00:00', 'Bevestigd', 'Controle afspraak'),
(2, 1, CURDATE() + INTERVAL 2 DAY, '10:30:00', 'Bevestigd', 'Vulling plaatsen'),
(3, 2, CURDATE() + INTERVAL 3 DAY, '11:00:00', 'Bevestigd', 'Gebitsreiniging'),
(4, 1, CURDATE() + INTERVAL 5 DAY, '14:00:00', 'Bevestigd', 'Eerste consult'),
(5, 2, CURDATE(), '15:30:00', 'Bevestigd', 'Controle'),
(1, 1, CURDATE() - INTERVAL 7 DAY, '09:00:00', 'Bevestigd', 'Vorige afspraak'),
(2, 2, CURDATE() - INTERVAL 14 DAY, '10:00:00', 'Geannuleerd', 'Geannuleerd door patiënt');

-- =========================================
-- EINDE MIGRATIE
-- =========================================
SELECT 'SmilePro database migratie voltooid!' AS status;
