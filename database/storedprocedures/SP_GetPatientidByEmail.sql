DROP PROCEDURE IF EXISTS SP_GetPatientidByEmail;
DELIMITER $$

CREATE PROCEDURE SP_GetPatientidByEmail(
    IN p_email VARCHAR(255)
)
BEGIN
    SELECT p.id
    FROM patient p
    JOIN persoon pe ON pe.id = p.persoonid
    JOIN user u ON u.id = pe.gebruikerid
    WHERE u.email COLLATE utf8mb4_unicode_ci = p_email;
END $$

DELIMITER ;
