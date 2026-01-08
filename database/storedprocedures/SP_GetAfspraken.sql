USE smilepro;

-- =========================================
-- STORED PROCEDURES VOOR AFSPRAKEN (GET)
-- =========================================

-- Verwijder bestaande procedures
DROP PROCEDURE IF EXISTS SP_GetAllAfspraken;
DROP PROCEDURE IF EXISTS SP_GetAfspraakById;
DROP PROCEDURE IF EXISTS SP_GetAfsprakenStatistieken;
DROP PROCEDURE IF EXISTS SP_GetBeschikbareMedewerkers;
DROP PROCEDURE IF EXISTS SP_GetAllPatientenDropdown;

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
-- SP_GetAllPatientenDropdown (voor dropdown)
-- =========================================
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
