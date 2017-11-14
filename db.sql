CREATE DATABASE APIdallas;
USE APIdallas;

CREATE TABLE personne(
  id INT(50) AUTO_INCREMENT,
  prenom VARCHAR(30),
  nom VARCHAR(30),
  PRIMARY KEY (id)
);

CREATE TABLE clef(
  id INT(50) AUTO_INCREMENT,
  id_personne INT(30),
  PRIMARY KEY (id),
  CONSTRAINT fk_personid FOREIGN KEY (id_personne) REFERENCES personne(id)
);
