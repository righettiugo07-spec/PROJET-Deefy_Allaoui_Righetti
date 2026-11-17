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

    //On récupère une playlist avec son id
    public function getPlaylistById(int $id): ?array {
        $sql = 'SELECT id, nom FROM playlist WHERE id = :id';

        //Requête préparée contre les injections SQL
        $resultat = $this->pdo->prepare($sql);

        $resultat->bindParam(':id', $id, PDO::PARAM_INT);

        $resultat->execute();

        $playlist = $resultat->fetch(PDO::FETCH_ASSOC);

        $resultat->closeCursor();

        if ($playlist === false) {
            return null;
        }

        return $playlist;
    }

    //On récupère les pistes d'une playlist
    public function getTracksByPlaylist(int $id): array {
        $sql = 'SELECT t.id, t.titre, t.genre, t.duree, t.type
                FROM track t
                JOIN playlist2track pt ON t.id = pt.id_track
                WHERE pt.id_pl = :id
                ORDER BY pt.no_piste_dans_liste';

        $resultat = $this->pdo->prepare($sql);

        $resultat->bindParam(':id', $id, PDO::PARAM_INT);

        $resultat->execute();

        $resultat->setFetchMode(PDO::FETCH_ASSOC);

        $pistes = [];

        while ($ligne = $resultat->fetch()) {
            $pistes[] = $ligne;
        }

        $resultat->closeCursor();

        return $pistes;
    }
}
