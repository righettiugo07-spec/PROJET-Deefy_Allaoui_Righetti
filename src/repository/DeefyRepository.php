<?php

namespace iutnc\deefy\repository;

use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\audio\tracks\AlbumTrack;
use iutnc\deefy\audio\tracks\AudioTrack;
use iutnc\deefy\audio\tracks\PodcastTrack;
use iutnc\deefy\Exception\DatabaseConnectionException;
use iutnc\deefy\Exception\DataNotFoundException;

/**
 * Toutes les requetes SQL de l'application (modele Repository + Singleton).
 * Utilisation : DeefyRepository::getInstance()->findPlaylistById($id);
 * Toutes les requetes sont preparees (parade contre l'injection SQL).
 */
class DeefyRepository
{
    private \PDO $pdo;
    private static ?DeefyRepository $instance = null;
    private static array $config = [];

    private function __construct(array $conf)
    {
        try {
            $this->pdo = new \PDO($conf['dsn'], $conf['user'], $conf['pass'],
                [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
        } catch (\PDOException $e) {
            throw new DatabaseConnectionException("Connexion à la base de données impossible.");
        }
        $this->pdo->prepare('SET NAMES \'UTF8\'')->execute();
    }

    public static function getInstance(): DeefyRepository
    {
        if (is_null(self::$instance)) {
            if (self::$config === []) {
                // fichier de configuration a la racine du projet (jamais dans Git)
                self::setConfig(__DIR__ . '/../../config.db.ini');
            }
            self::$instance = new DeefyRepository(self::$config);
        }
        return self::$instance;
    }

    public static function setConfig(string $file): void
    {
        if (!is_file($file)) {
            throw new DatabaseConnectionException("Fichier de configuration de la base introuvable.");
        }
        $conf = parse_ini_file($file);
        if ($conf === false) {
            throw new DatabaseConnectionException("Fichier de configuration de la base illisible.");
        }
        self::$config = [
            'dsn' => $conf['driver'] . ':host=' . $conf['host'] . ';dbname=' . $conf['database'],
            'user' => $conf['username'],
            'pass' => $conf['password']
        ];
    }

    // ---------- Lecture (F1, F2) ----------

    // ---------- Creation (F3, F4) ----------
}
