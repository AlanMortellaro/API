<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

//=========================================GET=======================================================

//retourne tous les utilisateurs
$app->get('/api/v2/users', function (Request $request, Response $response) {

    $cnn = getConnexion();
    $res = $cnn->prepare('SELECT * FROM tbl_user');
    $res->execute();

    $res = $res->fetchAll(PDO::FETCH_ASSOC);

    $jsonPerson = json_encode($res);
    $response->getBody()->write($jsonPerson);

    return $response->withHeader('Content-Type', 'application/json');
});

//retourne tous les utilisateurs activé
$app->get('/api/v2/users/active', function (Request $request, Response $response) {

    $cnn = getConnexion();
    $res = $cnn->prepare('SELECT * FROM tbl_user WHERE tbl_user.active = 1');
    $res->execute();

    $res = $res->fetchAll(PDO::FETCH_ASSOC);

    $jsonPerson = json_encode($res);
    $response->getBody()->write($jsonPerson);

    return $response->withHeader('Content-Type', 'application/json');
});

//retourne tous les utilisateurs desactivé
$app->get('/api/v2/users/desactive', function (Request $request, Response $response) {

    $cnn = getConnexion();
    $res = $cnn->prepare('SELECT * FROM tbl_user WHERE tbl_user.active = 0');
    $res->execute();

    $res = $res->fetchAll(PDO::FETCH_ASSOC);

    $jsonPerson = json_encode($res);
    $response->getBody()->write($jsonPerson);

    return $response->withHeader('Content-Type', 'application/json');
});

//retourne toutes les clefs
$app->get('/api/v2/keys', function (Request $request, Response $response) {

    $cnn = getConnexion();
    $res = $cnn->prepare('SELECT * FROM tbl_key');
    $res->execute();
    $res = $res->fetchAll(PDO::FETCH_ASSOC);

    $jsonPerson = json_encode($res);
    $response->getBody()->write($jsonPerson);

    return $response->withHeader('Content-Type', 'application/json');
});

//retourne une clef selon l'id de la clef
$app->get('/api/v2/key/{id}', function (Request $request, Response $response) {

    $id = $request->getAttribute('id');

    $cnn = getConnexion();
    $res = $cnn->prepare('SELECT * FROM tbl_key WHERE tbl_key.id = :id');
    $res->bindParam(':id', $id);
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
        'error' => 'T',
        'message' => 'Verifiez si l\'id de la clef est correct'
      );

      $response->write(json_encode($jsonRes));
    }
    return $response->withHeader('Content-Type', 'application/json');
});

//retourne un utilisateur selon l'id de l'utilisateur
$app->get('/api/v2/user/{id}', function (Request $request, Response $response) {

    $id = $request->getAttribute('id');

    $cnn = getConnexion();
    $res = $cnn->prepare('SELECT * FROM tbl_user WHERE tbl_user.id = :id');
    $res->bindParam(':id', $id);
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
        'error' => 'T',
        'message' => 'Verifiez si l\'id de l\'utilisateur est correct'
      );

      $response->write(json_encode($jsonRes));
    }

    return $response->withHeader('Content-Type', 'application/json');
});

//retourne les clefs d'un utilisateur via son id
$app->get('/api/v2/user/key/{id}', function (Request $request, Response $response) {

    $id = $request->getAttribute('id');

    $cnn = getConnexion();
    $res = $cnn->prepare('SELECT tbl_key.id, UID FROM tbl_key LEFT JOIN tbl_user ON tbl_key.id_user = tbl_user.id WHERE tbl_user.id = :id;');
    $res->bindParam(':id', $id);
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
        'error' => 'T',
        'message' => 'Verifiez si l\'id de l\'utilisateur est correct et verifiez si l\'utilisateur en question est bien associé a une clef'
      );

      $response->write(json_encode($jsonRes));
    }

    return $response->withHeader('Content-Type', 'application/json');
});

// retourne les informations de toutes les commandes effectuer
$app->get('/api/v2/orders', function (Request $request, Response $response) {

    $cnn = getConnexion();
    $res = $cnn->prepare('SELECT tbl_user.id AS id_user, firstname, lastname, tbl_order.id AS id_order, tbl_article.name FROM `tbl_order` LEFT JOIN tbl_user ON tbl_order.id_user = tbl_user.id LEFT JOIN content ON tbl_order.id = content.id_order LEFT JOIN tbl_article ON content.id_article = tbl_article.id');
    $res->execute();
    $res = $res->fetchAll(PDO::FETCH_ASSOC);

    $jsonPerson = json_encode($res);
    $response->getBody()->write($jsonPerson);

    return $response->withHeader('Content-Type', 'application/json');
});

// retourne les informations de la commande via l'id de l'utilisateur
$app->get('/api/v2/order/user/{id}', function (Request $request, Response $response) {

    $id = $request->getAttribute('id');

    $cnn = getConnexion();
    $res = $cnn->prepare('SELECT firstname, lastname, tbl_order.id AS id_order, tbl_article.name, tbl_article.price FROM `tbl_order` LEFT JOIN tbl_user ON tbl_order.id_user = tbl_user.id LEFT JOIN content ON tbl_order.id = content.id_order LEFT JOIN tbl_article ON content.id_article = tbl_article.id WHERE tbl_user.id = :id');
    $res->bindParam(':id', $id);
    $res->execute();
    $res = $res->fetchAll(PDO::FETCH_ASSOC);

    $jsonPerson = json_encode($res);
    $response->getBody()->write($jsonPerson);

    return $response->withHeader('Content-Type', 'application/json');
});

// retourne les informations d'une commande
$app->get('/api/v2/order/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');

    $cnn = getConnexion();
    $res = $cnn->prepare('SELECT tbl_user.id AS id_user, firstname, lastname, tbl_article.name AS name_article, tbl_article.price FROM `tbl_order` LEFT JOIN tbl_user ON tbl_order.id_user = tbl_user.id LEFT JOIN content ON tbl_order.id = content.id_order LEFT JOIN tbl_article ON content.id_article = tbl_article.id WHERE tbl_order.id = :id');
    $res->bindParam(':id', $id);
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
        'error' => 'T',
        'message' => 'Verifiez si l\'id de la commande est correct'
      );

      $response->write(json_encode($jsonRes));
    }

    return $response->withHeader('Content-Type', 'application/json');
});

// retourne les informations des articles
$app->get('/api/v2/articles', function (Request $request, Response $response) {

    $cnn = getConnexion();
    $res = $cnn->prepare('SELECT * FROM tbl_article');
    $res->execute();
    $res = $res->fetchAll(PDO::FETCH_ASSOC);

    $jsonPerson = json_encode($res);
    $response->getBody()->write($jsonPerson);

    return $response->withHeader('Content-Type', 'application/json');
});

// retourne les informations des articles activés
$app->get('/api/v2/articles/active', function (Request $request, Response $response) {

    $cnn = getConnexion();
    $res = $cnn->prepare('SELECT * FROM tbl_article WHERE tbl_article.active = 1');
    $res->execute();
    $res = $res->fetchAll(PDO::FETCH_ASSOC);

    $jsonPerson = json_encode($res);
    $response->getBody()->write($jsonPerson);

    return $response->withHeader('Content-Type', 'application/json');
});

// retourne les informations des articles desactivés
$app->get('/api/v2/articles/desactive', function (Request $request, Response $response) {

    $cnn = getConnexion();
    $res = $cnn->prepare('SELECT * FROM tbl_article WHERE tbl_article.active = 0');
    $res->execute();
    $res = $res->fetchAll(PDO::FETCH_ASSOC);

    $jsonPerson = json_encode($res);
    $response->getBody()->write($jsonPerson);

    return $response->withHeader('Content-Type', 'application/json');
});


// retourne les informations d'une commande
$app->get('/api/v2/article/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');

    $cnn = getConnexion();
    $res = $cnn->prepare('SELECT * FROM tbl_article WHERE id = :id');
    $res->bindParam(':id', $id);
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
        'error' => 'T',
        'message' => 'Verifiez si l\'id de l\'article est correct'
      );

      $response->write(json_encode($jsonRes));
    }

    return $response->withHeader('Content-Type', 'application/json');
});

//====================================================================================================





//=========================================POST======================================================
// Create token
$app->post('/api/v2/token', function ($request, $response, $args) {

        $data = $request->getParsedBody();

        if(array_key_exists('lastname', $_POST) && array_key_exists('password', $_POST))
        {
          $cnn = getConnexion();
          $res = $cnn->prepare('SELECT * FROM tbl_user WHERE lastname LIKE :lastname');
          $res->bindParam(':lastname', $data['lastname']);
          $res->execute();
          $res = $res->fetchAll(PDO::FETCH_ASSOC);
          $id = $res[0]['id'];

          if(md5($data['password']) == md5($res[0]['password']))
          {
            $arrRtn['token'] = bin2hex(openssl_random_pseudo_bytes(16)); //generate a random token

            $tokenExpiration = date('Y-m-d H:i:s', strtotime('+1 hour'));//the expiration date will be in one hour from the current moment

            $response->getBody()->write(json_encode($arrRtn));

            $cnn = getConnexion();
            $res = $cnn->prepare('UPDATE tbl_user SET token = :token WHERE id = :id');
            $res->bindParam(':id', $id);
            $res->bindParam(':token', $arrRtn['token']);
            $res->execute();

            $data['error'] = 'F';
            $data['msg'] = 'Token pour l\'utilisateur ' . $data['lastname'] . ' créé';
            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json');
          }
          else
          {
            $data['error'] = 'T';
            $data['msgError'] = 'lastname ou password incorrect';
            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json');
          }

          $data['error'] = 'F';
          $response->getBody()->write(json_encode($data));
          return $response->withHeader('Content-Type', 'application/json');
        }
      });

// Create user in database
$app->post("/api/v2/user", function ($request, $response) {

    $data = $request->getParsedBody();

    if(array_key_exists('lastname', $_POST) && array_key_exists('firstname', $_POST))
    {
      if($_POST['lastname'] !== '' AND $_POST['firstname'] !== '')
      {
        $data['error'] = 'F';
        //array_push($data, 'error', 'F');
        $cnn = getConnexion();
        $res = $cnn->prepare('INSERT INTO tbl_user(firstname, lastname, email, credit) VALUES ( :firstname, :lastname, :email, :credit)');
        $res->bindParam(':firstname', $data['firstname']);
        $res->bindParam(':lastname', $data['lastname']);
        $res->bindParam(':email', $data['email']);
        $res->bindParam(':credit', $data['credit']);
        $res->execute();

        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
      }
      else
      {
          $data['error'] = 'T';
          $data['msgError'] = 'Veuillez renseigner un nom et un prénom';
          $response->getBody()->write(json_encode($data));
          return $response->withHeader('Content-Type', 'application/json');
      }
    }
    else
    {
      $data['error'] = 'T';
      $data['msgError'] = 'Veuillez déclarer $_POST[\'name\'] et $_POST[\'firstname\']';
      $response->getBody()->write(json_encode($data));
      return $response->withHeader('Content-Type', 'application/json');
    }
});

// Reactive an user in database
$app->post('/api/v2/user/[{id}]', function ($request, $response, $args) {

        $id = $request->getAttribute('id');

        $cnn = getConnexion();
        $res = $cnn->prepare('UPDATE tbl_user SET active = 1 WHERE id = :id');
        $res->bindParam(':id', $id);
        $res->execute();

        $data['error'] = 'F';
        $data['msg'] = 'L\'utilisateur numéro ' . $id . ' a bien été ré-activer';
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');

      });

// Create key in database
$app->post('/api/v2/key', function ($request, $response) {

      $data = $request->getParsedBody();

      if(array_key_exists('UID', $_POST))
      {
        if($_POST['UID'] !== '')
        {
            $data['error'] = 'F';
            $cnn = getConnexion();
            $res = $cnn->prepare('INSERT INTO tbl_key(UID, id_user) VALUES ( :uid, :id_user)');
            $res->bindParam(':uid', $data['UID']);
            $res->bindParam(':id_user', $data['id_user']);
            $res->execute();

            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json');
        }
        else
        {
            $data['error'] = 'T';
            $data['msgError'] = 'Veuillez renseigner l\'UID de la clef';
            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json');
        }
    }
    else
    {
      $data['error'] = 'T';
      $data['msgError'] = 'Veuillez déclarer $_POST[\'UID\']';
      $response->getBody()->write(json_encode($data));
      return $response->withHeader('Content-Type', 'application/json');
    }
});

// Create article in database
$app->post('/api/v2/article', function ($request, $response) {

      $data = $request->getParsedBody();

      if(array_key_exists('name', $_POST) && array_key_exists('price', $_POST))
      {
        if($_POST['name'] !== '' && $_POST['price'] !== '')
        {
            $data['error'] = 'F';
            $cnn = getConnexion();
            $res = $cnn->prepare('INSERT INTO tbl_article(name, price) VALUES ( :name, :price)');
            $res->bindParam(':name', $data['name']);
            $res->bindParam(':price', $data['price']);
            $res->execute();

            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json');
        }
        else
        {
            $data['error'] = 'T';
            $data['msgError'] = 'Veuillez renseigner le nom de la clef et son prix';
            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json');
        }
    }
    else
    {
      $data['error'] = 'T';
      $data['msgError'] = 'Veuillez déclarer $_POST[\'name\'] et $_POST[\'price\']';
      $response->getBody()->write(json_encode($data));
      return $response->withHeader('Content-Type', 'application/json');
    }
});

// Active an article in database
$app->post('/api/v2/article/[{id}]', function ($request, $response, $args) {

        $id = $request->getAttribute('id');

        $cnn = getConnexion();
        $res = $cnn->prepare('UPDATE tbl_article SET active = 1 WHERE id = :id');
        $res->bindParam(':id', $id);
        $res->execute();

        $data['error'] = 'F';
        $data['msg'] = 'L\'article numéro ' . $id . ' a bien été ré-activer';
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');

      });

// Create buy article
$app->post('/api/v2/buy', function ($request, $response) {

  $id = $request->getAttribute('id');
  $data = $request->getParsedBody();

  if(array_key_exists('id_user', $_POST) && array_key_exists('quantitiy', $_POST) && array_key_exists('id_order', $_POST) && array_key_exists('id_article', $_POST))
  {
    if($_POST['id_user'] !== '' && $_POST['quantitiy'] !== '' && $_POST['id_order'] !== '' && $_POST['id_article'] !== '')
    {
      $data['error'] = 'F';

      $cnn = getConnexion();
      $res = $cnn->prepare('INSERT INTO tbl_order(id_user) VALUES (:id_user)');
      $res->bindParam(':id_user', $data['id_user']);
      $res->execute();

      $cnn = getConnexion();
      $res = $cnn->prepare('INSERT INTO content(quantitiy, id_order, id_article) VALUES (:quantitiy, :id_order, :id_article)');
      $res->bindParam(':quantitiy', $data['quantitiy']);
      $res->bindParam(':id_order', $data['id_order']);
      $res->bindParam(':id_article', $data['id_article']);
      $res->execute();

      $response->getBody()->write(json_encode($data));
      return $response->withHeader('Content-Type', 'application/json');
    }
    else
    {
      $data['error'] = 'T';
      $data['msgError'] = 'Veuillez renseigner les champs suivant : $_POST[\'id_user\'], $_POST[\'quantitiy\'], $_POST[\'id_order\'], $_POST[\'id_article\']';
      $response->getBody()->write(json_encode($data));
      return $response->withHeader('Content-Type', 'application/json');
    }
  }
  else
  {
    $data['error'] = 'T';
    $data['msgError'] = 'Veuillez déclarer et renseigner les champs suivant : $_POST[\'id_user\'], $_POST[\'quantitiy\'], $_POST[\'id_order\'], $_POST[\'id_article\']';
    $response->getBody()->write(json_encode($data));
    return $response->withHeader('Content-Type', 'application/json');
  }

});

// Attribute a key for an user
$app->post('/api/v2/link/key/[{id}]', function ($request, $response, $args) {

      $id = $request->getAttribute('id');
      $data = $request->getParsedBody();

      if(array_key_exists('id_user', $data))
      {
        if($data['id_user'] !== '')
        {
            $cnn = getConnexion();
            $res = $cnn->prepare('UPDATE `tbl_key` SET `id_user`= :id_user WHERE id = :id');
            $res->bindParam(':id_user', $data['id_user'], PDO::PARAM_INT);
            $res->bindParam(':id', $id, PDO::PARAM_INT);
            $res->execute();

            $data['error'] = 'F';
            $data['msg'] = 'Vous avez attribué la clef numéro ' . $id . ' à l\'utilisateur numéro ' . $data['id_user'];
            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json');
        }
        else
        {
            $data['error'] = 'T';
            $data['msgError'] = 'Veuillez renseigner le paramètre "id_user" (NULL vous voulez enlever l\'attribut de cette clé)';
            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json');
        }
      }
      else
      {
          $response->getBody()->write('Veuillez déclarer le paramètre "id_user"');
      }

    });

//====================================================================================================





//=========================================PUT========================================================

// Update user
$app->put('/api/v2/user/[{id}]', function ($request, $response, $args) {

      $data = $request->getParsedBody();
      $id = $request->getAttribute('id');

      if(array_key_exists('lastname', $data) AND array_key_exists('firstname', $data) AND array_key_exists('email', $data) AND array_key_exists('credit', $data))
      {
            $cnn = getConnexion();
            $res = $cnn->prepare('UPDATE tbl_user SET firstname=:firstname,lastname=:lastname,email=:email, credit=:credit WHERE id = :id');
            $res->bindParam(':firstname', $data['firstname']);
            $res->bindParam(':lastname', $data['lastname']);
            $res->bindParam(':email', $data['email']);
            $res->bindParam(':credit', $data['credit']);
            $res->bindParam(':id', $id);
            $res->execute();

            $data['error'] = 'F';
            $data['msg'] = 'Votre requête a bien abouti';
            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json');
      }
      else
      {
        $data['error'] = 'T';
        $data['msgError'] = 'Veuillez déclarer et renseigner "$_POST[\'lastname\']" ET "$_POST[\'firstname\']" ET "$_POST[\'email\']" ET "$_POST[\'credit\']"';
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
      }
    });

// Update article
$app->put('/api/v2/article/[{id}]', function ($request, $response, $args) {

      $data = $request->getParsedBody();
      $id = $request->getAttribute('id');

      if(array_key_exists('name', $data) AND array_key_exists('price', $data))
      {
            $cnn = getConnexion();
            $res = $cnn->prepare('UPDATE `tbl_article` SET `name`= :name,`price`= :price WHERE id = :id');
            $res->bindParam(':name', $data['name']);
            $res->bindParam(':price', $data['price']);
            $res->bindParam(':id', $id);
            $res->execute();

            $data['error'] = 'F';
            $data['msg'] = 'Votre requête a bien abouti';
            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json');
      }
      else
      {
        $data['error'] = 'T';
        $data['msgError'] = 'Veuillez déclarer et renseigner "$_POST[\'name\']" ET "$_POST[\'price\']"';
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
      }
    });

//====================================================================================================





//========================================DELETE=======================================================

// Delete(desactive) an user in database
$app->delete('/api/v2/user/[{id}]', function ($request, $response, $args) {

        $id = $request->getAttribute('id');

        $cnn = getConnexion();
        $res = $cnn->prepare('SELECT * FROM tbl_key WHERE id_user = :id');
        $res->bindParam(':id', $id);
        $res->execute();
        $res = $res->fetchAll(PDO::FETCH_ASSOC);

        if(empty($res))
        {
            $res = $cnn->prepare('UPDATE tbl_user SET active = 0 WHERE id = :id');
            $res->bindParam(':id', $id);
            $res->execute();

            $data['error'] = 'F';
            $data['msg'] = 'L\'utilisateur numéro ' . $id . ' a bien été désactiver';
            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json');
        }
        else
        {
            $data['error'] = 'T';
            $data['msgError'] = 'Une clef est assigné a cet utilisateur veuillez d\'abord désassigner la clef de cet utilisateur';
            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json');
        }
      });

// Delete a key in database
$app->delete('/api/v2/key/[{id}]', function ($request, $response, $args) {

        $id = $request->getAttribute('id');

        $cnn = getConnexion();
        $res = $cnn->prepare('DELETE FROM tbl_key WHERE tbl_key.id LIKE :id');
        $res->bindParam(':id', $id);
        $res->execute();

        $data['error'] = 'F';
        $data['msg'] = 'La clé numéro ' . $id . ' a bien été supprimé';
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    });

// Delete(desactive) an article in database
$app->delete('/api/v2/article/[{id}]', function ($request, $response, $args) {

        $id = $request->getAttribute('id');

        $cnn = getConnexion();
        $res = $cnn->prepare('UPDATE tbl_article SET active = 0 WHERE id = :id');
        $res->bindParam(':id', $id);
        $res->execute();

        $data['error'] = 'F';
        $data['msg'] = 'L\'article numéro ' . $id . ' a bien été désactiver';
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');

      });

// Desattribute a key for an user
$app->delete('/api/v2/unlink/key/[{id}]', function ($request, $response, $args) {

          $id = $request->getAttribute('id');

          $data = $request->getParsedBody();

          $cnn = getConnexion('apidallas');
          $res = $cnn->prepare('UPDATE tbl_key SET id_user = NULL WHERE tbl_key.id = :id');
          $res->bindParam(':id', $id, PDO::PARAM_INT);
          $res->execute();


          $data['error'] = 'F';
          $data['id_key'] = $id;
          $data['msg'] = 'Vous avez enlever l\'atrribution de la clé numero ' . $id;
          $response->getBody()->write(json_encode($data));
          return $response->withHeader('Content-Type', 'application/json');
        });
//====================================================================================================
?>
