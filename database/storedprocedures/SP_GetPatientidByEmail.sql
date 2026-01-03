DROP PROCEDURE IF EXISTS SP_GetPatientidByEmail;
DELIMITER $$

CREATE PROCEDURE SP_GetPatientidByEmail(
    IN p_email VARCHAR(255)
)
BEGIN
    SELECT pa.id
    FROM patient pa
    JOIN persoon pe ON pe.id = pa.persoonid
    JOIN users u ON u.id = pe.gebruikerid
    WHERE u.email COLLATE utf8mb4_unicode_ci = p_email;
END $$

DELIMITER ;
