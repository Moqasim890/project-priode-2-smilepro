DELIMITER $$

DROP PROCEDURE IF EXISTS SP_GetAllBerichten $$

DROP PROCEDURE IF EXISTS SP_GetAllFeedback $$
CREATE PROCEDURE SP_GetAllFeedback ()
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