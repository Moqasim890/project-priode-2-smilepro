-- Active: 1761234318508@@127.0.0.1@3306@tandarts

-- =========================================
-- 1. PERSOON
-- =========================================
CREATE TABLE persoon (
    id INT PRIMARY KEY AUTO_INCREMENT,
    gebruikerid INT NOT NULL,
    voornaam VARCHAR(100) NOT NULL,
    tussenvoegsel VARCHAR(20),
    achternaam VARCHAR(100) NOT NULL,
    geboortedatum DATE NOT NULL,
    isactief TINYINT(1) DEFAULT 1,
    opmerking TEXT,
    datumaangemaakt DATETIME DEFAULT CURRENT_TIMESTAMP,
    datumgewijzigd DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (gebruikerid) REFERENCES users(id)
);

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
    datumaangemaakt DATETIME DEFAULT CURRENT_TIMESTAMP,
    datumgewijzigd DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (persoonid) REFERENCES persoon(id)
);

-- =========================================
-- 3. MEDEWERKER
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
    datumaangemaakt DATETIME DEFAULT CURRENT_TIMESTAMP,
    datumgewijzigd DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (persoonid) REFERENCES persoon(id)
);

-- =========================================
-- 4. BESCHIKBAARHEID
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
    datumaangemaakt DATETIME DEFAULT CURRENT_TIMESTAMP,
    datumgewijzigd DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (medewerkerid) REFERENCES medewerker(id)
);

-- =========================================
-- 5. CONTACT
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
    datumaangemaakt DATETIME DEFAULT CURRENT_TIMESTAMP,
    datumgewijzigd DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (patientid) REFERENCES patient(id)
);

-- =========================================
-- 6. AFSPRAKEN
-- =========================================
CREATE TABLE afspraken (
    id INT PRIMARY KEY AUTO_INCREMENT,
    patientid INT NOT NULL,
    medewerkerid INT NOT NULL,
    datum DATE NOT NULL,
    tijd TIME NOT NULL,
    status ENUM('Bevestigd','Geannuleerd') NOT NULL,
    isactief TINYINT(1) DEFAULT 1,
    opmerking TEXT,
    datumaangemaakt DATETIME DEFAULT CURRENT_TIMESTAMP,
    datumgewijzigd DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (patientid) REFERENCES patient(id),
    FOREIGN KEY (medewerkerid) REFERENCES medewerker(id)
);

-- =========================================
-- 7. BEHANDELING
-- =========================================
CREATE TABLE behandeling (
    id INT PRIMARY KEY AUTO_INCREMENT,
    medewerkerid INT NOT NULL,
    patientid INT NOT NULL,
    datum DATE NOT NULL,
    tijd TIME NOT NULL,
    behandelingtype ENUM('Controles','Vullingen','Gebitsreiniging','Orthodontie','Wortelkanaalbehandelingen') NOT NULL,
    omschrijving TEXT,
    kosten DECIMAL(10,2) NOT NULL,
    status ENUM('Behandeld','Onbehandeld','Uitgesteld') NOT NULL,
    isactief TINYINT(1) DEFAULT 1,
    opmerking TEXT,
    datumaangemaakt DATETIME DEFAULT CURRENT_TIMESTAMP,
    datumgewijzigd DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (medewerkerid) REFERENCES medewerker(id),
    FOREIGN KEY (patientid) REFERENCES patient(id)
);

-- =========================================
-- 8. FACTUUR
-- =========================================
CREATE TABLE factuur (
    id INT PRIMARY KEY AUTO_INCREMENT,
    patientid INT NOT NULL,
    behandelingid INT NOT NULL,
    nummer VARCHAR(50) NOT NULL,
    datum DATE NOT NULL,
    bedrag DECIMAL(10,2) NOT NULL,
    status ENUM('Verzonden','Niet-Verzonden','Betaald','Onbetaald') NOT NULL,
    isactief TINYINT(1) DEFAULT 1,
    opmerking TEXT,
    datumaangemaakt DATETIME DEFAULT CURRENT_TIMESTAMP,
    datumgewijzigd DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (patientid) REFERENCES patient(id),
    FOREIGN KEY (behandelingid) REFERENCES behandeling(id)
);

-- =========================================
-- 9. COMMUNICATIE
-- =========================================
CREATE TABLE communicatie (
    id INT PRIMARY KEY AUTO_INCREMENT,
    patientid INT NOT NULL,
    medewerkerid INT NOT NULL,
    bericht TEXT NOT NULL,
    verzondendatum DATETIME NOT NULL,
    isactief TINYINT(1) DEFAULT 1,
    opmerking TEXT,
    datumaangemaakt DATETIME DEFAULT CURRENT_TIMESTAMP,
    datumgewijzigd DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (patientid) REFERENCES patient(id),
    FOREIGN KEY (medewerkerid) REFERENCES medewerker(id)
);

-- =========================================
-- 10. FEEDBACK
-- =========================================
CREATE TABLE feedback (
    id INT PRIMARY KEY AUTO_INCREMENT,
    patientid INT NOT NULL,
    beoordeling INT CHECK (beoordeling BETWEEN 1 AND 5),
    praktijkemail VARCHAR(255),
    praktijktelefoon VARCHAR(20),
    isactief TINYINT(1) DEFAULT 1,
    opmerking TEXT,
    datumaangemaakt DATETIME DEFAULT CURRENT_TIMESTAMP,
    datumgewijzigd DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (patientid) REFERENCES patient(id)
);
