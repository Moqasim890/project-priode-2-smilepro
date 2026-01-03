DROP PROCEDURE IF EXISTS SP_GetPatientIdByUserId;
DELIMITER $$

CREATE PROCEDURE SP_GetPatientIdByUserId(
    IN p_userid INT
)
BEGIN
    SELECT pa.id AS patientid
    FROM patient pa
    JOIN persoon pe ON pe.id = pa.persoonid
    JOIN users u ON u.id = pe.gebruikerid
    WHERE u.id = p_userid
    LIMIT 1;
END $$

DELIMITER ;
