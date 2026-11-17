<?php

session_start();

require_once __DIR__ . '/vendor/autoload.php';

use iutnc\deefy\Action\DefaultAction;
use iutnc\deefy\Dispatch\Dispatcher;
use iutnc\deefy\Exception\RouterException;

$dispatcher = new Dispatcher();

$dispatcher->addRoute('default', DefaultAction::class);

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
        </nav>
    </header>

    <main>
        <?= $contenu ?>
    </main>

</body>
</html>