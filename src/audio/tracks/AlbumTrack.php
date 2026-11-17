<?php

namespace iutnc\deefy\audio\tracks;

class AlbumTrack extends AudioTrack
{
    protected string $artiste = '';
    protected string $album;
    protected int $annee = 0;
    protected int $numero;

    public function __construct(string $titre, string $nomfichier, string $album, int $numero)
    {
        parent::__construct($titre, $nomfichier);
        $this->album = $album;
        $this->numero = $numero;
    }

    public function setArtiste(string $artiste): void
    {
        $this->artiste = $artiste;
    }

    public function setAnnee(int $annee): void
    {
        $this->annee = $annee;
    }
}
