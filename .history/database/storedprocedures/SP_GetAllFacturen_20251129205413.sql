USE tandarts;
DROP PROCEDURE IF EXISTS SP_GetAllUsers;
DELIMITER $$

CREATE PROCEDURE SP_GetAllFacturen(
    IN limitVal INT, 
    IN offsetVal INT
)
BEGIN

    