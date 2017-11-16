<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once('inc/connexion.inc.php');


require '../vendor/autoload.php';

$app = new \Slim\App();

//=========================================GET=======================================================

//retourne tous users
$app->get('/all', function (Request $request, Response $response) {

    $cnn = getConnexion('apidallas');
    $res = $cnn->prepare('SELECT tbl_keys.id AS id_keys, UID, tbl_users.id AS id_user, firstname, name FROM `tbl_users` LEFT JOIN tbl_keys ON tbl_users.id = tbl_keys.id_users;');
    $res->execute();
    $res = $res->fetchAll(PDO::FETCH_ASSOC);

    $jsonPerson = json_encode($res);
    $response->getBody()->write($jsonPerson);

    return $response->withHeader('Content-Type', 'application/json');
});

//retourne tous les utilisateurs
$app->get('/users', function (Request $request, Response $response) {

    $id = $request->getAttribute('id');

    $cnn = getConnexion('apidallas');
    $res = $cnn->prepare('SELECT * FROM tbl_users;');
    $res->execute();
    $res = $res->fetchAll(PDO::FETCH_ASSOC);

    $jsonPerson = json_encode($res);
    $response->getBody()->write($jsonPerson);

    return $response->withHeader('Content-Type', 'application/json');
});

//retourne toutes les clefs
$app->get('/keys', function (Request $request, Response $response) {

    $cnn = getConnexion('apidallas');
    $res = $cnn->prepare('SELECT * FROM tbl_keys;');
    $res->execute();
    $res = $res->fetchAll(PDO::FETCH_ASSOC);

    $jsonPerson = json_encode($res);
    $response->getBody()->write($jsonPerson);

    return $response->withHeader('Content-Type', 'application/json');
});

//retourne une clef selon l'id de la clef
$app->get('/key/{id}', function (Request $request, Response $response) {

    $id = $request->getAttribute('id');

    $cnn = getConnexion('apidallas');
    $res = $cnn->prepare('SELECT * FROM tbl_keys WHERE tbl_keys.id = :id;');
    $res->bindValue(':id', $id);
    $res->execute();
    $res = $res->fetchAll(PDO::FETCH_ASSOC);

    $jsonPerson = json_encode($res);
    $response->write($jsonPerson);

    return $response->withHeader('Content-Type', 'application/json');
});

//retourne un utilisateur selon l'id de l'utilisateur
$app->get('/user/{id}', function (Request $request, Response $response) {

    $id = $request->getAttribute('id');

    $cnn = getConnexion('apidallas');
    $res = $cnn->prepare('SELECT * FROM tbl_users WHERE tbl_users.id = :id;');
    $res->bindValue(':id', $id);
    $res->execute();
    $res = $res->fetchAll(PDO::FETCH_ASSOC);

    $jsonPerson = json_encode($res);
    $response->write($jsonPerson);

    return $response->withHeader('Content-Type', 'application/json');
});

//retourne les clefs d'un utilisateur via son id
$app->get('/key/user/{id}', function (Request $request, Response $response) {

    $id = $request->getAttribute('id');

    $cnn = getConnexion('apidallas');
    $res = $cnn->prepare('SELECT tbl_keys.id, UID FROM tbl_keys LEFT JOIN tbl_users ON tbl_keys.id_users = tbl_users.id WHERE tbl_users.id = :id;');
    $res->bindValue(':id', $id);
    $res->execute();
    $res = $res->fetchAll(PDO::FETCH_ASSOC);

    $jsonPerson = json_encode($res);
    $response->getBody()->write($jsonPerson);

    return $response->withHeader('Content-Type', 'application/json');
});

//====================================================================================================




//=========================================POST======================================================

//add user
$app->post("/create/user", function ($request, $response) {

    $data = $request->getParsedBody();

  //  if()
  //  {
      $cnn = getConnexion('apidallas');
      $res = $cnn->prepare('INSERT INTO tbl_users(firstname, name) VALUES (:firstname,:name);');
      $res->bindValue(':firstname', $data['firstname']);
      $res->bindValue(':name', $data['name']);
      $res->execute();

      return $response->withHeader('Content-Type', 'application/json');
  //  }
  //  else
  //  {
  //    echo "Veuillez renseigner le nom et le prénom";
  //  }
});

$app->post("/create/key", function ($request, $response) {
    $data = $request->getParsedBody();
    if($data == '')
    {
      $data = 'connerie';
    }
    return $response->withHeader('Content-Type', 'application/json');
    return $this->response->withJson($data);
});

//====================================================================================================




//=========================================PUT========================================================

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

//====================================================================================================




//========================================DELETE=======================================================



//====================================================================================================

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
// // Instantiate the app
// $settings = require __DIR__ . '/../src/settings.php';
// $app = new \Slim\App($settings);
//
// // Set up dependencies
// require __DIR__ . '/../src/dependencies.php';
//
// // Register middleware
// require __DIR__ . '/../src/middleware.php';
//
// // Register routes
// require __DIR__ . '/../src/routes.php';
//

// Run app
$app->run();
?>
