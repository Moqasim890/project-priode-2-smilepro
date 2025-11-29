USE tandarts;

DELIMITER $$

CREATE PROCEDURE SP_GETAllUsers()
BEGIN
    SELECT usr.id, usr.name, usr.email, usr.created_at 
    FROM user_role
    JOIN user 
    ORDER BY name.usr;
END $$