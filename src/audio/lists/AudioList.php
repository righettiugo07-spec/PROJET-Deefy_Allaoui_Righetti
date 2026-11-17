<?php

namespace iutnc\deefy\audio\lists;

use iutnc\deefy\audio\tracks\AudioTrack;
use iutnc\deefy\Exception\InvalidPropertyNameException;

class AudioList
{
    protected ?int $id = null;
    protected string $nom;
    protected int $nbpistes = 0;
    protected int $dureetotale = 0;
    protected array $pistes = [];

    public function __construct(string $nom, array $pistes = [])
    {
        $this->nom = $nom;
        foreach ($pistes as $piste) {
            $this->ajouterPiste($piste);
        }
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

    // Ajoute la piste et met a jour le nombre de pistes et la duree totale
    protected function ajouterPiste(AudioTrack $piste): void
    {
        $this->pistes[] = $piste;
        $this->nbpistes = $this->nbpistes + 1;
        $this->dureetotale = $this->dureetotale + $piste->duree;
    }
}
