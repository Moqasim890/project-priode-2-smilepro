-- pakken de behandelingen die geen factuur hebben en met de patient zijn gelinked

-- Active: 1764442526270@@127.0.0.1@3333@tandarts_db
USE tandarts;
DROP PROCEDURE IF EXISTS SP_GetAllBehandelingen;

DELIMITER $$

CREATE PROCEDURE SP_GetAllBehandelingen(
    IN pid INT
)
BEGIN
-- id, behandelingstype, kosten
    SELECT
        bhnd.id
        ,bhnd.behandelingtype
        ,bhnd.kosten
    FROM
        behandeling AS bhnd
    WHERE
        bhnd.patientid = pid
    AND
        id NOT IN (
            SELECT fctb.behandelingid
            FROM factuur_behandeling AS fctb
            WHERE fctb.behandelingid = bhnd.id
        );
END$$

DELIMITER ;

CALL SP_GetAllBehandelingen(6);
