<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once('inc/connexion.inc.php');


require '../vendor/autoload.php';

$app = new \Slim\App();

//=========================================GET=======================================================

//retourne tous users
$app->get('/v2/all', function (Request $request, Response $response) {

    $cnn = getConnexion('apidallas');
    $res = $cnn->prepare('SELECT tbl_keys.id AS id_keys, UID, tbl_users.id AS id_user, firstname, name FROM `tbl_users` LEFT JOIN tbl_keys ON tbl_users.id = tbl_keys.id_user;');
    $res->execute();
    $res = $res->fetchAll(PDO::FETCH_ASSOC);

    $jsonPerson = json_encode($res);
    $response->getBody()->write($jsonPerson);

    return $response->withHeader('Content-Type', 'application/json');
});

//retourne tous les utilisateurs
$app->get('/v2/users', function (Request $request, Response $response) {

    $cnn = getConnexion('apidallas');
    $res = $cnn->prepare('SELECT * FROM tbl_users;');
    $res->execute();

    $res = $res->fetchAll(PDO::FETCH_ASSOC);

    $jsonPerson = json_encode($res);
    $response->getBody()->write($jsonPerson);

    return $response->withHeader('Content-Type', 'application/json');
});

//retourne toutes les clefs
$app->get('/v2/keys', function (Request $request, Response $response) {

    $cnn = getConnexion('apidallas');
    $res = $cnn->prepare('SELECT * FROM tbl_keys;');
    $res->execute();
    $res = $res->fetchAll(PDO::FETCH_ASSOC);

    $jsonPerson = json_encode($res);
    $response->getBody()->write($jsonPerson);

    return $response->withHeader('Content-Type', 'application/json');
});

//retourne une clef selon l'id de la clef
$app->get('/v2/key/{id}', function (Request $request, Response $response) {

    $id = $request->getAttribute('id');

    $cnn = getConnexion('apidallas');
    $res = $cnn->prepare('SELECT * FROM tbl_keys WHERE tbl_keys.id = :id;');
    $res->bindValue(':id', $id);
    $res->execute();
    $res = $res->fetchAll(PDO::FETCH_ASSOC);

    if(array_key_exists('0', $res))
    {
      $jsonPerson = json_encode($res);
      $response->write($jsonPerson);
    }
    else
    {
      $jsonRes = array
      (
        'error' => 'V',
        'message' => 'La clé demandé n\'existe pas'
      );

      $response->write(json_encode($jsonRes));
    }
    return $response->withHeader('Content-Type', 'application/json');
});

//retourne un utilisateur selon l'id de l'utilisateur
$app->get('/v2/user/{id}', function (Request $request, Response $response) {

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
$app->get('/v2/key/user/{id}', function (Request $request, Response $response) {

    $id = $request->getAttribute('id');

    $cnn = getConnexion('apidallas');
    $res = $cnn->prepare('SELECT tbl_keys.id, UID FROM tbl_keys LEFT JOIN tbl_users ON tbl_keys.id_user = tbl_users.id WHERE tbl_users.id = :id;');
    $res->bindValue(':id', $id);
    $res->execute();
    $res = $res->fetchAll(PDO::FETCH_ASSOC);

    $jsonPerson = json_encode($res);
    $response->getBody()->write($jsonPerson);

    return $response->withHeader('Content-Type', 'application/json');
});

//test token
$app->get('/v2/debug', function (Request $request, Response $response) {
    $arrRtn['token'] = bin2hex(openssl_random_pseudo_bytes(16)); //generate a random token

    $tokenExpiration = date('Y-m-d H:i:s', strtotime('+1 hour'));//the expiration date will be in one hour from the current moment

    $response->getBody()->write(json_encode($arrRtn));

    return $response->withHeader('Content-Type', 'application/json');
});

//====================================================================================================




//=========================================POST======================================================

// Create user in database
$app->post("/v2/user", function ($request, $response) {

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
$app->post("/v2/key", function ($request, $response) {

      $data = $request->getParsedBody();

      if(array_key_exists('UID', $_POST))
      {
        if($_POST['UID'] !== '')
        {
          $cnn = getConnexion('apidallas');
          if(array_key_exists('id_user', $_POST))
          {
            $res = $cnn->prepare('INSERT INTO tbl_keys(UID, id_user) VALUES (:uid,:id_user);');
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
$app->put('/v2/key/[{id}]', function ($request, $response, $args) {

      $id = $request->getAttribute('id');
      $data = $request->getParsedBody();

      if(array_key_exists('id_user', $data))
      {
        if($data['id_user'] !== '')
        {
            $cnn = getConnexion('apidallas');
            $res = $cnn->prepare('UPDATE tbl_keys SET id_user = :id_user WHERE tbl_keys.id = :id');
            $res->bindParam(':id_user', $data['id_user'], PDO::PARAM_INT);
            $res->bindParam(':id', $id, PDO::PARAM_INT);
            $res->execute();
            $response->getBody()->write("Vous avez attribué la clef " . $id . "à l'utilisateur numéro " . $data['id_user']);
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
$app->put('/v2/desattribute/key/[{id}]', function ($request, $response, $args) {

          $id = $request->getAttribute('id');
          $data = $request->getParsedBody();

          $cnn = getConnexion('apidallas');
          $res = $cnn->prepare('UPDATE tbl_keys SET id_user = NULL WHERE tbl_keys.id = :id');
          $res->bindParam(':id', $id, PDO::PARAM_INT);
          $res->execute();

          $response->getBody()->write("Vous avez enlevé l'attribution de la clef numéro " . $id . " à l'utilisateur numéro " . $data);
          return $response->withHeader('Content-Type', 'application/json');




        });

// Update user
$app->put('/v2/user/[{id}]', function ($request, $response, $args) {

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

// Delete an user in database
$app->delete('/v2/user/[{id}]', function ($request, $response, $args) {

        $id = $request->getAttribute('id');

        $cnn = getConnexion('apidallas');
        $res = $cnn->prepare('SELECT * FROM tbl_keys WHERE id_user = :id');
        $res->bindParam(':id', $id);
        $res->execute();
        $res = $res->fetchAll(PDO::FETCH_ASSOC);

        if(empty($res))
        {
            $res = $cnn->prepare('DELETE FROM tbl_users WHERE tbl_users.id LIKE :id');
            $res->bindParam(':id', $id);
            $res->execute();
        }
        else
        {
            $response->getBody()->write('Une clef est assigné a cet utilisateur veuillez d\'abord désassigner la clef de cet utilisateur <br><br>');
            $jsonPerson = json_encode($res);
            $response->getBody()->write($jsonPerson);
        }
    });

// Delete a key in database
$app->delete('/v2/key/[{id}]', function ($request, $response, $args) {

        $id = $request->getAttribute('id');

        $cnn = getConnexion('apidallas');
        $res = $cnn->prepare('DELETE FROM tbl_keys WHERE tbl_keys.id LIKE :id');
        $res->bindParam(':id', $id);
        $res->execute();
    });

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
