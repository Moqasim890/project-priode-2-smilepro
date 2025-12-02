USE tandarts;

DELIMITER $$

CREATE PROCEDURE SP_GETAllUsers()
BEGIN
    SELECT usr.id, usr.name, usr.email, usr.created_at, GROUP_CONCAT(role.name SEPARATOR ', ') AS roles 
    FROM role_user
    JOIN users AS usr ON role_user.user_id = usr.id
    JOIN roles AS role ON role_user.role_id = role.id
    ORDER BY usr.name;
END $$

DELIMITER ;

CALL SP_GETAllUsers();