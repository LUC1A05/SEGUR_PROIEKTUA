CREATE DATABASE IF NOT EXISTS `database` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `database`;

-- Erabiltzaileen taula 
CREATE TABLE IF NOT EXISTS `erabiltzaileak` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `izen_abizen` VARCHAR(100) NOT NULL,
  `nan` VARCHAR(10) NOT NULL,
  `telefonoa` CHAR(9) NOT NULL,
  `jaiotze_data` DATE NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `erabiltzaile_izena` VARCHAR(50) NOT NULL,
  `pasahitza` VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Maskoten taula
CREATE TABLE IF NOT EXISTS `maskotak` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `maskotaren_izena` VARCHAR(100) NOT NULL,
  `espeziea` VARCHAR(100) NOT NULL,
  `arraza` VARCHAR(100),
  `adina` INT,
  `sexua` ENUM('Arra', 'Emea') NOT NULL,
  `deskribapena` TEXT,
  `jabea_id` INT NOT NULL,
  `irudia` LONGBLOB, 
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (jabea_id) REFERENCES erabiltzaileak(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Erabiltzaieen adibideen insert-a
INSERT INTO `erabiltzaileak` (izen_abizen, nan, telefonoa, jaiotze_data, email, erabiltzaile_izena, pasahitza) VALUES
  ('Olatz Elejalde Urrutia','67376786-B','618545678','1996-01-15','olatz.eu@gmail.com', 'Olatz', MD5('Olatz')),
  ('Maider Tato Herrera','87654321-X','634524690','1995-07-22','maider.th@gmail.com', 'Maider', MD5('Maider')),
  ('Ainhoa Tomas Santin','89345621-M','688907623','2005-02-09','ainhoa.ts@gmail.com', 'Ainhoa', MD5('Ainhoa')),
  ('Lucia Del Rio Lizaso','45237843-V','688349067','2005-04-27','lucia.drl@gmail.com', 'Lucia', MD5('Lucia')),
  ('Asier Las Hayas Fernandez','34781267-T','644568912','2005-03-12','asier.lhf@gmail.com', 'Asier', MD5('Asier')),
  ('Mikel Eguia Bengoa','45761234-C','633457612','2005-10-27','mikel.eb@gmail.com', 'Mikel', MD5('Mikel'));


-- Maskoten adibideen insert-a
INSERT INTO `maskotak` (maskotaren_izena, espeziea, arraza, adina, sexua, deskribapena, jabea_id) VALUES
  ('Lur', 'Txakurra', 'Labrador Retriever', 5, 'Arra', 'Alergia', 1),
  ('Kosmo', 'Katua', 'Europarra', 3, 'Emea', 'Antzutua, urteko kontrolak', 1),
  ('Harri', 'Txakurra', 'Bulldog', 2, 'Arra', 'Arnasketa arazoak', 2),
  ('Wanda', 'Hamsterra', 'Siberiakoa', 1, 'Emea', '', 2),
  ('Ilargi', 'Txakurra', 'Euskal Artzaina', 4, 'Arra', '', 6),
  ('Dune', 'Katua', 'Siamesa', 2, 'Emea', '', 3),
  ('Thor', 'Txakurra', 'Boxer', 10,'Arra', 'Bihotz arazoak', 4), 
  ('Pato', 'Loroa', 'Guacamayo', 35, 'Emea', '', 6), 
  ('Patxi', 'Hamsterra','Siriakoa', 1, 'Arra', '', 1), 
  ('Pascal', 'Kamalehoia', 'Kamalehoi berdea', 4, 'Emea', 'Begi batetik ez du ikusten', 2), 
  ('Remi', 'Arratoia', 'Arratoi beltza', 2, 'Arra', '', 3), 
  ('Jupiter', 'Zaldia', 'Purasangre', 29, 'Emea', 'Artritisa dauka', 4),
  ('Oreo', 'Txerria', 'Duroc', 7, 'Arra', '', 1), 
  ('Nagini', 'Sugea', 'Boa Constrictor', 20, 'Emea', '', 5),
  ('Theodore', 'Urtxintxa', 'Sciurus', 2, 'Arra', '', 1), 
  ('Gustavo', 'Igela', 'Dardo-igela', 1, 'Emea', '', 5), 
  ('Miquelangelo', 'Dortoka', 'Seychelles Dortoka Handia', 120, 'Arra', '', 3), 
  ('Loki', 'Untxia', 'Flandeseko Untxi Handia', 4, 'Emea','', 4);