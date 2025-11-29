-- Active: 1764442526270@@127.0.0.1@3333@tandarts
-- Zorg dat we schone lei hebben (drop in juiste volgorde)
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS feedback;
DROP TABLE IF EXISTS communicatie;
DROP TABLE IF EXISTS factuur;
DROP TABLE IF EXISTS behandeling;
DROP TABLE IF EXISTS afspraken;
DROP TABLE IF EXISTS contact;
DROP TABLE IF EXISTS beschikbaarheid;
DROP TABLE IF EXISTS medewerker;
DROP TABLE IF EXISTS patient;
DROP TABLE IF EXISTS persoon;
SET FOREIGN_KEY_CHECKS = 1;

-- =========================================
-- 1. PERSOON
-- =========================================
CREATE TABLE persoon (
    id INT PRIMARY KEY AUTO_INCREMENT,
    gebruikerid BIGINT UNSIGNED DEFAULT NULL,
    voornaam VARCHAR(100) NOT NULL,
    tussenvoegsel VARCHAR(20),
    achternaam VARCHAR(100) NOT NULL,
    geboortedatum DATE NOT NULL,
    isactief TINYINT(1) DEFAULT 1,
    opmerking TEXT,
    datumaangemaakt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    datumgewijzigd DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX ix_persoon_gebruikerid (gebruikerid),
    CONSTRAINT fk_persoon_users FOREIGN KEY (gebruikerid) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- 2. PATIENT
-- =========================================
CREATE TABLE patient (
    id INT PRIMARY KEY AUTO_INCREMENT,
    persoonid INT NOT NULL,
    nummer VARCHAR(50) NOT NULL,
    medischdossier TEXT,
    isactief TINYINT(1) DEFAULT 1,
    opmerking TEXT,
    datumaangemaakt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    datumgewijzigd DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY ux_patient_nummer (nummer),
    INDEX ix_patient_persoonid (persoonid),
    CONSTRAINT fk_patient_persoon FOREIGN KEY (persoonid) REFERENCES persoon(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- 4. MEDEWERKER
-- =========================================
CREATE TABLE medewerker (
    id INT PRIMARY KEY AUTO_INCREMENT,
    persoonid INT NOT NULL,
    nummer VARCHAR(50) NOT NULL,
    medewerkertype ENUM('Assistent','Mondhygiënist','Tandarts','Praktijkmanagement') NOT NULL,
    specialisatie VARCHAR(255),
    beschikbaarheid TEXT,
    isactief TINYINT(1) DEFAULT 1,
    opmerking TEXT,
    datumaangemaakt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    datumgewijzigd DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY ux_medewerker_nummer (nummer),
    INDEX ix_medewerker_persoonid (persoonid),
    CONSTRAINT fk_medewerker_persoon FOREIGN KEY (persoonid) REFERENCES persoon(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- 5. BESCHIKBAARHEID
-- =========================================
CREATE TABLE beschikbaarheid (
    id INT PRIMARY KEY AUTO_INCREMENT,
    medewerkerid INT NOT NULL,
    datumvanaf DATE NOT NULL,
    datumtotmet DATE NOT NULL,
    tijdvanaf TIME NOT NULL,
    tijdtotmet TIME NOT NULL,
    status ENUM('Aanwezig','Afwezig','Verlof','Ziek') NOT NULL,
    isactief TINYINT(1) DEFAULT 1,
    opmerking TEXT,
    datumaangemaakt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    datumgewijzigd DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX ix_beschikbaarheid_medewerkerid (medewerkerid),
    CONSTRAINT fk_beschikbaarheid_medewerker FOREIGN KEY (medewerkerid) REFERENCES medewerker(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- 6. CONTACT
-- =========================================
CREATE TABLE contact (
    id INT PRIMARY KEY AUTO_INCREMENT,
    patientid INT NOT NULL,
    straatnaam VARCHAR(100) NOT NULL,
    huisnummer VARCHAR(10) NOT NULL,
    toevoeging VARCHAR(10),
    postcode VARCHAR(10) NOT NULL,
    plaats VARCHAR(100) NOT NULL,
    mobiel VARCHAR(20),
    email VARCHAR(255),
    isactief TINYINT(1) DEFAULT 1,
    opmerking TEXT,
    datumaangemaakt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    datumgewijzigd DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX ix_contact_patientid (patientid),
    CONSTRAINT fk_contact_patient FOREIGN KEY (patientid) REFERENCES patient(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- 7. AFSPRAKEN
-- =========================================
CREATE TABLE afspraken (
    id INT PRIMARY KEY AUTO_INCREMENT,
    patientid INT NOT NULL,
    medewerkerid INT NOT NULL,
    datum DATE NOT NULL,
    tijd TIME NOT NULL,
    status ENUM('Bevestigd','Geannuleerd') NOT NULL DEFAULT 'Bevestigd',
    isactief TINYINT(1) DEFAULT 1,
    opmerking TEXT,
    datumaangemaakt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    datumgewijzigd DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX ix_afspraken_patientid (patientid),
    INDEX ix_afspraken_medewerkerid (medewerkerid),
    CONSTRAINT fk_afspraken_patient FOREIGN KEY (patientid) REFERENCES patient(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_afspraken_medewerker FOREIGN KEY (medewerkerid) REFERENCES medewerker(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- 8. BEHANDELING
-- =========================================
CREATE TABLE behandeling (
    id INT PRIMARY KEY AUTO_INCREMENT,
    medewerkerid INT,
    patientid INT NOT NULL,
    datum DATE NOT NULL,
    tijd TIME NOT NULL,
    behandelingtype ENUM('Controles','Vullingen','Gebitsreiniging','Orthodontie','Wortelkanaalbehandelingen') NOT NULL,
    omschrijving TEXT,
    kosten DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    status ENUM('Behandeld','Onbehandeld','Uitgesteld') NOT NULL DEFAULT 'Onbehandeld',
    isactief TINYINT(1) DEFAULT 1,
    opmerking TEXT,
    datumaangemaakt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    datumgewijzigd DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX ix_behandeling_medewerkerid (medewerkerid),
    INDEX ix_behandeling_patientid (patientid),
    CONSTRAINT fk_behandeling_medewerker FOREIGN KEY (medewerkerid) REFERENCES medewerker(id) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_behandeling_patient FOREIGN KEY (patientid) REFERENCES patient(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Opmerking: ON DELETE SET NULL vereist dat kolom nullable - dus we passen kolom aan:
ALTER TABLE behandeling MODIFY COLUMN medewerkerid INT DEFAULT NULL;
-- Herstel FK (verwijder en maak opnieuw om SET NULL toe te passen)
ALTER TABLE behandeling DROP FOREIGN KEY fk_behandeling_medewerker;
ALTER TABLE behandeling ADD CONSTRAINT fk_behandeling_medewerker FOREIGN KEY (medewerkerid) REFERENCES medewerker(id) ON DELETE SET NULL ON UPDATE CASCADE;

-- =========================================
-- 9. FACTUUR
-- =========================================
CREATE TABLE factuur (
    id INT PRIMARY KEY AUTO_INCREMENT,
    patientid INT NOT NULL,
    behandelingid INT NOT NULL,
    nummer VARCHAR(50) NOT NULL,
    datum DATE NOT NULL,
    bedrag DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    status ENUM('Verzonden','Niet-Verzonden','Betaald','Onbetaald') NOT NULL DEFAULT 'Niet-Verzonden',
    isactief TINYINT(1) DEFAULT 1,
    opmerking TEXT,
    datumaangemaakt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    datumgewijzigd DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY ux_factuur_nummer (nummer),
    INDEX ix_factuur_patientid (patientid),
    INDEX ix_factuur_behandelingid (behandelingid),
    CONSTRAINT fk_factuur_patient FOREIGN KEY (patientid) REFERENCES patient(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_factuur_behandeling FOREIGN KEY (behandelingid) REFERENCES behandeling(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE  
-- =========================================
-- 10. COMMUNICATIE
-- =========================================
CREATE TABLE communicatie (
    id INT PRIMARY KEY AUTO_INCREMENT,
    patientid INT NOT NULL,
    medewerkerid INT,
    bericht TEXT NOT NULL,
    verzonden_datum DATETIME NOT NULL,
    isactief TINYINT(1) DEFAULT 1,
    opmerking TEXT,
    datumaangemaakt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    datumgewijzigd DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX ix_communicatie_patientid (patientid),
    INDEX ix_communicatie_medewerkerid (medewerkerid),
    CONSTRAINT fk_communicatie_patient FOREIGN KEY (patientid) REFERENCES patient(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_communicatie_medewerker FOREIGN KEY (medewerkerid) REFERENCES medewerker(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Pas medewerkerid nullable voor SET NULL
ALTER TABLE communicatie MODIFY COLUMN medewerkerid INT DEFAULT NULL;
ALTER TABLE communicatie DROP FOREIGN KEY fk_communicatie_medewerker;
ALTER TABLE communicatie ADD CONSTRAINT fk_communicatie_medewerker FOREIGN KEY (medewerkerid) REFERENCES medewerker(id) ON DELETE SET NULL ON UPDATE CASCADE;

-- =========================================
-- 11. FEEDBACK
-- =========================================
CREATE TABLE feedback (
    id INT PRIMARY KEY AUTO_INCREMENT,
    patientid INT NOT NULL,
    beoordeling INT NOT NULL,
    praktijkemail VARCHAR(255),
    praktijktelefoon VARCHAR(20),
    isactief TINYINT(1) DEFAULT 1,
    opmerking TEXT,
    datumaangemaakt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    datumgewijzigd DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CHECK (beoordeling BETWEEN 1 AND 5),
    INDEX ix_feedback_patientid (patientid),
    CONSTRAINT fk_feedback_patient FOREIGN KEY (patientid) REFERENCES patient(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

