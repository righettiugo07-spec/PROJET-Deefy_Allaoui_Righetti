<?php

namespace iutnc\deefy\Action;

class DefaultAction extends ActionAbs
{
    public function execute(): string
    {
        return '<h1>Bienvenue sur Deefy !</h1>'
            . '<p><a href="index.php?action=create-playlist">Créer une playlist</a></p>';
    }
}