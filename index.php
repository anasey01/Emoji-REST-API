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
use Laztopaz\EmojiRestfulAPI\Schema;

$app = new Slim\App(['settings' => [
    'debug' => true, 
    'displayErrorDetails' => true
]]);

$capsule = new Capsule; 

new DatabaseConnection($capsule);

//new Schema();

$emoji = new EmojiController();

/**
 * This verb returns all emoji
 *
 * @aparams $request
 *
 * @params $response
 *
 * @return json $response
 *
 */

$app->get('/emojis', function ( $request, $response ) use ( $emoji ) {

    $response = $response->withHeader(
        'Content-type',
        'application/json; charset=utf-8'
    );
   
    $response->write($emoji->listAllEmoji());

    return $response;

});

/**
 * This verb returns a single emoji
 *
 * @aparams $request
 *
 * @params $response
 *
 * @params $args
 *
 * @return json $response
 *
 */
$app->get('/emojis/{id}', function ( $request, $response, $args ) use ( $emoji ) {
   
    $response = $response->withHeader(
        'Content-type',
        'application/json; charset=utf-8'
    );
    $response->write( $emoji->getSingleEmoji($args['id']) );

    return $response;

});

/**
 * This verb creates a new  emoji
 *
 * @aparams $request
 *
 * @params $response
 *
 * @params $parsedBody
 *
 * @return json $response
 *
 */
$app->post('/emojis', function ( $request, $response ) use ( $emoji ) {

    $response = $response->withHeader(
        'Content-type',
        'application/json; charset=utf-8'
    );

    $parsedBody = $request->getParsedBody();
   
    $response->write($emoji->createEmoji($parsedBody));

    return $response;

});

/**
 * This verb updatess an emoji using put verb
 *
 * @aparams $request
 *
 * @params $response
 *
 * @params $data
 *
 * @return json $response
 *
 */
$app->put('/emojis/{id}', function ( $request, $response, $args ) use ( $emoji ) {

    $response = $response->withHeader(
        'Content-type',
        'application/json; charset=utf-8'
    );

    $parsedBody = $request->getParsedBody();

    //print_r($parsedBody);
   
    $response->write($emoji->updateEmojiByPutVerb($parsedBody, $args['id']));

    return $response;

});

/**
 * This verb updatess an emoji using put verb
 *
 * @aparams $request
 *
 * @params $response
 *
 * @params $data
 *
 * @return json $response
 *
 */
$app->patch('/emojis/{id}', function ( $request, $response, $args ) use ( $emoji ) {

    $response = $response->withHeader(
        'Content-type',
        'application/json; charset=utf-8'
    );

    $parsedBody = $request->getBody();
   
    $response->write($emoji->updateEmojiByPatchVerb($parsedBody, $args['id']));

    return $response;

});

/**
 * This verb updatess an emoji using put verb
 *
 * @aparams $request
 *
 * @params $response
 *
 * @params $args['id']
 *
 * @return json $response
 *
 */
$app->delete('/emojis/{id}', function ( $request, $response, $args ) use ( $emoji ) {

    $response = $response->withHeader(
        'Content-type',
        'application/json; charset=utf-8'
    );
   
    $response->write($emoji->deleteEmoji($args['id']));

    return $response;

});

$app->run();
