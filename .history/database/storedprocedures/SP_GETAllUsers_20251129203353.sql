USE tandarts;

DELIMITER $$

CREATE PROCEDURE SP_GETAllUsers()
BEGIN
    SELECT id.usr, name.usr, email.usr, created_at 
    FROM users;
END $$