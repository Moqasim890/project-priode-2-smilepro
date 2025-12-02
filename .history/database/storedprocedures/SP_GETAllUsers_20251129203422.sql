USE tandarts;

DELIMITER $$

CREATE PROCEDURE SP_GETAllUsers()
BEGIN
    SELECT id.usr, name.usr, email.usr, created_at.usr 
    FROM users AS usr
    
    ORDER BY name.usr;
END $$