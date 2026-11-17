<?php

namespace iutnc\deefy\Action;

use iutnc\deefy\Repository\DeefyRepository;

class SelectPlaylistAction extends ActionAbs
{
    public function execute(): string {
        //Récupération et validation de l'identifiant
        $id = filter_var(
            $_GET['id'] ?? null,
            FILTER_VALIDATE_INT
        );

        if ($id === false || $id === null || $id <= 0) {
            http_response_code(400);
            return '<p>Identifiant de playlist invalide.</p>';
        }

        //On recherche la playlist dans la base
        $repository = new DeefyRepository();
        $playlist = $repository->getPlaylistById($id);

        if ($playlist === null) {
            http_response_code(404);
            return '<p>Playlist introuvable.</p>';
        }

        //On enregistre la playlist courante
        $_SESSION['playlist_id'] = (int) $playlist['id'];

        //On récupère les pistes de la playlist
        $pistes = $repository->getTracksByPlaylist($id);

        $nom = filter_var(
            $playlist['nom'],
            FILTER_SANITIZE_SPECIAL_CHARS
        );

        $html = '<h1>' . $nom . '</h1>';
        $html .= '<p>Playlist sélectionnée.</p>';

        //On affiche les pistes de la playlist
        if (empty($pistes)) {
            $html .= '<p>Cette playlist ne contient aucune piste.</p>';
        } else {
            $html .= '<h2>Liste des pistes</h2>';
            $html .= '<ol>';

            foreach ($pistes as $piste) {

                $titre = filter_var(
                    $piste['titre'],
                    FILTER_SANITIZE_SPECIAL_CHARS
                );

                $genre = filter_var(
                    $piste['genre'] ?? '',
                    FILTER_SANITIZE_SPECIAL_CHARS
                );

                $html .= '<li>' . $titre;

                if ($genre !== '') {
                    $html .= ' - ' . $genre;
                }

                $html .= '</li>';
            }

            $html .= '</ol>';
        }

        $html .= '<p><a href="index.php?action=list-playlists">Retour aux playlists</a></p>';

        return $html;
    }
}
