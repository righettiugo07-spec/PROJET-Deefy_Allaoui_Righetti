<?php

session_start();

require_once __DIR__ . '/vendor/autoload.php';

use iutnc\deefy\Action\DefaultAction;
use iutnc\deefy\Action\ListPlaylistsAction;
use iutnc\deefy\Dispatch\Dispatcher;
use iutnc\deefy\Exception\RouterException;
use iutnc\deefy\Action\SelectPlaylistAction;
use iutnc\deefy\Action\CreatePlaylistAction;
use iutnc\deefy\Action\AddTrackAction;

//Creation du dispatcher
$dispatcher = new Dispatcher();

//Declaration des routes
$dispatcher->addRoute('default', DefaultAction::class);
$dispatcher->addRoute('list-playlists', ListPlaylistsAction::class);
$dispatcher->addRoute('select-playlist', SelectPlaylistAction::class);

$dispatcher->addRoute('create-playlist', CreatePlaylistAction::class);
$dispatcher->addRoute('add-track', AddTrackAction::class);

try {

    $contenu = $dispatcher->run();

} catch (RouterException $e) {

    http_response_code(404);

    $contenu = '<h1>Erreur 404 : page introuvable</h1>';
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deefy</title>
</head>
<body>

    <header>
        <nav>
            <a href="index.php">Accueil</a>
            <a href="index.php?action=list-playlists">Mes playlists</a>
        </nav>
    </header>

    <main>
        <?= $contenu ?>
    </main>

</body>
</html>