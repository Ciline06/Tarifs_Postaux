-- SUPPRESSION DES TABLES SI ELLES EXISTENT DEJA 

DROP TABLE IF EXISTS G02_Tarifer CASCADE;
DROP TABLE IF EXISTS G02_TypeEnvoi CASCADE;
DROP TABLE IF EXISTS G02_Destination CASCADE;


-- CREATION DES TABLES

CREATE TABLE G02_Destination (
  id_destination SERIAL PRIMARY KEY,
  nom_destination VARCHAR(100) NOT NULL,
  zone_tarifaire VARCHAR(50),
  code_pays VARCHAR(5),
  devise VARCHAR(10) NOT NULL CHECK (devise <> ''),
  restrictions_envoi VARCHAR(255)
);

CREATE TABLE G02_TypeEnvoi (
  id_type_envoi SERIAL PRIMARY KEY,
  nom_type_envoi VARCHAR(100) NOT NULL,
  delai_livraison INTEGER NOT NULL CHECK (delai_livraison >= 0),
  assurance_possible BOOLEAN NOT NULL,
  fragile BOOLEAN NOT NULL,
  option_tarifaire VARCHAR(50)
);

CREATE TABLE G02_Tarifer (
  id_tarif SERIAL PRIMARY KEY,
  id_type_envoi INTEGER NOT NULL,
  id_destination INTEGER NOT NULL,
  poids_min NUMERIC(7,2) NOT NULL CHECK (poids_min >= 0),
  poids_max NUMERIC(7,2) NOT NULL CHECK (poids_max >= poids_min),
  tarif NUMERIC(7,2) NOT NULL CHECK (tarif >= 0),
  date_debut DATE NOT NULL,
  date_fin DATE,

  UNIQUE (id_type_envoi, id_destination, date_debut),

  CONSTRAINT fk_tarif_type FOREIGN KEY (id_type_envoi)
    REFERENCES G02_TypeEnvoi (id_type_envoi)
    ON DELETE RESTRICT ON UPDATE CASCADE,

  CONSTRAINT fk_tarif_dest FOREIGN KEY (id_destination)
    REFERENCES G02_Destination (id_destination)
    ON DELETE RESTRICT ON UPDATE CASCADE
);


-- INSERTIONS TYPE ENVOI

INSERT INTO G02_TypeEnvoi 
(nom_type_envoi, delai_livraison, assurance_possible, fragile, option_tarifaire)
VALUES
('Lettre', 2, FALSE, FALSE, NULL),
('Colis', 5, TRUE, TRUE, 'assurance'),
('Lettre verte suivie', 3, FALSE, FALSE, 'suivi'),
('Chronopost', 1, TRUE, TRUE, 'express'),
('Colis économique', 8, FALSE, FALSE, NULL),
('Lettre internationale', 6, FALSE, FALSE, NULL),
('Eco courrier', 6, FALSE, FALSE, NULL),
('Express 24h', 1, TRUE, TRUE, 'Express rapide'),
('Colis standard international', 5, TRUE, TRUE, 'Suivi + assurance'),
('Lettre urgente', 1, FALSE, FALSE, 'Prioritaire'),
('Colis volumineux', 8, TRUE, TRUE, 'Grand volume'),
('Transport médical', 2, TRUE, TRUE, 'Température contrôlée'),
('Envoi économique international', 10, FALSE, FALSE, NULL),
('Colis fragile renforcé', 4, TRUE, TRUE, 'Protection renforcée');


-- INSERTIONS DESTINATION

INSERT INTO G02_Destination 
(nom_destination, zone_tarifaire, code_pays, devise, restrictions_envoi)
VALUES
('France', 'Zone 1', 'FR', 'EUR', NULL),
('Algerie', 'Zone 2', 'DZ', 'DZD', 'Poids max 30kg'),
('États-Unis', 'Zone B', 'US', 'USD', 'Batteries interdites'),
('Canada', 'Zone B', 'CA', 'CAD', NULL),
('Espagne', 'Zone 1', 'ES', 'EUR', NULL),
('Chine', 'Zone C', 'CN', 'CNY', 'Contrôle strict'),
('Portugal', 'Zone 1', 'PT', 'EUR', NULL),
('Pays-Bas', 'Zone 1', 'NL', 'EUR', NULL),
('Luxembourg', 'Zone 1', 'LU', 'EUR', NULL),
('Suède', 'Zone A', 'SE', 'SEK', NULL),
('Norvège', 'Zone A', 'NO', 'NOK', NULL),
('Danemark', 'Zone A', 'DK', 'DKK', NULL),
('Pologne', 'Zone 1', 'PL', 'PLN', NULL),
('Turquie', 'Zone B', 'TR', 'TRY', 'Contrôle douanier'),
('Inde', 'Zone C', 'IN', 'INR', 'Produits sensibles'),
('Afrique du Sud', 'Zone C', 'ZA', 'ZAR', NULL),
('Italie', 'Zone 1', 'IT', 'EUR', NULL),
('Allemagne', 'Zone 1', 'DE', 'EUR', NULL),
('Royaume-Uni', 'Zone A', 'GB', 'GBP', NULL),
('Japon', 'Zone C', 'JP', 'JPY', NULL),
('Brésil', 'Zone C', 'BR', 'BRL', NULL),
('Suisse', 'Zone A', 'CH', 'CHF', NULL);


-- INSERTIONS TARIFICATION

INSERT INTO G02_Tarifer 
(id_destination, id_type_envoi, poids_min, poids_max, tarif, date_debut, date_fin)

VALUES

(
    (SELECT id_destination FROM G02_Destination WHERE nom_destination = 'France'),
    (SELECT id_type_envoi FROM G02_TypeEnvoi WHERE nom_type_envoi = 'Lettre'),
    0, 20, 1.30, '2025-01-01', '2025-12-31'
),

(
    (SELECT id_destination FROM G02_Destination WHERE nom_destination = 'France'),
    (SELECT id_type_envoi FROM G02_TypeEnvoi WHERE nom_type_envoi = 'Colis'),
    20, 5000, 3.20, '2025-01-01', '2025-12-31'
),

(
    (SELECT id_destination FROM G02_Destination WHERE nom_destination = 'Algerie'),
    (SELECT id_type_envoi FROM G02_TypeEnvoi WHERE nom_type_envoi = 'Colis'),
    0, 30000, 18.50, '2025-01-01', '2025-12-31'
),

(
    (SELECT id_destination FROM G02_Destination WHERE nom_destination = 'États-Unis'),
    (SELECT id_type_envoi FROM G02_TypeEnvoi WHERE nom_type_envoi = 'Chronopost'),
    0, 5000, 45.00, '2025-01-01', '2025-12-31'
),

(
    (SELECT id_destination FROM G02_Destination WHERE nom_destination = 'Canada'),
    (SELECT id_type_envoi FROM G02_TypeEnvoi WHERE nom_type_envoi = 'Lettre internationale'),
    0, 100, 4.20, '2025-01-01', '2025-12-31'
),

(
    (SELECT id_destination FROM G02_Destination WHERE nom_destination = 'Espagne'),
    (SELECT id_type_envoi FROM G02_TypeEnvoi WHERE nom_type_envoi = 'Lettre verte suivie'),
    0, 500, 3.10, '2025-01-01', '2025-12-31'
),

(
    (SELECT id_destination FROM G02_Destination WHERE nom_destination = 'Chine'),
    (SELECT id_type_envoi FROM G02_TypeEnvoi WHERE nom_type_envoi = 'Colis économique'),
    0, 20000, 22.00, '2025-01-01', '2025-12-31'
);