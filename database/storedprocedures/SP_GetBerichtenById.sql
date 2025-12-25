DELIMITER $$

DROP PROCEDURE IF EXISTS SP_GetBerichtenById $$

CREATE PROCEDURE SP_GetBerichtenById(
    IN comm.patientid INT
)
BEGIN
    SELECT
        comm.patientid,
        comm.medewerkerid,
        CONCAT_WS(' ', prsn.voornaam, prsn.tussenvoegsel, prsn.achternaam) AS volledigeNaam,
        comm.bericht,
        comm.Verzonden_datum
    FROM communicatie AS comm
    WHERE comm.patientid = comm.patientid
    JOIN patient      AS ptnt ON comm.patientid = ptnt.id
    JOIN persoon      AS prsn ON ptnt.persoonid = prsn.id;
END $$

DELIMITER ;