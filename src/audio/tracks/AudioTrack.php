<?php

namespace iutnc\deefy\audio\tracks;

use iutnc\deefy\Exception\InvalidPropertyNameException;
use iutnc\deefy\Exception\InvalidPropertyValueException;

class AudioTrack
{
    protected ?int $id = null;
    protected string $titre;
    protected string $genre = '';
    protected int $duree = 0;
    protected string $nomfichier;

    public function __construct(string $titre, string $nomfichier)
    {
        $this->titre = $titre;
        $this->nomfichier = $nomfichier;
    }

    public function __get(string $attribut): mixed
    {
        if (property_exists($this, $attribut)) {
            return $this->$attribut;
        }
        throw new InvalidPropertyNameException("invalid property : $attribut");
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setGenre(string $genre): void
    {
        $this->genre = $genre;
    }

    public function setDuree(int $duree): void
    {
        if ($duree < 0) {
            throw new InvalidPropertyValueException("invalid value for duree : $duree");
        }
        $this->duree = $duree;
    }
}
