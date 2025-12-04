-- Maak en gebruik database
CREATE DATABASE IF NOT EXISTS smilepro
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE smilepro;

-- Schone lei
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
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

-- 0. USERS
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gebruikersnaam VARCHAR(100) NOT NULL,
    wachtwoord VARCHAR(255) NOT NULL,
    rolnaam VARCHAR(50) NOT NULL,
    ingelogd DATETIME NULL,
    uitgelogd DATETIME NULL,
    isactief TINYINT(1) NOT NULL DEFAULT 1,
    opmerking TEXT,
    datumaangemaakt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    datumgewijzigd DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY ux_users_gebruikersnaam (gebruikersnaam)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 1. PERSOON
CREATE TABLE persoon (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gebruikerid INT NULL,
    voornaam VARCHAR(100) NOT NULL,
    tussenvoegsel VARCHAR(20),
    achternaam VARCHAR(100) NOT NULL,
    geboortedatum DATE NOT NULL,
    isactief TINYINT(1) DEFAULT 1,
    opmerking TEXT,
    datumaangemaakt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    datumgewijzigd DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX ix_persoon_gebruikerid (gebruikerid),
    CONSTRAINT fk_persoon_users FOREIGN KEY (gebruikerid)
        REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. PATIENT
CREATE TABLE patient (
    id INT AUTO_INCREMENT PRIMARY KEY,
    persoonid INT NOT NULL,
    nummer VARCHAR(50) NOT NULL,
    medischdossier TEXT,
    isactief TINYINT(1) DEFAULT 1,
    opmerking TEXT,
    datumaangemaakt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    datumgewijzigd DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY ux_patient_nummer (nummer),
    INDEX ix_patient_persoonid (persoonid),
    CONSTRAINT fk_patient_persoon FOREIGN KEY (persoonid)
        REFERENCES persoon(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. MEDEWERKER
CREATE TABLE medewerker (
    id INT AUTO_INCREMENT PRIMARY KEY,
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
    CONSTRAINT fk_medewerker_persoon FOREIGN KEY (persoonid)
        REFERENCES persoon(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. BESCHIKBAARHEID
CREATE TABLE beschikbaarheid (
    id INT AUTO_INCREMENT PRIMARY KEY,
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
    CONSTRAINT fk_beschikbaarheid_medewerker FOREIGN KEY (medewerkerid)
        REFERENCES medewerker(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. CONTACT
CREATE TABLE contact (
    id INT AUTO_INCREMENT PRIMARY KEY,
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
    CONSTRAINT fk_contact_patient FOREIGN KEY (patientid)
        REFERENCES patient(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. AFSPRAKEN
CREATE TABLE afspraken (
    id INT AUTO_INCREMENT PRIMARY KEY,
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
    CONSTRAINT fk_afspraken_patient FOREIGN KEY (patientid)
        REFERENCES patient(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_afspraken_medewerker FOREIGN KEY (medewerkerid)
        REFERENCES medewerker(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. BEHANDELING
CREATE TABLE behandeling (
    id INT AUTO_INCREMENT PRIMARY KEY,
    medewerkerid INT NULL,
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
    CONSTRAINT fk_behandeling_medewerker FOREIGN KEY (medewerkerid)
        REFERENCES medewerker(id) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_behandeling_patient FOREIGN KEY (patientid)
        REFERENCES patient(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. FACTUUR
CREATE TABLE factuur (
    id INT AUTO_INCREMENT PRIMARY KEY,
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
    CONSTRAINT fk_factuur_patient FOREIGN KEY (patientid)
        REFERENCES patient(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_factuur_behandeling FOREIGN KEY (behandelingid)
        REFERENCES behandeling(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. COMMUNICATIE
CREATE TABLE communicatie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patientid INT NOT NULL,
    medewerkerid INT NULL,
    bericht TEXT NOT NULL,
    verzonden_datum DATETIME NOT NULL,
    isactief TINYINT(1) DEFAULT 1,
    opmerking TEXT,
    datumaangemaakt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    datumgewijzigd DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX ix_communicatie_patientid (patientid),
    INDEX ix_communicatie_medewerkerid (medewerkerid),
    CONSTRAINT fk_communicatie_patient FOREIGN KEY (patientid)
        REFERENCES patient(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_communicatie_medewerker FOREIGN KEY (medewerkerid)
        REFERENCES medewerker(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. FEEDBACK
CREATE TABLE feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
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
    CONSTRAINT fk_feedback_patient FOREIGN KEY (patientid)
        REFERENCES patient(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

