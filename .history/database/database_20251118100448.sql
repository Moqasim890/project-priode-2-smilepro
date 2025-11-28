-- Active: 1761234318508@@127.0.0.1@3306@tandarts
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- =========================================
-- 2. PERSOON
-- =========================================
CREATE TABLE `persoon` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `gebruikerid` BIGINT UNSIGNED NOT NULL,
    `voornaam` VARCHAR(100) NOT NULL,
    `tussenvoegsel` VARCHAR(20) NULL,
    `achternaam` VARCHAR(100) NOT NULL,
    `geboortedatum` DATE NOT NULL,
    `isactief` BIT(1) DEFAULT 1,
    `opmerking` TEXT NULL,
    `datumaangemaakt` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `datumgewijzigd` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_persoon_user`
        FOREIGN KEY (`gebruikerid`) REFERENCES `users`(`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================
-- 3. PATIENT
-- =========================================
CREATE TABLE `patient` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `persoonid` BIGINT UNSIGNED NOT NULL,
    `nummer` VARCHAR(50) NOT NULL,
    `medischdossier` TEXT NULL,
    `isactief` BIT(1) DEFAULT 1,
    `opmerking` TEXT NULL,
    `datumaangemaakt` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `datumgewijzigd` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_patient_persoon`
        FOREIGN KEY (`persoonid`) REFERENCES `persoon`(`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================
-- 4. MEDEWERKER
-- =========================================
CREATE TABLE `medewerker` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `persoonid` BIGINT UNSIGNED NOT NULL,
    `nummer` VARCHAR(50) NOT NULL,
    `medewerkertype` ENUM('Assistent','Mondhygiënist','Tandarts','Praktijkmanagement') NOT NULL,
    `specialisatie` VARCHAR(255) NULL,
    `beschikbaarheid` TEXT NULL,
    `isactief` BIT(1) DEFAULT 1,
    `opmerking` TEXT NULL,
    `datumaangemaakt` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `datumgewijzigd` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_medewerker_persoon`
        FOREIGN KEY (`persoonid`) REFERENCES `persoon`(`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ====================================
