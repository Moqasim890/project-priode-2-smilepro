DROP PROCEDURE IF EXISTS SP_CountBerichtenById;
DELIMITER $$

CREATE PROCEDURE SP_CountBerichtenById(
    IN p_patientid INT
)
BEGIN
    SELECT
        COUNT(*) AS AantalBerichten
    FROM communicatie
    WHERE patientid = p_patientid;
END $$

DELIMITER ;
