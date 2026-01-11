USE tandarts;

-- Verwijder bestaande procedure
DROP PROCEDURE IF EXISTS SP_CreateAfspraak;

DELIMITER $$

CREATE PROCEDURE SP_CreateAfspraak(
    IN p_patientid INT,
    IN p_medewerkerid INT,
    IN p_datum DATE,
    IN p_tijd TIME,
    IN p_status VARCHAR(15),
    IN p_opmerking TEXT
)
BEGIN
    DECLARE v_afspraak_id INT;
    DECLARE v_patient_naam VARCHAR(255);
    DECLARE v_medewerker_naam VARCHAR(255);

    -- Voeg nieuwe afspraak toe in de afspraken tabel
    -- De patientid en medewerkerid zijn foreign keys die verwijzen naar
    -- respectievelijk de patient en medewerker tabellen
    INSERT INTO afspraken (patientid, medewerkerid, datum, tijd, status, opmerking)
    VALUES (p_patientid, p_medewerkerid, p_datum, p_tijd, p_status, p_opmerking);

    -- Haal de aangemaakte afspraak ID op
    SET v_afspraak_id = LAST_INSERT_ID();

    -- Haal patiënt naam op voor logging
    SELECT CONCAT_WS(' ', prs.voornaam, prs.tussenvoegsel, prs.achternaam)
    INTO v_patient_naam
    FROM patient pt
    JOIN persoon prs ON prs.id = pt.persoonid
    WHERE pt.id = p_patientid;

    -- Haal medewerker naam op voor logging (indien aanwezig)
    IF p_medewerkerid IS NOT NULL THEN
        SELECT CONCAT_WS(' ', prs.voornaam, prs.tussenvoegsel, prs.achternaam)
        INTO v_medewerker_naam
        FROM medewerker mw
        JOIN persoon prs ON prs.id = mw.persoonid
        WHERE mw.id = p_medewerkerid;
    ELSE
        SET v_medewerker_naam = 'Niet toegewezen';
    END IF;

    -- Registreer in log tabel (als log tabel bestaat)
    -- Creëer log tabel indien deze nog niet bestaat
    CREATE TABLE IF NOT EXISTS afspraak_log (
        id INT AUTO_INCREMENT PRIMARY KEY,
        afspraak_id INT NOT NULL,
        actie VARCHAR(50) NOT NULL,
        patient_naam VARCHAR(255),
        medewerker_naam VARCHAR(255),
        datum DATE,
        tijd TIME,
        status VARCHAR(15),
        opmerking TEXT,
        datum_aangemaakt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_afspraak_id (afspraak_id),
        INDEX idx_datum_aangemaakt (datum_aangemaakt)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    -- Voeg log entry toe
    INSERT INTO afspraak_log (
        afspraak_id,
        actie,
        patient_naam,
        medewerker_naam,
        datum,
        tijd,
        status,
        opmerking
    ) VALUES (
        v_afspraak_id,
        'CREATE',
        v_patient_naam,
        v_medewerker_naam,
        p_datum,
        p_tijd,
        p_status,
        p_opmerking
    );

    -- Return de aangemaakte afspraak ID
    SELECT v_afspraak_id AS id;
END$$

DELIMITER ;
