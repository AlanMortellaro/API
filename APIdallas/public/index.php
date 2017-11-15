<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

session_start();

require_once('inc/connexion.inc.php');


require '../vendor/autoload.php';

$app = new \Slim\App();


// Recherche personne de la base de donnée via le nom
$app->get('/nom/{name}', function (Request $request, Response $response) {

    $name = $request->getAttribute('name');

    $cnn = getConnexion('apidallas');
    $res = $cnn->prepare('SELECT * FROM personne LEFT JOIN clef ON personne.id = clef.id_personne WHERE nom LIKE :name;');
    $res->bindValue(':name', $name);
    $res->execute();
    $res = $res->fetchAll(PDO::FETCH_ASSOC);

    $jsonPerson = json_encode($res);
    $response->getBody()->write($jsonPerson);

    return $response->withHeader('Content-Type', 'application/json');
    return $response;
});

// Recherche personne de la base de donnée via le prenom
$app->get('/prenom/{firstname}', function (Request $request, Response $response) {

    $FirstName = $request->getAttribute('firstname');

    $cnn = getConnexion('apidallas');
    $res = $cnn->prepare('SELECT * FROM personne LEFT JOIN clef ON personne.id = clef.id_personne WHERE prenom LIKE :firstname;');
    $res->bindValue(':firstname', $FirstName);
    $res->execute();
    $res = $res->fetchAll(PDO::FETCH_ASSOC);

    $jsonPerson = json_encode($res);
    $response->getBody()->write($jsonPerson);

    return $response->withHeader('Content-Type', 'application/json');
    return $response;
});

//retourne tous users
$app->get('/users', function (Request $request, Response $response) {

    $cnn = getConnexion('apidallas');
    $res = $cnn->prepare('SELECT * FROM tbl_users LEFT JOIN user_key on user_key.id_user = tbl_users.id LEFT JOIN tbl_keys on user_key.id_key = tbl_keys.id;');
    $res->execute();
    $res = $res->fetchAll(PDO::FETCH_ASSOC);

    $jsonPerson = json_encode($res);
    $response->getBody()->write($jsonPerson);

    return $response->withHeader('Content-Type', 'application/json');
    return $response;
});

//retourne l'utilisateur donc via son id
$app->get('/user/{id}', function (Request $request, Response $response) {

    $id = $request->getAttribute('id');

    $cnn = getConnexion('apidallas');
    $res = $cnn->prepare('SELECT * FROM tbl_users LEFT JOIN user_key on user_key.id_user = tbl_users.id LEFT JOIN tbl_keys on user_key.id_key = tbl_keys.id WHERE tbl_users.id = :id');
    $res->bindValue(':id', $id);
    $res->execute();
    $res = $res->fetchAll(PDO::FETCH_ASSOC);

    $jsonPerson = json_encode($res);
    $response->getBody()->write($jsonPerson);

    return $response->withHeader('Content-Type', 'application/json');
    return $response;
});

$app->post("/user/create", function ($request, $response) {
    $data = $request->getParsedBody();
    if($data == '')
    {
      $data = 'connerie';
    }

    return $response->withHeader('Content-Type', 'application/json');
    return $this->response->withJson($data);
});

$app->post("/key/create", function ($request, $response) {
    $data = $request->getParsedBody();
    if($data == '')
    {
      $data = 'connerie';
    }

    return $response->withHeader('Content-Type', 'application/json');
    return $this->response->withJson($data);
});

$app->put('/user/[{id}]', function ($request, $response, $args) {

      $id = $request->getAttribute('id');

      $cnn = getConnexion('apidallas');
      $res = $cnn->prepare('UPDATE personne SET nom=\'teeeeeeest\' WHERE id LIKE :id');
      $res->bindValue(':id', $id);
      $res->execute();
      $res = $res->fetchAll(PDO::FETCH_ASSOC);

      $response->getBody()->write("{name: 'teeeeeeest' WHERE id LIKE '$id'}");

      return $response->withHeader('Content-Type', 'application/json');
      return $response;
    });


$app->run();


if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';

session_start();

// Instantiate the app
$settings = require __DIR__ . '/../src/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
require __DIR__ . '/../src/dependencies.php';

// Register middleware
require __DIR__ . '/../src/middleware.php';

// Register routes
require __DIR__ . '/../src/routes.php';

// Run app
$app->run();
?>
