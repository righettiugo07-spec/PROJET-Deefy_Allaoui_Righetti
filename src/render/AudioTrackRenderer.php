<?php

namespace iutnc\deefy\render;

use iutnc\deefy\audio\tracks\AudioTrack;

// Patron "template de methode" : render() choisit le mode, les sous-classes le produisent
abstract class AudioTrackRenderer implements Renderer
{
    protected AudioTrack $piste;

    public function __construct(AudioTrack $piste)
    {
        $this->piste = $piste;
    }

    public static function dureeEnTexte(int $secondes): string
    {
        $minutes = (int) ($secondes / 60);
        $reste = $secondes % 60;
        return $minutes . ' min ' . $reste . ' s';
    }

    public function render(int $selector): string
    {
        if ($selector === Renderer::LONG) {
            return $this->renderLong();
        }
        return $this->renderCompact();
    }

    protected function audio(): string
    {
        return "<audio controls src=\"{$this->piste->nomfichier}\"></audio>";
    }

    abstract protected function renderCompact(): string;

    abstract protected function renderLong(): string;
}
