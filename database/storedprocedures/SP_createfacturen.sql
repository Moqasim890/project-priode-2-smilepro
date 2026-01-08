USE tandarts;
DROP PROCEDURE IF EXISTS SP_CreateFactuur;
DELIMITER //

CREATE PROCEDURE SP_CreateFactuur (
    IN p_patientid INT,
    IN p_nummer VARCHAR(50),
    IN p_datum DATE,
    IN p_bedrag DECIMAL(10,2),
    IN p_status VARCHAR(50),
    IN p_behandeling_ids TEXT
)
proc: BEGIN
    DECLARE v_factuur_id INT;
    DECLARE v_affected INT DEFAULT 0;

    -- Rollback bij SQL-fout
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SELECT -1 AS affected;
    END;

    START TRANSACTION;

    -- 1. Factuur
    INSERT INTO factuur (
        patientid,
        nummer,
        datum,
        bedrag,
        status,
        isactief,
        datumaangemaakt,
        datumgewijzigd
    )
    VALUES (
        p_patientid,
        p_nummer,
        p_datum,
        p_bedrag,
        p_status,
        1,
        NOW(),
        NOW()
    );

    SET v_factuur_id = LAST_INSERT_ID();
    SET v_affected = ROW_COUNT(); -- = 1

    -- 2. Koppelen behandelingen
    INSERT INTO factuur_behandeling (
        factuurid,
        behandelingid,
        isactief,
        datumaangemaakt,
        datumgewijzigd
    )
    SELECT
        v_factuur_id,
        b.id,
        1,
        NOW(),
        NOW()
    FROM behandeling b
    WHERE FIND_IN_SET(b.id, p_behandeling_ids);

    SET v_affected = v_affected + ROW_COUNT();

    -- Minimaal 1 behandeling vereist
    IF v_affected <= 1 THEN
        ROLLBACK;
        SELECT -1 AS affected;
        LEAVE proc;
    END IF;

    COMMIT;

    SELECT v_affected AS affected;
END//

DELIMITER ;
