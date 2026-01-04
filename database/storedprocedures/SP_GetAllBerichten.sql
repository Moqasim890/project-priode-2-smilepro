DELIMITER $$

DROP PROCEDURE IF EXISTS SP_GetAllBerichten $$

CREATE PROCEDURE SP_GetAllBerichten()
BEGIN
    SELECT
        comm.patientid,
        CONCAT_WS(' ', prsn_p.voornaam, prsn_p.tussenvoegsel, prsn_p.achternaam) AS patientNaam,

        comm.medewerkerid,
        CONCAT_WS(' ', prsn_m.voornaam, prsn_m.tussenvoegsel, prsn_m.achternaam) AS medewerkerNaam,

        comm.bericht,
        comm.Verzonden_datum
    FROM communicatie AS comm

    JOIN patient  AS ptnt   ON comm.patientid = ptnt.id
    JOIN persoon  AS prsn_p ON ptnt.persoonid = prsn_p.id

    LEFT JOIN medewerker AS med   ON comm.medewerkerid = med.id
    LEFT JOIN persoon   AS prsn_m ON med.persoonid = prsn_m.id
    ORDER BY comm.Verzonden_datum DESC;
END $$

DELIMITER ;