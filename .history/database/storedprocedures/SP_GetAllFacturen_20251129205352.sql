USE tandarts;
DROP PROCEDURE IF EXISTS SP_GetAllUsers;
DELIMITER $$

CREATE PROCEDURE SP_GetAllUsers(
    IN limitVal INT, 
    IN offsetVal INT
)
BEGIN