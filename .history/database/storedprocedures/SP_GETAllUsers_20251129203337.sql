USE tandarts;

DELIMITER $$

CREATE PROCEDURE SP_GETAllUsers()
BEGIN
    SELECT id.USER_RESOURCES, name, email, created_at 
    FROM users;
END $$