<?php

namespace iutnc\deefy\Action;

use iutnc\deefy\Repository\DeefyRepository;

//F4 : creation d'une playlist vide, qui devient la playlist courante
class CreatePlaylistAction extends ActionAbs
{
    public function execute(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->traiter();
        }

        return $this->formulaire('', '');
    }

    private function formulaire(string $erreur, string $nom): string
    {
        $message = '';
        if ($erreur !== '') {
            $message = '<p>' . $erreur . '</p>';
        }

        //Protection contre les injections HTML
        $nom = filter_var($nom, FILTER_SANITIZE_SPECIAL_CHARS);

        return <<<HTML
        <h1>Créer une playlist</h1>
        $message
        <form method="post" action="index.php?action=create-playlist">
            <p>
                <label for="nom">Nom de la playlist</label>
                <input type="text" id="nom" name="nom" maxlength="100" value="$nom" required>
            </p>
            <p><button type="submit">Créer</button></p>
        </form>
        HTML;
    }

    private function traiter(): string
    {
        $nom = '';
        if (isset($_POST['nom']) && is_string($_POST['nom'])) {
            $nom = $_POST['nom'];
        }

        //Validation : le nom est conserve tel quel dans la base (taille de la colonne : 100)
        if ($nom === '') {
            return $this->formulaire('Le nom de la playlist est obligatoire.', $nom);
        }
        if (strlen($nom) > 100) {
            return $this->formulaire('Le nom ne doit pas dépasser 100 caractères.', $nom);
        }

        try {
            $repository = new DeefyRepository();
            $id = $repository->creerPlaylist($nom);
        } catch (\Exception $e) {
            return '<h1>Erreur</h1><p>La base de données est indisponible.</p>';
        }

        //La nouvelle playlist devient la playlist courante
        $_SESSION['playlist_id'] = $id;

        //Protection contre les injections HTML a l'affichage
        $nomAffiche = filter_var($nom, FILTER_SANITIZE_SPECIAL_CHARS);

        return '<h1>Playlist créée</h1>'
            . '<p>La playlist « ' . $nomAffiche . ' » est créée. Elle est maintenant la playlist courante.</p>'
            . '<p><a href="index.php?action=add-track">Ajouter une piste</a></p>'
            . '<p><a href="index.php?action=list-playlists">Voir mes playlists</a></p>';
    }
}
