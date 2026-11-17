<?php

namespace iutnc\deefy\Action;

use iutnc\deefy\Repository\DeefyRepository;

class ListPlaylistsAction extends ActionAbs
{
    public function execute(): string
    {
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

            $html .= '<li>' . $nom . '</li>';
        }

        $html .= '</ul>';

        return $html;
    }
}