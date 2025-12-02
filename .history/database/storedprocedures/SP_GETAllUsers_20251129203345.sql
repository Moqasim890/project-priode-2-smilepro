USE tandarts;

DELIMITER $$

CREATE PROCEDURE SP_GETAllUsers()
BEGIN
    SELECT id.usr, name.usr, email, created_at 
    FROM users;
END $$