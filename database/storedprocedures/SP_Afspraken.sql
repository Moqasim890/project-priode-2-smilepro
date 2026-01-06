USE tandarts;

-- =========================================
-- STORED PROCEDURES VOOR AFSPRAKEN
-- =========================================

-- Verwijder bestaande procedures
DROP PROCEDURE IF EXISTS SP_GetAllAfspraken;
DROP PROCEDURE IF EXISTS SP_GetAfspraakById;
DROP PROCEDURE IF EXISTS SP_CreateAfspraak;
DROP PROCEDURE IF EXISTS SP_GetAfsprakenStatistieken;
DROP PROCEDURE IF EXISTS SP_GetBeschikbareMedewerkers;

DELIMITER $$

-- =========================================
-- SP_GetAllAfspraken
-- Haalt alle afspraken op met patiënt en medewerker info
-- =========================================
CREATE PROCEDURE SP_GetAllAfspraken(
    IN limitVal INT, 
    IN offsetVal INT
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
    -- Join patiënt en persoon
    JOIN patient AS pt ON pt.id = a.patientid
    JOIN persoon AS prs_patient ON prs_patient.id = pt.persoonid
    -- Join medewerker en persoon (LEFT JOIN omdat medewerker nullable kan zijn)
    LEFT JOIN medewerker AS mw ON mw.id = a.medewerkerid
    LEFT JOIN persoon AS prs_medewerker ON prs_medewerker.id = mw.persoonid
    WHERE a.isactief = 1
    ORDER BY a.datumaangemaakt DESC
    LIMIT limitVal OFFSET offsetVal;
END$$

-- =========================================
-- SP_GetAfspraakById
-- Haalt één specifieke afspraak op
-- =========================================
CREATE PROCEDURE SP_GetAfspraakById(
    IN afspraakId INT
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
        -- Patiënt gegevens
        pt.nummer AS patientnummer,
        CONCAT_WS(' ', prs_patient.voornaam, prs_patient.tussenvoegsel, prs_patient.achternaam) AS patientnaam,
        -- Medewerker gegevens
        mw.nummer AS medewerkernummer,
        CONCAT_WS(' ', prs_medewerker.voornaam, prs_medewerker.tussenvoegsel, prs_medewerker.achternaam) AS medewerkernaam,
        mw.medewerkertype
    FROM afspraken AS a
    JOIN patient AS pt ON pt.id = a.patientid
    JOIN persoon AS prs_patient ON prs_patient.id = pt.persoonid
    LEFT JOIN medewerker AS mw ON mw.id = a.medewerkerid
    LEFT JOIN persoon AS prs_medewerker ON prs_medewerker.id = mw.persoonid
    WHERE a.id = afspraakId AND a.isactief = 1;
END$$

-- =========================================
-- SP_CreateAfspraak
-- Maakt een nieuwe afspraak aan
-- =========================================
-- Parameters:
--   p_patientid    : ID van de patiënt (foreign key naar patient tabel)
--   p_medewerkerid : ID van de medewerker/tandarts (foreign key naar medewerker tabel)
--   p_datum        : Datum van de afspraak
--   p_tijd         : Tijd van de afspraak
--   p_status       : Status van de afspraak (bijv. 'Gepland', 'Bevestigd', 'Geannuleerd')
--   p_opmerking    : Optionele opmerking bij de afspraak
-- =========================================
-- Relaties in de database:
--   afspraken.patientid    -> patient.id    (verwijst naar patiënt)
--   afspraken.medewerkerid -> medewerker.id (verwijst naar behandelaar)
--   patient.persoonid      -> persoon.id    (patiënt persoonsgegevens)
--   medewerker.persoonid   -> persoon.id    (medewerker persoonsgegevens)
-- =========================================
-- WAAROM GEEN JOINS?
-- Deze procedure gebruikt geen JOINs omdat het een INSERT operatie is.
-- Bij een INSERT hoeven we alleen de ID's (patientid, medewerkerid) op te slaan.
-- We hoeven geen gegevens uit andere tabellen op te halen.
-- De foreign key constraints in de database zorgen ervoor dat de ID's geldig zijn.
-- JOINs zijn alleen nodig bij SELECT queries wanneer we gegevens uit 
-- meerdere tabellen willen combineren (zoals namen van patiënten/medewerkers).
-- =========================================
CREATE PROCEDURE SP_CreateAfspraak(
    IN p_patientid INT,
    IN p_medewerkerid INT,
    IN p_datum DATE,
    IN p_tijd TIME,
    IN p_status VARCHAR(15),
    IN p_opmerking TEXT
)
BEGIN
    -- Voeg nieuwe afspraak toe in de afspraken tabel
    -- De patientid en medewerkerid zijn foreign keys die verwijzen naar 
    -- respectievelijk de patient en medewerker tabellen
    INSERT INTO afspraken (patientid, medewerkerid, datum, tijd, status, opmerking)
    VALUES (p_patientid, p_medewerkerid, p_datum, p_tijd, p_status, p_opmerking);
    
    -- Return de aangemaakte afspraak ID (auto-increment waarde)
    SELECT LAST_INSERT_ID() AS id;
END$$

-- =========================================
-- SP_GetAfsprakenStatistieken
-- Haalt statistieken op voor dashboard
-- =========================================
CREATE PROCEDURE SP_GetAfsprakenStatistieken()
BEGIN
    -- Aantal afspraken vandaag
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
-- SP_GetBeschikbareMedewerkers
-- Haalt alle actieve medewerkers op voor dropdown
-- =========================================
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
-- SP_GetAllPatienten (voor dropdown)
-- =========================================
DROP PROCEDURE IF EXISTS SP_GetAllPatientenDropdown$$

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

DELIMITER ;

