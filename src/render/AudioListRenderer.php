<?php

namespace iutnc\deefy\render;

use iutnc\deefy\audio\lists\AudioList;
use iutnc\deefy\audio\tracks\AlbumTrack;

// Meme affichage pour les albums et les playlists : le selecteur n'est pas utilise
class AudioListRenderer implements Renderer
{
    private AudioList $liste;

    public function __construct(AudioList $liste)
    {
        $this->liste = $liste;
    }

    public function render(int $selector): string
    {
        $html = "<h1>{$this->liste->nom}</h1>\n";

        if ($this->liste->nbpistes === 0) {
            return $html . "<p>Cette playlist est vide.</p>\n";
        }

        $html .= "<ol>\n";
        foreach ($this->liste->pistes as $piste) {
            if ($piste instanceof AlbumTrack) {
                $renderer = new AlbumTrackRenderer($piste);
            } else {
                $renderer = new PodcastRenderer($piste);
            }
            $html .= "<li>\n" . $renderer->render(Renderer::COMPACT) . "\n</li>\n";
        }
        $html .= "</ol>\n";

        return $html . "<p>{$this->liste->nbpistes} piste(s) - durée totale : "
            . AudioTrackRenderer::dureeEnTexte($this->liste->dureetotale) . "</p>\n";
    }
}
