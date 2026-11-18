<?php

namespace iutnc\deefy\Action;

use iutnc\deefy\Repository\DeefyRepository;

class CurrentPlaylistAction extends ActionAbs
{
    public function execute(): string
    {
        //On vérifie si une playlist est enregistrée en session
        if (!isset($_SESSION['playlist_id'])) {
            return '<h1>Playlist courante</h1>
                    <p>Aucune playlist sélectionnée.</p>
                    <p><a href="index.php?action=list-playlists">Choisir une playlist</a></p>';
        }

        //On récupère l'identifiant de la playlist courante
        $id = filter_var(
            $_SESSION['playlist_id'],
            FILTER_VALIDATE_INT
        );

        if ($id === false || $id === null || $id <= 0) {
            unset($_SESSION['playlist_id']);
            return '<p>Identifiant de playlist invalide.</p>';
        }

        //On récupère la playlist depuis la BD
        $repository = new DeefyRepository();
        $playlist = $repository->getPlaylistById($id);

        //On vérifie que la playlist existe toujours
        if ($playlist === null) {
            unset($_SESSION['playlist_id']);
            http_response_code(404);
            return '<p>La playlist courante n\'existe plus.</p>';
        }

        //On récupère les pistes de la playlist
        $pistes = $repository->getTracksByPlaylist($id);

        $nom = filter_var(
            $playlist['nom'],
            FILTER_SANITIZE_SPECIAL_CHARS
        );

        $html = '<h1>Playlist courante : ' . $nom . '</h1>';

        //On affiche les pistes
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