DELIMITER $$

DROP PROCEDURE IF EXISTS SP_GetAllPatienten $$
CREATE PROCEDURE SP_GetAllPatienten ()
BEGIN
    SELECT
        p.persoonid,
        u.id AS user_id,
        u.name AS username,
        u.email,
        p.nummer,
        p.medischdossier
    FROM patient AS p
    JOIN users AS u ON p.persoonid = u.id
    JOIN role_user AS ru ON ru.user_id = u.id
    JOIN roles AS r ON r.id = ru.role_id
    WHERE r.name = 'klant';
END $$

DELIMITER ;
