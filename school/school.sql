CREATE TABLE apprenants (
    id_appr INT PRIMARY KEY AUTO_INCREMENT,
    nom_appr VARCHAR(50),
    prenom_appr VARCHAR(50),
    email_appr VARCHAR(100),
    password_appr VARCHAR(200)
);
-- Create table for Formateurs
CREATE TABLE formateurs (
    id_formateur INT PRIMARY KEY AUTO_INCREMENT,
    nom_formateur VARCHAR(50),
    prenom_formateur VARCHAR(50),
    email_formateur VARCHAR(100),
    password_formateur VARCHAR(200)
  
);

-- Create table for Formation
CREATE TABLE formations (
    id_formation INT PRIMARY KEY AUTO_INCREMENT,
    titre VARCHAR(50),
    description TEXT,
    categorie VARCHAR(50),
    image VARCHAR(100),
    mass_horaire INT
);

-- Create table for Sessions
CREATE TABLE sessions (
    id_session INT PRIMARY KEY AUTO_INCREMENT,
    date_debut DATE,
    date_fin DATE,
    etat VARCHAR(50),
    places INT,
    id_formation INT,
    id_formateur INT,
    FOREIGN KEY (id_formateur) REFERENCES formateurs (id_formateur),
    FOREIGN KEY (id_formation) REFERENCES formations (id_formation)

);

-- Create table for Inscription
CREATE TABLE inscriptions (
    id_inscription INT PRIMARY KEY AUTO_INCREMENT,
    id_appr INT,
    id_session INT,
    validate boolean,
    FOREIGN KEY (id_appr) REFERENCES apprenants (id_appr),
    FOREIGN KEY (id_session) REFERENCES sessions (id_session)
); 



-- Inserting data into the apprenants table
INSERT INTO apprenants (nom_appr, prenom_appr, email_appr, password_appr) VALUES
    ('Smith', 'John', 'john.smith@example.com', 'password1'),
    ('Doe', 'Jane', 'jane.doe@example.com', 'password2'),
    ('Johnson', 'Robert', 'robert.johnson@example.com', 'password3'),
    ('Lee', 'Samantha', 'samantha.lee@example.com', 'password4'),
    ('Garcia', 'Carlos', 'carlos.garcia@example.com', 'password5'),
    ('Wang', 'Li', 'li.wang@example.com', 'password6'),
    ('Kim', 'Min', 'min.kim@example.com', 'password7'),
    ('Chen', 'Wei', 'wei.chen@example.com', 'password8'),
    ('Singh', 'Raj', 'raj.singh@example.com', 'password9'),
    ('Gonzalez', 'Maria', 'maria.gonzalez@example.com', 'password10');

-- Inserting data into the formateurs table
INSERT INTO formateurs (nom_formateur, prenom_formateur, email_formateur, password_formateur) VALUES
    ('Miller', 'David', 'david.miller@example.com', 'password1'),
    ('Nguyen', 'Linh', 'linh.nguyen@example.com', 'password2'),
    ('Patel', 'Rohan', 'rohan.patel@example.com', 'password3'),
    ('Thompson', 'Emily', 'emily.thompson@example.com', 'password4'),
    ('Garcia', 'Juan', 'juan.garcia@example.com', 'password5'),
    ('Kim', 'Ji-hye', 'ji-hye.kim@example.com', 'password6'),
    ('Li', 'Yan', 'yan.li@example.com', 'password7'),
    ('Chen', 'Hui', 'hui.chen@example.com', 'password8'),
    ('Singh', 'Amit', 'amit.singh@example.com', 'password9'),
    ('Gonzalez', 'Jose', 'jose.gonzalez@example.com', 'password10');

-- Inserting data into the formations table
INSERT INTO formations (titre, description, categorie, image, mass_horaire) 
VALUES 
('Marketing Digital', 'Formation sur le marketing digital', 'Marketing', 'marketing.jpg', 20),
('Développement Web', 'Formation sur le développement web', 'Informatique', 'developpement-web.jpg', 50),
('Gestion de projet', 'Formation sur la gestion de projet', 'Management', 'gestion-de-projet.jpg', 30),
('Design Graphique', 'Formation sur le design graphique', 'Design', 'design-graphique.jpg', 25),
('Langue Anglaise', 'Formation sur la langue anglaise', 'Langues', 'anglais.jpg', 40),
('Finance et Comptabilité', 'Formation sur la finance et comptabilité', 'Finance', 'finance.jpg', 35),
('Communication', 'Formation sur la communication', 'Communication', 'communication.jpg', 15),
('Ressources Humaines', 'Formation sur les ressources humaines', 'Management', 'ressources-humaines.jpg', 45),
('Développement Personnel', 'Formation sur le développement personnel', 'Développement personnel', 'developpement-personnel.jpg', 20),
('Marketing et Communication Digitale', 'Formation sur le marketing et la communication digitale', 'Marketing', 'marketing-communication-digitale.jpg', 30);

-- Inserting data into the sessions table
INSERT INTO sessions (date_debut, date_fin, etat, places, id_formation, id_formateur)
VALUES
('2023-05-10', '2023-05-15', 'en cours dinscription', 20, 1, 1),
('2023-06-05', '2023-06-25', 'en cours dinscription', 50, 2, 2),
('2023-07-12', '2023-07-18', 'annulé', 0, 3, 3),
('2023-06-20', '2023-06-27', 'en cours', 15, 4, 4),
('2023-09-05', '2023-09-30', 'en cours dinscription', 40, 5, 5),
('2023-08-01', '2023-08-31', 'en cours', 35, 6, 6),
('2023-11-01', '2023-11-10', 'en cours dinscription', 15, 7, 7),
('2023-10-15', '2023-10-30', 'en cours dinscription', 45, 8, 8),
('2023-12-01', '2023-12-15', 'en cours dinscription', 20, 9, 9),
('2023-11-20', '2023-12-20', 'en cours', 30, 10, 10);


-- Inserting data into the inscriptions table
INSERT INTO inscriptions (id_appr, id_session, validate) VALUES
(1, 1, 1),
(2, 1, 1),
(3, 1, 0),
(4, 2, 0),
(5, 2, 1),
(6, 2, 0),
(7, 3, 1),
(8, 3, 1),
(9, 3, 1),
(10, 4, 0);



-- change etat of  the current session to en cours


DELIMITER $$
CREATE TRIGGER update_session_status
AFTER INSERT ON sessions
FOR EACH ROW
BEGIN
    IF NEW.date_fin = CURDATE() THEN
        UPDATE sessions SET etat = 'en cours' WHERE id_session = NEW.id_session;
    END IF;
END$$
DELIMITER ;



-- update the etat of session before 3 days







DELIMITER $$
CREATE TRIGGER check_inscriptions
BEFORE UPDATE ON inscriptions
FOR EACH ROW
BEGIN
    DECLARE session_id INT;
    DECLARE session_date_debut DATE;
    DECLARE session_places INT;
    DECLARE inscriptions_count INT;
    
    SELECT id_session, places, date_debut INTO session_id, session_places, session_date_debut FROM sessions WHERE id_session = OLD.id_session;
    
    SELECT COUNT(*) INTO inscriptions_count FROM inscriptions WHERE id_session = OLD.id_session;
    
    IF inscriptions_count <= 3 AND DATEDIFF(session_date_debut, CURDATE()) <= 3 THEN
        IF NEW.places > session_places THEN
            SET NEW.places = session_places;
        END IF;
        
        IF NEW.places <= 3 THEN
            UPDATE sessions SET etat = 'ANNULLER' WHERE id_session = session_id;
        END IF;
    END IF;
END $$
DELIMITER ;

DELIMITER //
CREATE TRIGGER update_etat_trigger BEFORE UPDATE ON sessions
FOR EACH ROW
BEGIN
    IF NEW.date_fin < CURDATE() THEN
        SET NEW.etat = 'cloturée';
    END IF;
END//
DELIMITER ;
