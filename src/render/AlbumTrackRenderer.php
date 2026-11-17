<?php

namespace iutnc\deefy\render;

class AlbumTrackRenderer extends AudioTrackRenderer
{
    protected function renderCompact(): string
    {
        $p = $this->piste;
        return "<p>{$p->titre} - {$p->artiste} ({$p->album})</p>\n" . $this->audio();
    }

    protected function renderLong(): string
    {
        $p = $this->piste;
        $duree = self::dureeEnTexte($p->duree);
        return "<h2>{$p->titre}</h2>\n<p>Artiste : {$p->artiste}</p>\n<p>Album : {$p->album} ({$p->annee}), piste n° {$p->numero}</p>\n"
            . "<p>Genre : {$p->genre}</p>\n<p>Durée : $duree</p>\n" . $this->audio();
    }
}
