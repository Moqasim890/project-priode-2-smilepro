-- Active: 1764442526270@@127.0.0.1@3333@smilepro_db

DROP PROCEDURE IF EXISTS SP_GetAllUsers;
DROP PROCEDURE IF EXISTS SP_CountAllUsers;
DELIMITER $$

CREATE PROCEDURE SP_GetAllUsers(
    IN limitVal INT, 
    IN offsetVal INT
)
BEGIN
    SELECT usr.id, 
           usr.name, 
           usr.email, 
           DATE_FORMAT(usr.created_at, '%d-%m-%Y %H:%i') AS created_at, 
           GROUP_CONCAT(role.name SEPARATOR ', ') AS roles 
    FROM role_user
    JOIN users AS usr ON role_user.user_id = usr.id
    JOIN roles AS role ON role_user.role_id = role.id
    GROUP BY usr.id, 
             usr.name, 
             usr.email, 
             usr.created_at
    ORDER BY usr.id
    LIMIT limitVal OFFSET offsetVal;
END$$

CREATE PROCEDURE SP_CountAllUsers()
BEGIN
    SELECT COUNT(*) AS total_users FROM users;
END $$

DELIMITER ;

CALL SP_GetAllUsers(30, 0);

CALL SP_CountAllUsers();