DELIMITER $$

DROP PROCEDURE IF EXISTS SP_GetAllPatienten $$
CREATE PROCEDURE SP_GetAllPatienten ()
BEGIN
    SELECT
        pt.persoonid,
        us.id AS user_id,
        us.name AS username,
        CONCAT_WS(' ', prs.voornaam, prs.tussenvoegsel, prs.achternaam) AS volledigeNaam,
        us.email,
        pt.nummer,
        pt.opmerking
    FROM patient AS pt
    JOIN persoon AS prs ON prs.id = pt.persoonid
    JOIN users AS us ON prs.gebruikerid = us.id
    JOIN role_user AS ru ON ru.user_id = us.id
    JOIN roles AS r ON r.id = ru.role_id
    WHERE r.name = 'patient';
END $$

DELIMITER ;