
DROP PROCEDURE IF EXISTS SP_GetAllAfspraken;

DELIMITER $$

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
        pt.id AS patientid,
        pt.nummer AS patientnummer,
        CONCAT_WS(' ', prs_patient.voornaam, prs_patient.tussenvoegsel, prs_patient.achternaam) AS patientnaam,
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
    ORDER BY a.datumaangemaakt DESC
    LIMIT limitVal OFFSET offsetVal;
END$$

DELIMITER ;
