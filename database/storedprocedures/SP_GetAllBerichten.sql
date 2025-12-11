DELIMITER $$

DROP PROCEDURE IF EXISTS SP_GetAllBerichten $$

CREATE PROCEDURE SP_GetAllBerichten ()
BEGIN
    SELECT
        comm.patientid,
        comm.medewerkerid,
        prsn.voornaam,
        prsn.tussenvoegsel,
        prsn.achternaam,
        comm.bericht,
        comm.Verzonden_datum
    FROM communicatie AS comm
    JOIN patient      AS ptnt ON comm.patientid = ptnt.id
    JOIN persoon      AS prsn ON ptnt.persoonid = prsn.id;
END $$

DELIMITER ;
