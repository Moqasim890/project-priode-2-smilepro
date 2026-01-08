-- =========================================
-- SMILEPRO STORED PROCEDURES
-- Versie: 1.0
-- Datum: 2026-01-04
-- =========================================
-- Dit bestand bevat alle stored procedures
-- voor de SmilePro tandartspraktijk applicatie
-- =========================================

USE tandarts;

-- =========================================
-- VERWIJDER BESTAANDE STORED PROCEDURES
-- =========================================
DROP PROCEDURE IF EXISTS SP_GetAllAfspraken;
DROP PROCEDURE IF EXISTS SP_GetAfspraakById;
DROP PROCEDURE IF EXISTS SP_CreateAfspraak;
DROP PROCEDURE IF EXISTS SP_UpdateAfspraak;
DROP PROCEDURE IF EXISTS SP_DeleteAfspraak;
DROP PROCEDURE IF EXISTS SP_GetAfsprakenStatistieken;
DROP PROCEDURE IF EXISTS SP_GetAfsprakenVoorDatum;
DROP PROCEDURE IF EXISTS SP_GetBeschikbareMedewerkers;
DROP PROCEDURE IF EXISTS SP_GetMedewerkerById;
DROP PROCEDURE IF EXISTS SP_GetAllPatientenDropdown;
DROP PROCEDURE IF EXISTS SP_GetAllPatienten;
DROP PROCEDURE IF EXISTS SP_GetPatientById;
DROP PROCEDURE IF EXISTS SP_GetAllFacturen;
DROP PROCEDURE IF EXISTS SP_GetAllTotaalbedragFacturen;
DROP PROCEDURE IF EXISTS SP_GetFactuurById;
DROP PROCEDURE IF EXISTS SP_GetAllUsers;

DELIMITER $$

-- =========================================
-- =========================================
-- AFSPRAKEN STORED PROCEDURES
-- =========================================
-- =========================================

-- -----------------------------------------
-- SP_GetAllAfspraken
-- Haalt alle afspraken op met patiënt en medewerker info
-- Parameters:
--   p_limit: Maximum aantal resultaten
--   p_offset: Startpositie voor paginering
-- -----------------------------------------
CREATE PROCEDURE SP_GetAllAfspraken(
    IN p_limit INT, 
    IN p_offset INT
)
BEGIN
    -- Valideer input parameters
    SET p_limit = IFNULL(p_limit, 100);
    SET p_offset = IFNULL(p_offset, 0);
    
    -- Beperk limit tot maximum 1000
    IF p_limit > 1000 THEN
        SET p_limit = 1000;
    END IF;
    
    SELECT 
        a.id,
        a.datum,
        a.tijd,
        a.status,
        a.opmerking,
        a.datumaangemaakt,
        a.datumgewijzigd,
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
    INNER JOIN patient AS pt ON pt.id = a.patientid
    INNER JOIN persoon AS prs_patient ON prs_patient.id = pt.persoonid
    LEFT JOIN medewerker AS mw ON mw.id = a.medewerkerid
    LEFT JOIN persoon AS prs_medewerker ON prs_medewerker.id = mw.persoonid
    WHERE a.isactief = 1
    ORDER BY a.datum DESC, a.tijd DESC
    LIMIT p_limit OFFSET p_offset;
END$$

-- -----------------------------------------
-- SP_GetAfspraakById
-- Haalt één specifieke afspraak op via ID
-- Parameters:
--   p_id: Afspraak ID
-- -----------------------------------------
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
        a.datumgewijzigd,
        a.patientid,
        a.medewerkerid,
        -- Patiënt gegevens
        pt.nummer AS patientnummer,
        CONCAT_WS(' ', prs_patient.voornaam, prs_patient.tussenvoegsel, prs_patient.achternaam) AS patientnaam,
        prs_patient.geboortedatum AS patient_geboortedatum,
        -- Medewerker gegevens
        mw.nummer AS medewerkernummer,
        CONCAT_WS(' ', prs_medewerker.voornaam, prs_medewerker.tussenvoegsel, prs_medewerker.achternaam) AS medewerkernaam,
        mw.medewerkertype,
        mw.specialisatie
    FROM afspraken AS a
    INNER JOIN patient AS pt ON pt.id = a.patientid
    INNER JOIN persoon AS prs_patient ON prs_patient.id = pt.persoonid
    LEFT JOIN medewerker AS mw ON mw.id = a.medewerkerid
    LEFT JOIN persoon AS prs_medewerker ON prs_medewerker.id = mw.persoonid
    WHERE a.id = p_id AND a.isactief = 1;
END$$

-- -----------------------------------------
-- SP_CreateAfspraak
-- Maakt een nieuwe afspraak aan
-- Parameters:
--   p_patientid: ID van de patiënt
--   p_medewerkerid: ID van de medewerker (optioneel)
--   p_datum: Datum van de afspraak
--   p_tijd: Tijd van de afspraak
--   p_status: Status (Bevestigd/Geannuleerd)
--   p_opmerking: Opmerking (optioneel)
-- Returns: ID van de nieuwe afspraak
-- -----------------------------------------
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
    DECLARE v_status VARCHAR(15);
    
    -- Stel default status in als niet opgegeven
    SET v_status = IFNULL(NULLIF(p_status, ''), 'Bevestigd');
    
    -- Insert de nieuwe afspraak
    INSERT INTO afspraken (
        patientid, 
        medewerkerid, 
        datum, 
        tijd, 
        status, 
        opmerking,
        isactief,
        datumaangemaakt,
        datumgewijzigd
    )
    VALUES (
        p_patientid, 
        p_medewerkerid, 
        p_datum, 
        p_tijd, 
        v_status, 
        p_opmerking,
        1,
        NOW(6),
        NOW(6)
    );
    
    -- Haal het nieuwe ID op
    SET v_new_id = LAST_INSERT_ID();
    
    -- Return het nieuwe ID
    SELECT v_new_id AS id;
END$$

-- -----------------------------------------
-- SP_UpdateAfspraak
-- Wijzigt een bestaande afspraak
-- Parameters: Alle afspraak velden
-- Returns: Aantal gewijzigde rijen
-- -----------------------------------------
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
        patientid = IFNULL(p_patientid, patientid),
        medewerkerid = p_medewerkerid,
        datum = IFNULL(p_datum, datum),
        tijd = IFNULL(p_tijd, tijd),
        status = IFNULL(p_status, status),
        opmerking = p_opmerking,
        datumgewijzigd = NOW(6)
    WHERE id = p_id AND isactief = 1;
    
    SELECT ROW_COUNT() AS affected_rows;
END$$

-- -----------------------------------------
-- SP_DeleteAfspraak
-- Soft delete van een afspraak (zet isactief op 0)
-- Parameters:
--   p_id: ID van de afspraak
-- Returns: Aantal gewijzigde rijen
-- -----------------------------------------
CREATE PROCEDURE SP_DeleteAfspraak(
    IN p_id INT
)
BEGIN
    UPDATE afspraken
    SET 
        isactief = 0,
        datumgewijzigd = NOW(6)
    WHERE id = p_id AND isactief = 1;
    
    SELECT ROW_COUNT() AS affected_rows;
END$$

-- -----------------------------------------
-- SP_GetAfsprakenStatistieken
-- Haalt statistieken op voor het dashboard
-- Returns: Diverse tellingen en aggregaties
-- -----------------------------------------
CREATE PROCEDURE SP_GetAfsprakenStatistieken()
BEGIN
    SELECT 
        -- Totaal aantal actieve afspraken
        COUNT(*) AS totaal_afspraken,
        
        -- Afspraken voor vandaag
        SUM(CASE WHEN datum = CURDATE() THEN 1 ELSE 0 END) AS afspraken_vandaag,
        
        -- Afspraken voor morgen
        SUM(CASE WHEN datum = CURDATE() + INTERVAL 1 DAY THEN 1 ELSE 0 END) AS afspraken_morgen,
        
        -- Afspraken voor deze week (komende 7 dagen)
        SUM(CASE 
            WHEN datum >= CURDATE() AND datum <= CURDATE() + INTERVAL 7 DAY 
            THEN 1 ELSE 0 
        END) AS afspraken_week,
        
        -- Aantal bevestigde afspraken
        SUM(CASE WHEN status = 'Bevestigd' THEN 1 ELSE 0 END) AS bevestigd,
        
        -- Aantal geannuleerde afspraken
        SUM(CASE WHEN status = 'Geannuleerd' THEN 1 ELSE 0 END) AS geannuleerd,
        
        -- Toekomstige afspraken
        SUM(CASE WHEN datum > CURDATE() THEN 1 ELSE 0 END) AS toekomstig,
        
        -- Afspraken in het verleden
        SUM(CASE WHEN datum < CURDATE() THEN 1 ELSE 0 END) AS verleden
        
    FROM afspraken
    WHERE isactief = 1;
END$$

-- -----------------------------------------
-- SP_GetAfsprakenVoorDatum
-- Haalt afspraken op voor een specifieke datum
-- Parameters:
--   p_datum: Datum om afspraken voor op te halen
-- -----------------------------------------
CREATE PROCEDURE SP_GetAfsprakenVoorDatum(
    IN p_datum DATE
)
BEGIN
    SELECT 
        a.id,
        a.datum,
        a.tijd,
        a.status,
        a.opmerking,
        pt.nummer AS patientnummer,
        CONCAT_WS(' ', prs_patient.voornaam, prs_patient.tussenvoegsel, prs_patient.achternaam) AS patientnaam,
        mw.nummer AS medewerkernummer,
        CONCAT_WS(' ', prs_medewerker.voornaam, prs_medewerker.tussenvoegsel, prs_medewerker.achternaam) AS medewerkernaam,
        mw.medewerkertype
    FROM afspraken AS a
    INNER JOIN patient AS pt ON pt.id = a.patientid
    INNER JOIN persoon AS prs_patient ON prs_patient.id = pt.persoonid
    LEFT JOIN medewerker AS mw ON mw.id = a.medewerkerid
    LEFT JOIN persoon AS prs_medewerker ON prs_medewerker.id = mw.persoonid
    WHERE a.datum = p_datum AND a.isactief = 1
    ORDER BY a.tijd ASC;
END$$


-- =========================================
-- =========================================
-- MEDEWERKER STORED PROCEDURES
-- =========================================
-- =========================================

-- -----------------------------------------
-- SP_GetBeschikbareMedewerkers
-- Haalt alle actieve medewerkers op voor dropdown
-- Returns: Lijst met medewerker gegevens
-- -----------------------------------------
CREATE PROCEDURE SP_GetBeschikbareMedewerkers()
BEGIN
    SELECT 
        mw.id,
        mw.nummer,
        mw.medewerkertype,
        mw.specialisatie,
        CONCAT_WS(' ', prs.voornaam, prs.tussenvoegsel, prs.achternaam) AS naam,
        prs.voornaam,
        prs.tussenvoegsel,
        prs.achternaam
    FROM medewerker AS mw
    INNER JOIN persoon AS prs ON prs.id = mw.persoonid
    WHERE mw.isactief = 1 AND prs.isactief = 1
    ORDER BY prs.achternaam, prs.voornaam;
END$$

-- -----------------------------------------
-- SP_GetMedewerkerById
-- Haalt één medewerker op via ID
-- Parameters:
--   p_id: Medewerker ID
-- -----------------------------------------
CREATE PROCEDURE SP_GetMedewerkerById(
    IN p_id INT
)
BEGIN
    SELECT 
        mw.id,
        mw.nummer,
        mw.medewerkertype,
        mw.specialisatie,
        mw.isactief,
        mw.opmerking,
        mw.datumaangemaakt,
        mw.datumgewijzigd,
        prs.id AS persoonid,
        prs.voornaam,
        prs.tussenvoegsel,
        prs.achternaam,
        prs.geboortedatum,
        CONCAT_WS(' ', prs.voornaam, prs.tussenvoegsel, prs.achternaam) AS naam
    FROM medewerker AS mw
    INNER JOIN persoon AS prs ON prs.id = mw.persoonid
    WHERE mw.id = p_id AND mw.isactief = 1;
END$$


-- =========================================
-- =========================================
-- PATIENT STORED PROCEDURES
-- =========================================
-- =========================================

-- -----------------------------------------
-- SP_GetAllPatientenDropdown
-- Haalt alle patiënten op voor dropdown selectie
-- Returns: Beknopte patiënt lijst
-- -----------------------------------------
CREATE PROCEDURE SP_GetAllPatientenDropdown()
BEGIN
    SELECT 
        pt.id,
        pt.nummer,
        CONCAT_WS(' ', prs.voornaam, prs.tussenvoegsel, prs.achternaam) AS naam,
        prs.voornaam,
        prs.tussenvoegsel,
        prs.achternaam
    FROM patient AS pt
    INNER JOIN persoon AS prs ON prs.id = pt.persoonid
    WHERE pt.isactief = 1 AND prs.isactief = 1
    ORDER BY prs.achternaam, prs.voornaam;
END$$

-- -----------------------------------------
-- SP_GetAllPatienten
-- Haalt alle patiënten op met volledige gegevens
-- Parameters:
--   p_limit: Maximum aantal resultaten
--   p_offset: Startpositie voor paginering
-- -----------------------------------------
CREATE PROCEDURE SP_GetAllPatienten(
    IN p_limit INT, 
    IN p_offset INT
)
BEGIN
    -- Valideer input parameters
    SET p_limit = IFNULL(p_limit, 100);
    SET p_offset = IFNULL(p_offset, 0);
    
    SELECT 
        pt.id,
        pt.nummer,
        pt.isactief,
        pt.opmerking,
        pt.datumaangemaakt,
        pt.datumgewijzigd,
        -- Persoon gegevens
        prs.id AS persoonid,
        prs.voornaam,
        prs.tussenvoegsel,
        prs.achternaam,
        prs.geboortedatum,
        CONCAT_WS(' ', prs.voornaam, prs.tussenvoegsel, prs.achternaam) AS naam,
        -- Leeftijd berekenen
        TIMESTAMPDIFF(YEAR, prs.geboortedatum, CURDATE()) AS leeftijd,
        -- Contact gegevens (meest recente)
        c.straatnaam,
        c.huisnummer,
        c.toevoeging,
        c.postcode,
        c.plaats,
        c.mobiel,
        c.email,
        -- Aantal afspraken
        (SELECT COUNT(*) FROM afspraken a WHERE a.patientid = pt.id AND a.isactief = 1) AS aantal_afspraken
    FROM patient AS pt
    INNER JOIN persoon AS prs ON prs.id = pt.persoonid
    LEFT JOIN contact AS c ON c.patientid = pt.id AND c.isactief = 1
    WHERE pt.isactief = 1
    ORDER BY prs.achternaam, prs.voornaam
    LIMIT p_limit OFFSET p_offset;
END$$

-- -----------------------------------------
-- SP_GetPatientById
-- Haalt één patiënt op via ID
-- Parameters:
--   p_id: Patiënt ID
-- -----------------------------------------
CREATE PROCEDURE SP_GetPatientById(
    IN p_id INT
)
BEGIN
    SELECT 
        pt.id,
        pt.nummer,
        pt.medischdossier,
        pt.isactief,
        pt.opmerking,
        pt.datumaangemaakt,
        pt.datumgewijzigd,
        prs.id AS persoonid,
        prs.voornaam,
        prs.tussenvoegsel,
        prs.achternaam,
        prs.geboortedatum,
        CONCAT_WS(' ', prs.voornaam, prs.tussenvoegsel, prs.achternaam) AS naam,
        TIMESTAMPDIFF(YEAR, prs.geboortedatum, CURDATE()) AS leeftijd,
        c.id AS contactid,
        c.straatnaam,
        c.huisnummer,
        c.toevoeging,
        c.postcode,
        c.plaats,
        c.mobiel,
        c.email
    FROM patient AS pt
    INNER JOIN persoon AS prs ON prs.id = pt.persoonid
    LEFT JOIN contact AS c ON c.patientid = pt.id AND c.isactief = 1
    WHERE pt.id = p_id AND pt.isactief = 1;
END$$


-- =========================================
-- =========================================
-- FACTUUR STORED PROCEDURES
-- =========================================
-- =========================================

-- -----------------------------------------
-- SP_GetAllFacturen
-- Haalt alle facturen op met patiënt en behandeling info
-- Parameters:
--   p_limit: Maximum aantal resultaten
--   p_offset: Startpositie voor paginering
-- -----------------------------------------
CREATE PROCEDURE SP_GetAllFacturen(
    IN p_limit INT, 
    IN p_offset INT
)
BEGIN
    -- Valideer input parameters
    SET p_limit = IFNULL(p_limit, 100);
    SET p_offset = IFNULL(p_offset, 0);
    
    SELECT 
        f.id,
        f.nummer,
        f.datum,
        f.status,
        f.omschrijving,
        f.opmerking,
        f.datumaangemaakt,
        f.datumgewijzigd,
        -- Patiënt gegevens
        pt.id AS patientid,
        pt.nummer AS patientnummer,
        CONCAT_WS(' ', prs.voornaam, prs.tussenvoegsel, prs.achternaam) AS naam,
        -- Behandeling gegevens
        b.id AS behandelingid,
        b.behandelingtype,
        b.kosten AS bedrag
    FROM factuur AS f
    INNER JOIN patient AS pt ON pt.id = f.patientid
    INNER JOIN persoon AS prs ON prs.id = pt.persoonid
    LEFT JOIN behandeling AS b ON b.id = f.behandelingid
    WHERE f.isactief = 1
    ORDER BY f.datum DESC
    LIMIT p_limit OFFSET p_offset;
END$$

-- -----------------------------------------
-- SP_GetAllTotaalbedragFacturen
-- Haalt totaalbedragen op per factuur status
-- Returns: Status met aantal en totaalbedrag
-- -----------------------------------------
CREATE PROCEDURE SP_GetAllTotaalbedragFacturen()
BEGIN
    SELECT 
        f.status,
        COUNT(*) AS aantal,
        COALESCE(SUM(b.kosten), 0) AS totaalbedrag
    FROM factuur AS f
    LEFT JOIN behandeling AS b ON b.id = f.behandelingid
    WHERE f.isactief = 1
    GROUP BY f.status
    ORDER BY f.status;
END$$

-- -----------------------------------------
-- SP_GetFactuurById
-- Haalt één factuur op via ID
-- Parameters:
--   p_id: Factuur ID
-- -----------------------------------------
CREATE PROCEDURE SP_GetFactuurById(
    IN p_id INT
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
        f.datumgewijzigd,
        pt.id AS patientid,
        pt.nummer AS patientnummer,
        CONCAT_WS(' ', prs.voornaam, prs.tussenvoegsel, prs.achternaam) AS patientnaam,
        b.id AS behandelingid,
        b.behandelingtype,
        b.kosten AS bedrag,
        b.datum AS behandelingdatum
    FROM factuur AS f
    INNER JOIN patient AS pt ON pt.id = f.patientid
    INNER JOIN persoon AS prs ON prs.id = pt.persoonid
    LEFT JOIN behandeling AS b ON b.id = f.behandelingid
    WHERE f.id = p_id AND f.isactief = 1;
END$$


-- =========================================
-- =========================================
-- USER STORED PROCEDURES
-- =========================================
-- =========================================

-- -----------------------------------------
-- SP_GetAllUsers
-- Haalt alle gebruikers op met hun rollen
-- Parameters:
--   p_limit: Maximum aantal resultaten
--   p_offset: Startpositie voor paginering
-- -----------------------------------------
CREATE PROCEDURE SP_GetAllUsers(
    IN p_limit INT, 
    IN p_offset INT
)
BEGIN
    -- Valideer input parameters
    SET p_limit = IFNULL(p_limit, 100);
    SET p_offset = IFNULL(p_offset, 0);
    
    SELECT 
        u.id,
        u.name,
        u.email,
        u.email_verified_at,
        u.created_at,
        u.updated_at,
        GROUP_CONCAT(r.name ORDER BY r.name SEPARATOR ', ') AS roles
    FROM users AS u
    LEFT JOIN role_user AS ru ON ru.user_id = u.id
    LEFT JOIN roles AS r ON r.id = ru.role_id
    GROUP BY u.id, u.name, u.email, u.email_verified_at, u.created_at, u.updated_at
    ORDER BY u.name
    LIMIT p_limit OFFSET p_offset;
END$$


DELIMITER ;

-- =========================================
-- EINDE STORED PROCEDURES
-- =========================================
SELECT 'Stored procedures succesvol aangemaakt!' AS status;
