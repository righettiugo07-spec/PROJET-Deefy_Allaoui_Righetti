-- Deefy : tables utilisees par l'application (MySQL / MariaDB)
-- Structure reconstituee d'apres le TD13 (tables playlist, track, playlist2track) :
-- si tu utilises le script fourni dans Arche, ce fichier n'est necessaire que pour les donnees d'essai.
-- Attention : les 3 tables ci-dessous sont supprimees puis recreees.

SET NAMES 'UTF8';

DROP TABLE IF EXISTS playlist2track;
DROP TABLE IF EXISTS track;
DROP TABLE IF EXISTS playlist;

-- Les colonnes texte sont larges (300) car le texte est neutralise contre le XSS
-- avant stockage (un guillemet devient &#34;) ; la limite de saisie reste de 50 octets.
CREATE TABLE playlist (
    id  INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE track (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    titre          VARCHAR(300) NOT NULL,
    genre          VARCHAR(300),
    duree          INT,
    filename       VARCHAR(100) NOT NULL,
    type           VARCHAR(1) NOT NULL,           -- 'A' = piste d'album, 'P' = podcast
    artiste_album  VARCHAR(300),
    titre_album    VARCHAR(300),
    annee_album    INT,
    numero_album   INT,
    auteur_podcast VARCHAR(300),
    date_podcast   DATE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE playlist2track (
    id_pl               INT NOT NULL,
    id_track            INT NOT NULL,
    no_piste_dans_liste INT NOT NULL,
    PRIMARY KEY (id_pl, id_track),
    FOREIGN KEY (id_pl) REFERENCES playlist(id),
    FOREIGN KEY (id_track) REFERENCES track(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Donnees d'essai (toutes les pistes utilisent audio/exemple.mp3)
INSERT INTO playlist (id, nom) VALUES (1, 'Révisions'), (2, 'Sport');

INSERT INTO track (id, titre, genre, duree, filename, type, auteur_podcast, date_podcast) VALUES
    (1, 'Calme', 'Ambient', 3, 'audio/exemple.mp3', 'P', 'Artiste A', '2026-09-01'),
    (2, 'Focus', 'Lo-fi', 3, 'audio/exemple.mp3', 'P', 'Artiste B', '2026-09-15');

INSERT INTO track (id, titre, genre, duree, filename, type, artiste_album, titre_album, annee_album, numero_album) VALUES
    (3, 'Rythme', 'Électro', 3, 'audio/exemple.mp3', 'A', 'Artiste C', 'Album C', 2024, 1);

INSERT INTO playlist2track (id_pl, id_track, no_piste_dans_liste) VALUES
    (1, 1, 1), (1, 2, 2), (2, 3, 1);
