# Deefy — Mini-projet PHP

Projet réalisé en binôme dans le cadre du BUT Informatique à l'IUT Nancy-Charlemagne. Deefy permet de consulter des playlists, d'en créer et d'y ajouter des pistes audio.

 **Application sur Webetu :** [Accéder à Deefy](https://webetu.iutnc.univ-lorraine.fr/www/e71221u/deefy/index.php)

## Tableau de bord des fonctionnalités

| N° | Fonctionnalité | Réalisée par | État |
|---|---|---|---|
| F1 | Afficher les playlists enregistrées | Righetti | Développée |
| F2 | Sélectionner une playlist, afficher ses pistes et la stocker en session | Righetti | Développée |
| F3 | Ajouter une piste à la playlist courante | Allaoui | Développée |
| F4 | Créer une playlist vide et la rendre courante | Allaoui | Développée |
| F5 | Afficher la playlist courante | Righetti | Développée |

Les fonctionnalités d'inscription et de connexion (F6 et F7) sont retirées du sujet adapté.

## Répartition du travail

- **Righetti :** mise en place initiale du projet, fonctionnalités F1, F2 et F5, configuration `.htaccess`, CSS, intégrations Git, déploiement sur `webetu` et README.
- **Allaoui :** fonctionnalités F3 et F4, classes liées aux pistes audio et gestion de l'envoi des fichiers MP3.

## Installation et tests

1. Importer `sql/deefy.sql` dans une base MySQL/MariaDB nommée `deefy`.
2. Copier `config.db.example.ini` en `config.db.ini`, puis renseigner les paramètres de connexion à la base. Ce dernier fichier n'est pas envoyé sur Git.
3. Exécuter `composer install` à la racine du projet.
4. Avec XAMPP, placer le projet dans `htdocs`, démarrer Apache et MySQL, puis ouvrir `http://localhost/PROJET-Deefy_Allaoui_Righetti/`.

Aucun identifiant de connexion n'est nécessaire : l'authentification ne fait pas partie des fonctionnalités demandées.