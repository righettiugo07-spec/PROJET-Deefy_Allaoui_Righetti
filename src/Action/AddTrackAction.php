<?php

namespace iutnc\deefy\Action;

use iutnc\deefy\audio\tracks\PodcastTrack;
use iutnc\deefy\Repository\DeefyRepository;

//F3 : ajout d'une piste (podcast) a la playlist courante
class AddTrackAction extends ActionAbs
{
    public function execute(): string
    {
        //La playlist courante est identifiee par $_SESSION['playlist_id']
        if (!isset($_SESSION['playlist_id'])) {
            return $this->aucunePlaylist();
        }
        $idPlaylist = (int) $_SESSION['playlist_id'];

        try {
            $repository = new DeefyRepository();
            if (!$repository->playlistExiste($idPlaylist)) {
                return $this->aucunePlaylist();
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                return $this->traiter($repository, $idPlaylist);
            }
        } catch (\Exception $e) {
            return '<h1>Erreur</h1><p>La base de données est indisponible.</p>';
        }

        return $this->formulaire('', []);
    }

    private function aucunePlaylist(): string
    {
        return '<h1>Ajouter une piste</h1>'
            . '<p>Aucune playlist courante : choisissez-en une dans '
            . '<a href="index.php?action=list-playlists">Mes playlists</a> ou '
            . '<a href="index.php?action=create-playlist">créez-en une</a>.</p>';
    }

    //$valeurs : ce qui a deja ete saisi, pour ne pas tout retaper apres une erreur
    private function formulaire(string $erreur, array $valeurs): string
    {
        $message = '';
        if ($erreur !== '') {
            $message = '<p>' . $erreur . '</p>';
        }

        //Protection contre les injections HTML
        $titre = filter_var($valeurs['titre'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $auteur = filter_var($valeurs['auteur'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $genre = filter_var($valeurs['genre'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);

        //La date et la duree ne sont reaffichees que si elles sont valides (sinon le HTML serait invalide)
        $date = '';
        if ($this->dateValide($valeurs['date'] ?? '')) {
            $date = $valeurs['date'];
        }
        $duree = '';
        if ((string) (int) ($valeurs['duree'] ?? '') === ($valeurs['duree'] ?? '')) {
            $duree = $valeurs['duree'];
        }

        return <<<HTML
        <h1>Ajouter une piste à la playlist courante</h1>
        $message
        <form method="post" action="index.php?action=add-track" enctype="multipart/form-data">
            <p>
                <label for="titre">Titre</label>
                <input type="text" id="titre" name="titre" maxlength="100" value="$titre" required>
            </p>
            <p>
                <label for="auteur">Auteur</label>
                <input type="text" id="auteur" name="auteur" maxlength="100" value="$auteur" required>
            </p>
            <p>
                <label for="date">Date</label>
                <input type="date" id="date" name="date" value="$date" required>
            </p>
            <p>
                <label for="genre">Genre</label>
                <input type="text" id="genre" name="genre" maxlength="30" value="$genre" required>
            </p>
            <p>
                <label for="duree">Durée (en secondes)</label>
                <input type="number" id="duree" name="duree" min="0" value="$duree" required>
            </p>
            <p>
                <label for="userfile">Fichier audio (mp3)</label>
                <input type="file" id="userfile" name="userfile" accept="audio/mpeg" required>
            </p>
            <p><button type="submit">Ajouter</button></p>
        </form>
        HTML;
    }

    private function champ(string $nom): string
    {
        if (isset($_POST[$nom]) && is_string($_POST[$nom])) {
            return $_POST[$nom];
        }

        return '';
    }

    private function traiter(DeefyRepository $repository, int $idPlaylist): string
    {
        $valeurs = [
            'titre' => $this->champ('titre'),
            'auteur' => $this->champ('auteur'),
            'date' => $this->champ('date'),
            'genre' => $this->champ('genre'),
            'duree' => $this->champ('duree')
        ];

        //Validation : les valeurs sont conservees telles quelles dans la base
        //(tailles des colonnes : titre 100, auteur 100, genre 30)
        if ($valeurs['titre'] === '' || $valeurs['auteur'] === '' || $valeurs['date'] === ''
            || $valeurs['genre'] === '' || $valeurs['duree'] === '') {
            return $this->formulaire('Tous les champs sont obligatoires.', $valeurs);
        }
        if (strlen($valeurs['titre']) > 100 || strlen($valeurs['auteur']) > 100) {
            return $this->formulaire('Le titre et l\'auteur ne doivent pas dépasser 100 caractères.', $valeurs);
        }
        if (strlen($valeurs['genre']) > 30) {
            return $this->formulaire('Le genre ne doit pas dépasser 30 caractères.', $valeurs);
        }
        if ((string) (int) $valeurs['duree'] !== $valeurs['duree'] || (int) $valeurs['duree'] < 0) {
            return $this->formulaire('La durée doit être un nombre entier de secondes, positif ou nul.', $valeurs);
        }
        if (!$this->dateValide($valeurs['date'])) {
            return $this->formulaire('La date doit être au format AAAA-MM-JJ.', $valeurs);
        }
        if (!isset($_FILES['userfile']) || $_FILES['userfile']['error'] !== UPLOAD_ERR_OK) {
            return $this->formulaire('Le fichier audio est obligatoire.', $valeurs);
        }
        if (substr($_FILES['userfile']['name'], -4) !== '.mp3' || $_FILES['userfile']['type'] !== 'audio/mpeg') {
            return $this->formulaire('Le fichier doit être un fichier audio mp3.', $valeurs);
        }

        //Le nom d'origine du fichier n'est jamais utilise : nom aleatoire
        $nomFichier = uniqid() . '.mp3';
        if (!move_uploaded_file($_FILES['userfile']['tmp_name'], __DIR__ . '/../../audio/' . $nomFichier)) {
            return $this->formulaire('Le fichier n\'a pas pu être enregistré.', $valeurs);
        }

        $piste = new PodcastTrack($valeurs['titre'], $nomFichier);
        $piste->setAuteur($valeurs['auteur']);
        $piste->setDate($valeurs['date']);
        $piste->setGenre($valeurs['genre']);
        $piste->setDuree((int) $valeurs['duree']);

        $repository->ajouterPiste($idPlaylist, $piste);

        //Protection contre les injections HTML a l'affichage
        $titreAffiche = filter_var($valeurs['titre'], FILTER_SANITIZE_SPECIAL_CHARS);

        return '<h1>Piste ajoutée</h1>'
            . '<p>La piste « ' . $titreAffiche . ' » est ajoutée à la playlist courante.</p>'
            . '<p><a href="index.php?action=add-track">Ajouter une autre piste</a></p>'
            . '<p><a href="index.php?action=list-playlists">Voir mes playlists</a></p>';
    }

    //Format AAAA-MM-JJ et date qui existe
    private function dateValide(string $date): bool
    {
        $parties = explode('-', $date);
        if (!isset($parties[2]) || isset($parties[3]) || strlen($date) !== 10) {
            return false;
        }

        return checkdate((int) $parties[1], (int) $parties[2], (int) $parties[0]);
    }
}
