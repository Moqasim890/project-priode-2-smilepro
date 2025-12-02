USE tandarts;

DELIMITER $$

CREATE PROCEDURE SP_GETAllUsers()
BEGIN
    SELECT usr.id, name.usr, email.usr, created_at.usr 
    FROM user_role
    JOIN user 
    ORDER BY name.usr;
END $$