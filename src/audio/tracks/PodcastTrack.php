<?php

namespace iutnc\deefy\audio\tracks;

class PodcastTrack extends AudioTrack
{
    protected string $auteur = '';
    protected string $date = '';

    public function setAuteur(string $auteur): void
    {
        $this->auteur = $auteur;
    }

    public function setDate(string $date): void
    {
        $this->date = $date;
    }
}
