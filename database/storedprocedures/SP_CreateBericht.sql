DROP PROCEDURE IF EXISTS SP_CreateBericht;
DELIMITER $$

CREATE PROCEDURE SP_CreateBericht(
     IN c_patientid    INT
    ,IN c_medewerkerid INT
    ,IN c_bericht      TEXT
)
BEGIN
    INSERT INTO communicatie
    (
         patientid
        ,medewerkerid
        ,bericht
    ) VALUES (
         c_patientid
        ,c_medewerkerid
        ,c_bericht
    );
END $$