<?php
/**
 * @author   Temitope Olotin <temitope.olotin@andela.com>
 * @license  <https://opensource.org/license/MIT> MIT
 */

require 'vendor/autoload.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

use Laztopaz\EmojiRestfulAPI\DatabaseConnection;
use Laztopaz\EmojiRestfulAPI\EmojiController;
use Laztopaz\EmojiRestfulAPI\OauthLogin;

$app = new Slim\App([
    'settings' => [
     'debug' => true, 
     'displayErrorDetails' => true
    ]
]);

$capsule = new Capsule; 

new DatabaseConnection($capsule);

$emoji = new EmojiController();

$login = new OauthLogin();

/**
 * This verb returns error 404
 *
 * @param $request
 *
 * @param $response
 *
 * @return json $response
 *
 */
$app->get('/', function (Request $request, Response $response) {
    return $response->withJson(['status'], 404);

});

/**
 * This verb returns error 404
 *
 * @param $request
 *
 * @param $response
 *
 * @return json $response
 *
 */
$app->post('/', function (Request $request, Response $response) {
    return $response->withJson(['status'], 404);

});

/**
 * This endpoint authenticate the user
 *
 * @param $request
 *
 * @param $response
 *
 * @return json $response
 *
 */
$app->post('/auth/login', function (Request $request, Response $response) use ($login) {
    return $login->authenticateUser($request, $response); 

});

/**
 * This endpoint authenticate the user
 *
 * @param $request
 *
 * @param $response
 *
 * @param $args
 *
 * @return json $response
 *
 */
$app->get('/auth/logout', function (Request $request, Response $response, $args) use ($login) {
    return $login->deAuthenticateUser($request, $response, $args);

});

/**
 * This verb returns all emoji
 *
 * @param $request
 *
 * @param $response
 *
 * @return json $response
 *
 */
$app->get('/emojis', function (Request $request, Response $response, $args ) use ($emoji) {
    return $emoji->listAllEmoji($response);

});

/**
 * This verb returns a single emoji
 *
 * @param $response
 *
 * @param $args
 *
 * @return json $response
 *
 */
$app->get('/emojis/{id}', function (Request $request, Response $response, $args) use ($emoji) {
    return  $emoji->getSingleEmoji($response, $args);

});

/**
 * This verb creates a new  emoji
 *
 * @param $request
 *
 * @param $response
 *
 * @return json $response
 *
 */
$app->post('/emojis', function (Request $request, Response $response) use ($emoji) {
    return $emoji->createEmoji($request, $response);

});

/**
 * This verb updatess an emoji using put verb
 *
 * @param $request
 *
 * @param $response
 *
 * @param $args
 *
 * @param $emoji
 *
 * @return json $response
 *
 */
$app->put('/emojis/{id}', function (Request $request, Response $response, $args) use ($emoji) {
    return $emoji->updateEmojiByPutVerb($request, $response, $args);

});

/**
 * This verb updatess an emoji using put verb
 *
 * @param $request
 *
 * @param $response
 *
 * @param $data
 *
 * @return json $response
 *
 */
$app->patch('/emojis/{id}', function (Request $request, Response $response, $args) use ($emoji) {
    return $emoji->updateEmojiByPatchVerb($request, $response, $args);

});

/**
 * This verb updatess an emoji using put verb
 *
 * @param $request
 *
 * @param $response
 *
 * @param $args
 *
 * @return json $response
 *
 */
$app->delete('/emojis/{id}', function (Request $request, Response $response, $args) use ( $emoji ) {
    return $emoji->deleteEmoji($request, $response, $args);

});

$app->run();
