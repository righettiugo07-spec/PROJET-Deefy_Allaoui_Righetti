<?php

namespace iutnc\deefy\Repository;

use PDO;

class DeefyRepository
{
    private PDO $pdo;

    public function __construct()
    {
        //On recup les info de connexion
        $configFile = __DIR__ . '/../../config.db.ini';

        if (!is_file($configFile)) {
            throw new \RuntimeException('Fichier de configuration introuvable');
        }

        $config = parse_ini_file($configFile);

        if ($config === false) {
            throw new \RuntimeException('Erreur de lecture de la configuration');
        }

        //Connexion a la BD
        $dsn = $config['driver']
             . ':host=' . $config['host']
             . ';dbname=' . $config['database']
             . ';charset=utf8mb4';

        $this->pdo = new PDO(
            $dsn,
            $config['username'],
            $config['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
    }

    //Recup ttes les playlists enregistre
    public function getPlaylists(): array
    {
        $sql = 'SELECT id, nom FROM playlist ORDER BY id';

        $resultat = $this->pdo->query($sql);

        return $resultat->fetchAll();
    }
}