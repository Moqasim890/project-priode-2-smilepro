DELIMITER $$

DROP PROCEDURE IF EXISTS SP_GetAllPatienten $$

MENEER IK BEN NOG BEZIG :(

CREATE PROCEDURE SP_GetAllPatienten ()
BEGIN
    SELECT
         p.patientid
        ,p.nummer
        ,p.medischdossier
    JOIN users AS
    FROM patient AS p
END $$
