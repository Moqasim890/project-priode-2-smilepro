USE tandarts;
DROP PROCEDURE IF EXISTS SP_GetAllUsers;
DELIMITER $$

CREATE PROCEDURE SP_GetAllFacturen(
    IN limitVal INT, 
    IN offsetVal INT
)
BEGIN

    SELECT fct.id, 
           fct.patient_id, 
           fct.treatment_id, 
           fct.amount, 
           DATE_FORMAT(fct.created_at, '%d-%m-%Y %H:%i') AS created_at
    FROM facturen AS fct
    LIMIT limitVal OFFSET offsetVal;
END $$
DELIMITER ;