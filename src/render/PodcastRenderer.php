<?php

namespace iutnc\deefy\render;

class PodcastRenderer extends AudioTrackRenderer
{
    protected function renderCompact(): string
    {
        $p = $this->piste;
        return "<p>{$p->titre} - {$p->auteur}</p>\n" . $this->audio();
    }

    protected function renderLong(): string
    {
        $p = $this->piste;
        $duree = self::dureeEnTexte($p->duree);
        return "<h2>{$p->titre}</h2>\n<p>Auteur : {$p->auteur}</p>\n<p>Date : {$p->date}</p>\n"
            . "<p>Genre : {$p->genre}</p>\n<p>Durée : $duree</p>\n" . $this->audio();
    }
}
