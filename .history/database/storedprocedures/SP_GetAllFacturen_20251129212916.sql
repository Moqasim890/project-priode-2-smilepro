USE tandarts;
DROP PROCEDURE IF EXISTS SP_GetAllFacturen;
DELIMITER $$

CREATE PROCEDURE SP_GetAllFacturen(
    IN limitVal INT, 
    IN offsetVal INT
)
BEGIN
    SELECT 
        ftc.nummer
        ,ftc.datum
        ,ftc.bedrag
        ,
    FROM facturen AS fct
    LIMIT limitVal OFFSET offsetVal;
END $$
DELIMITER ;