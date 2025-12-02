USE tandarts;

DELIMITER $$

CREATE PROCEDURE SP_GETAllUsers()
BEGIN
    SELECT id.users, name.usr, email, created_at 
    FROM users;
END $$