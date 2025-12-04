DELIMITER $$

DROP PROCEDURE IF EXISTS SP_GetAllBerichten $$
CREATE PROCEDURE SP_GetAllBerichten ()
BEGIN
    SELECT
        f.id AS user_id,
        f.beoordeling,
        f.praktijkemail,
        f.praktijktelefoon,
        f.opmerking
    FROM feedback AS f;
END $$

DELIMITER ;