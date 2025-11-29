USE tandarts;

DELIMITER $$

CREATE PROCEDURE SP_GETAllUsers()
BEGIN
    SELECT usr.id, usr.name, usr.email, usr.created_at, GROUP_CONCAT(role.name SEPARATOR ', ') AS roles 
    FROM user_role
    JOIN users AS usr ON user_role.user_id = usr.id
    JOIN roles AS role ON user_role.role_id = role.id
    ORDER BY usr.name;
END $$