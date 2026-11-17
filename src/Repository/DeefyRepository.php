<?php

namespace iutnc\deefy\Repository;

use PDO;

class DeefyRepository
{
    private PDO $pdo;

    public function __construct()
    {
        //Recup de la config de la BD
        $configFile = __DIR__ . '/../../config.db.ini';

        if (!is_file($configFile)) {
            throw new \RuntimeException('Fichier de configuration introuvable');
        }

        $config = parse_ini_file($configFile);

        if ($config === false) {
            throw new \RuntimeException('Erreur de lecture de la configuration');
        }

        //Connexion à la BD avec PDO
        $dsn = $config['driver']
             . ':host=' . $config['host']
             . ';dbname=' . $config['database']
             . ';charset=utf8mb4';

        $this->pdo = new PDO(
            $dsn,
            $config['username'],
            $config['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
        );
    }

    //F4 : enregistre une nouvelle playlist vide et renvoie son identifiant
    public function creerPlaylist(string $nom): int
    {
        $requete = $this->pdo->prepare('INSERT INTO playlist (nom) VALUES (?)');
        $requete->execute([$nom]);

        return (int) $this->pdo->lastInsertId();
    }

    //F3 : indique si une playlist existe
    public function playlistExiste(int $id): bool
    {
        $requete = $this->pdo->prepare('SELECT id FROM playlist WHERE id = ?');
        $requete->execute([$id]);

        $ligne = $requete->fetch();
        $requete->closeCursor();

        return $ligne !== false;
    }

    //F3 : enregistre une piste de podcast et l'ajoute a la fin de la playlist
    public function ajouterPiste(int $idPlaylist, \iutnc\deefy\audio\tracks\PodcastTrack $piste): void
    {
        //Insertion de la piste dans la table track
        $requete = $this->pdo->prepare(
            "INSERT INTO track (titre, genre, duree, filename, type, auteur_podcast, date_posdcast)
             VALUES (?, ?, ?, ?, 'P', ?, ?)"
        );
        $requete->execute([
            $piste->titre,
            $piste->genre,
            $piste->duree,
            $piste->nomfichier,
            $piste->auteur,
            $piste->date
        ]);

        $idPiste = (int) $this->pdo->lastInsertId();

        //Position de la piste : a la suite de celles deja presentes
        $requete = $this->pdo->prepare('SELECT MAX(no_piste_dans_liste) FROM playlist2track WHERE id_pl = ?');
        $requete->execute([$idPlaylist]);

        $max = $requete->fetchColumn();
        $requete->closeCursor();

        $position = 1;
        if ($max !== false && $max !== null) {
            $position = (int) $max + 1;
        }

        //Association de la piste a la playlist
        $requete = $this->pdo->prepare(
            'INSERT INTO playlist2track (id_pl, id_track, no_piste_dans_liste) VALUES (?, ?, ?)'
        );
        $requete->execute([$idPlaylist, $idPiste, $position]);
    }

    //Recup de tt les playlists enregistrées
    public function getPlaylists(): array
    {
        $sql = 'SELECT id, nom FROM playlist ORDER BY id';

        $resultat = $this->pdo->query($sql);

        $resultat->setFetchMode(PDO::FETCH_ASSOC);

        $playlists = [];

        while ($ligne = $resultat->fetch()) {
            $playlists[] = $ligne;
        }

        $resultat->closeCursor();

        return $playlists;
    }
}
