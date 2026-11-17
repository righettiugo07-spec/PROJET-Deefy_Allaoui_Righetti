<?php

namespace iutnc\deefy\audio\lists;

use iutnc\deefy\audio\tracks\AudioTrack;

class Playlist extends AudioList
{
    public function ajouterPiste(AudioTrack $piste): void
    {
        parent::ajouterPiste($piste);
    }

    // Supprime la piste d'indice donne (les indices suivants sont decales)
    public function supprimerPiste(int $indice): void
    {
        $restantes = [];
        $position = 0;
        foreach ($this->pistes as $piste) {
            if ($position === $indice) {
                $this->nbpistes = $this->nbpistes - 1;
                $this->dureetotale = $this->dureetotale - $piste->duree;
            } else {
                $restantes[] = $piste;
            }
            $position = $position + 1;
        }
        $this->pistes = $restantes;
    }

    // Ajoute plusieurs pistes ; une piste dont le fichier est deja present est ignoree
    public function ajouterListePistes(array $pistes): void
    {
        foreach ($pistes as $nouvelle) {
            $presente = false;
            foreach ($this->pistes as $existante) {
                if ($existante->nomfichier === $nouvelle->nomfichier) {
                    $presente = true;
                }
            }
            if (!$presente) {
                $this->ajouterPiste($nouvelle);
            }
        }
    }
}
