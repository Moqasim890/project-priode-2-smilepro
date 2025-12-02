USE tandarts;
DROP PROCEDURE IF EXISTS SP_GetAllUsers;
DELIMITER $$

CREATE PROCEDURE SP_GetAllUsers(
    IN limitVal INT, 
    IN offsetVal INT
)
BEGIN
    SELECT usr.id, 
           usr.name, 
           usr.email, usr.created_at, GROUP_CONCAT(role.name SEPARATOR ', ') AS roles 
    FROM role_user
    JOIN users AS usr ON role_user.user_id = usr.id
    JOIN roles AS role ON role_user.role_id = role.id
    GROUP BY usr.id, 
             usr.name, 
             usr.email, 
             usr.created_at
    LIMIT limitVal OFFSET offsetVal;
END $$

DELIMITER ;

CALL SP_GetAllUsers(30, 0);

