<?php

namespace iutnc\deefy\Action;

class ListPlaylistsAction extends ActionAbs
{
    public function execute(): string
    {
        $html = '<h1>Mes playlists</h1>';
        $html .= '<p>Liste des playlists enregistrées.</p>';

        return $html;
    }
}