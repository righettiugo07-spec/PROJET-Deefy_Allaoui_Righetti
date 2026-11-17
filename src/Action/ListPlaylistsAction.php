<?php

namespace iutnc\deefy\Action;

use iutnc\deefy\Repository\DeefyRepository;

class ListPlaylistsAction extends ActionAbs
{
    public function execute(): string
    {
        //On recup les playlists dans la BD
        $repository = new DeefyRepository();
        $playlists = $repository->getPlaylists();

        $html = '<h1>Mes playlists</h1>';

        if (empty($playlists)) {
            return $html . '<p>Aucune playlist disponible.</p>';
        }

        $html .= '<ul>';

        foreach ($playlists as $playlist) {

            //Protection contre les injections HTML
            $nom = filter_var(
                $playlist['nom'],
                FILTER_SANITIZE_SPECIAL_CHARS
            );

            //Id de la playlist
            $id = (int) $playlist['id'];

            //Lien qui permet de sélectionner une playlist
            $html .= '<li><a href="index.php?action=select-playlist&amp;id='
                   . $id . '">' . $nom . '</a></li>';
        }

        $html .= '</ul>';

        return $html;
    }
}
