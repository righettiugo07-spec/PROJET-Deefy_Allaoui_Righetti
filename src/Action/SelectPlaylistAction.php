<?php

namespace iutnc\deefy\Action;

use iutnc\deefy\Repository\DeefyRepository;

class SelectPlaylistAction extends ActionAbs
{
    public function execute(): string
    {
        //Récupération et validation de l'identifiant
        $id = filter_var(
            $_GET['id'] ?? null,
            FILTER_VALIDATE_INT
        );

        if ($id === false || $id === null || $id <= 0) {
            http_response_code(400);
            return '<p>Identifiant de playlist invalide.</p>';
        }

        //On recherche de la playlist dans la base
        $repository = new DeefyRepository();
        $playlist = $repository->getPlaylistById($id);

        if ($playlist === null) {
            http_response_code(404);
            return '<p>Playlist introuvable.</p>';
        }

        //On enregistre la playlist courante
        $_SESSION['playlist_id'] = (int) $playlist['id'];

        //Protection contre les injections HTML
        $nom = filter_var(
            $playlist['nom'],
            FILTER_SANITIZE_SPECIAL_CHARS
        );

        $html = '<h1>' . $nom . '</h1>';
        $html .= '<p>Playlist sélectionnée.</p>';
        $html .= '<p><a href="index.php?action=list-playlists">Retour aux playlists</a></p>';

        return $html;
    }
}
