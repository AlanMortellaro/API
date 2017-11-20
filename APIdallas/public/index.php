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

// Create user in database
$app->post("/create/user", function ($request, $response) {

    $data = $request->getParsedBody();

    if(array_key_exists('name', $_POST) && array_key_exists('firstname', $_POST))
    {
      if($_POST['name'] !== '' AND $_POST['firstname'] !== '')
      {
        $cnn = getConnexion('apidallas');
        $res = $cnn->prepare('INSERT INTO tbl_users(firstname, name) VALUES (:firstname,:name);');
        $res->bindValue(':firstname', $data['firstname']);
        $res->bindValue(':name', $data['name']);
        $res->execute();

        return $response->withHeader('Content-Type', 'application/json');
      }
      else
      {
          $response->getBody()->write('Veuillez renseigner un nom et un prénom');
      }
    }
    else
    {
      $response->getBody()->write('Veuillez déclarer "$_POST[\'name\']" et "$_POST[\'firstname\']"');
    }
});


// Create key in database
$app->post("/create/key", function ($request, $response) {

      $data = $request->getParsedBody();
      var_dump($_POST);
      if(array_key_exists('UID', $_POST))
      {
        if($_POST['UID'] !== '')
        {
          $cnn = getConnexion('apidallas');
          if(array_key_exists('id_user', $_POST))
          {
            $res = $cnn->prepare('INSERT INTO tbl_keys(UID, id_users) VALUES (:uid,:id_user);');
            $res->bindValue(':uid', $data['UID']);
            $res->bindValue(':id_user', $data['id_user']);
          }
          else
          {
            $res = $cnn->prepare('INSERT INTO tbl_keys(UID) VALUES (:uid);');
            $res->bindValue(':uid', $data['UID']);
          }
          $res->execute();

          return $response->withHeader('Content-Type', 'application/json');
        }
        else
        {
            $response->getBody()->write('Veuillez renseigner l\'UID de la clef');
        }
    }
    else
    {
      $response->getBody()->write('Veuillez déclarer "$_POST[\'UID\']"');
    }
});

//====================================================================================================




//=========================================PUT========================================================


// Attribute a key for an user
$app->put('/attribute/key/[{id}]', function ($request, $response, $args) {

      $id = $request->getAttribute('id');
      $data = $request->getParsedBody();
      var_dump($data);
      var_dump($id);
      if(array_key_exists('id_user', $data))
      {
        if($data['id_user'] !== '')
        {
            $cnn = getConnexion('apidallas');
            $res = $cnn->prepare('UPDATE tbl_keys SET id_users = :id_user WHERE tbl_keys.id = :id');
            $res->bindParam(':id_user', $data['id_user'], PDO::PARAM_INT);
            $res->bindParam(':id', $id, PDO::PARAM_INT);
            $res->execute();
            return $response->withHeader('Content-Type', 'application/json');
        }
        else
        {
            $response->getBody()->write('Veuillez renseigner le paramètre "id_user" (NULL)');
        }
      }
      else
      {
          $response->getBody()->write('Veuillez déclarer le paramètre "id_user"');
      }



    });


// Desattribute a key for an user
    $app->put('/desattribute/key/[{id}]', function ($request, $response, $args) {

          $id = $request->getAttribute('id');
          $data = $request->getParsedBody();
          var_dump($data);
          var_dump($id);

          $cnn = getConnexion('apidallas');
          $res = $cnn->prepare('UPDATE tbl_keys SET id_users = NULL WHERE tbl_keys.id = :id');
          $res->bindParam(':id', $id, PDO::PARAM_INT);
          $res->execute();

          return $response->withHeader('Content-Type', 'application/json');




        });

// Update user
$app->put('/edit/user/[{id}]', function ($request, $response, $args) {

      $data = $request->getParsedBody();
      $id = $request->getAttribute('id');

      var_dump($data);

      if(array_key_exists('name', $data) && array_key_exists('firstname', $data))
      {
        if($data['name'] !== '' && $data['firstname'] !== '')
        {
            $cnn = getConnexion('apidallas');
            $res = $cnn->prepare('UPDATE tbl_users SET name = :name, firstname = :firstname WHERE id LIKE :id');
            $res->bindParam(':name', $data['name']);
            $res->bindParam(':firstname', $data['firstname']);
            $res->bindParam(':id', $id);
            $res->execute();
        }
        else
        {
          $response->getBody()->write('Veuillez renseigner "$data[\'name\']" et "$data[\'firstname\']" ');
        }
      }
      else
      {
        $response->getBody()->write('Veuillez passer en paramètre "name" et "firstname" ');
      }
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
