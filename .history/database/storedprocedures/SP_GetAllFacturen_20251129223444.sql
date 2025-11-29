USE tandarts;
DROP PROCEDURE IF EXISTS SP_GetAllFacturen;
DROP PROCEDURE IF EXISTS SP_GetAllTotaalbedragFacturen;
DELIMITER $$

CREATE PROCEDURE SP_GetAllFacturen(
    IN limitVal INT, 
    IN offsetVal INT
)
BEGIN
    SELECT 
        fct.id,
        fct.nummer,
        fct.datum,
        fct.bedrag,
        fct.status,
        CONCAT_WS(' ', prs.voornaam, prs.tussenvoegsel, prs.achternaam) AS naam,
        GROUP_CONCAT(bhdl.behandelingtype SEPARATOR ', ') AS behandelingen
    FROM factuur AS fct
    JOIN patient AS pt ON pt.id = fct.patientid
    JOIN persoon AS prs ON prs.id = pt.persoonid
    LEFT JOIN factuur_behandeling AS fct_bhdl ON fct_bhdl.factuurid = fct.id AND fct_bhdl.isactief = 1
    LEFT JOIN behandeling AS bhdl ON bhdl.id = fct_bhdl.behandelingid
    WHERE fct.isactief = 1
    GROUP BY fct.id, fct.nummer, fct.datum, fct.bedrag, fct.status, prs.voornaam, prs.tussenvoegsel, prs.achternaam
    ORDER BY fct.datum DESC
    LIMIT limitVal OFFSET offsetVal;
END$$


CREATE PROCEDURE SP_GetAllTotaalbedragFacturen(
    IN STATUS VARCHAR(20)
)
BEGIN
    SELECT 
        SUM(fct.bedrag) AS totaalbedrag
    FROM factuur AS fct
    WHERE fct.isactief = 1
    ;
END $$
DELIMITER ;

CALL `SP_GetAllFacturen`(10, 0);