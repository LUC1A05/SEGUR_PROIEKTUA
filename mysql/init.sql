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

INSERT INTO erabiltzaileak (izena, abizenak, nan, telefonoa, jaiotze_data, email, pasahitza) VALUES 
  ('Ainhoa','Tomas Santin','89345621-R','688907623','2005-02-09','ainhoa.th@gmail.com', MD5('T2or4t9')),
  ('Lucia','Del Rio Nizaso','45237843-J','688349067','2005-04-27','lucia.th@gmail.com', MD5('R9od2g9')),
  ('Asier','Las Hayas Fernandez','34781267-M','644568912','2005-03-12','asier.th@gmail.com', MD5('T5y39rw')),
  ('Mikel','Eguia Bengoa','45761234-F','633457612','2005-10-27','meguiaissks@gmail.com', MD5('K3o90tf'));

INSERT INTO maskotak (maskotaren_izena, espeziea, arraza, adina, sexua, deskribapena) VALUES 
  ('Thor', 'Txakurra', 'Boxer', 10,'Arra', 'Bihotz arazoak'), 
  ('Pato', 'Loroa', 'Guacamayo', 35, 'Emea', ''), 
  ('Patxi', 'Hamsterra','Siriakoa', 1, 'Arra', ''), 
  ('Pascal', 'Kamalehoia', 'Kamalehoi berdea', 4, 'Emea', 'Begi batetik ez du
ikusten'), 
  ('Remi', 'Arratoia', 'Arratoi beltza', 2, 'Arra', ''), 
  ('Jupiter', 'Zaldia', 'Purasangre', 29, 'Emea', 'Artritisa dauka'),
('Oreo', 'Txerria', 'Duroc', 7, 'Arra', ''), 
  ('Nagini', 'Sugea', 'Boa Constrictor', 20, 'Emea', '');

INSERT INTO maskotak (maskotaren_izena, espeziea, arraza, adina, sexua, deskribapena) VALUES 
  ('Theodore', 'Urtxintxa', 'Sciurus', 2, 'Arra', ''), 
  ('Gustavo', 'Igela', 'Dardo-igela', 1, 'Emea', ''), 
  ('Miquelangelo', 'Dortoka', 'Seychelles Dortoka Handia', 120, 'Arra', ''), 
  ('Loki', 'Untxia', 'Flandeseko Untxi Handia', 4, 'Emea','');
