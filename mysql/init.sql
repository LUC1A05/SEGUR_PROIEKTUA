CREATE DATABASE IF NOT EXISTS `database` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `database`;

-- Erabiltzaileen taula 
CREATE TABLE IF NOT EXISTS `erabiltzaileak` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `izena` VARCHAR(100) NOT NULL,
  `abizenak` VARCHAR(150) NOT NULL,
  `nan` VARCHAR(10) NOT NULL,
  `telefonoa` CHAR(9) NOT NULL,
  `jaiotze_data` DATE NOT NULL,
  `email` VARCHAR(150) NOT NULL,
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
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Erabiltzaieen adibideen insert-a
INSERT INTO `erabiltzaileak` (izena, abizenak, nan, telefonoa, jaiotze_data, email, pasahitza) VALUES
('Olatz','Elejalde Urrutia','67376786-B','618545678','1996-01-15','olatz.eu@gmail.com', MD5('r6P629f')),
('Maider','Tato Herrera','87654321-X','634524690','1995-07-22','maider.th@gmail.com', MD5('Kp3n7wZ2x'));

-- Maskoten adibideen insert-a
INSERT INTO `maskotak` (maskotaren_izena, espeziea, arraza, adina, sexua, deskribapena) VALUES
('Lur', 'Txakurra', 'Labrador Retriever', 5, 'Arra', 'Alergia'),
('Kosmo', 'Katua', 'Europarra', 3, 'Emea', 'Antzutua, urteko kontrolak'),
('Harri', 'Txakurra', 'Bulldog', 2, 'Arra', 'Arnasketa arazoak'),
('Wanda', 'Hamsterra', 'Siberiakoa', 1, 'Emea', ''),
('Ilargi', 'Txakurra', 'Euskal Artzaina', 4, 'Arra', ''),
('Dune', 'Katua', 'Siamesa', 2, 'Emea', '');
