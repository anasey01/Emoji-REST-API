<?php
/**
 * @author   Temitope Olotin <temitope.olotin@andela.com>
 * @license  <https://opensource.org/license/MIT> MIT
 */
namespace Laztopaz\EmojiRestfulAPI;

require 'vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
class SlimRouteApp
{
    protected $auth;
    protected $emoji;
    protected $slimApp;

    public function __construct(Oauth $auth, EmojiController $emoji)
    {
        $this->auth = $auth;
        $this->emoji = $emoji;
        $this->slimApp = new \Slim\App([
            'settings' => [
            'debug'               => true,
            'displayErrorDetails' => true,
            ], ]);

        $this->runEmojiRoute();
    }

    public function setUpSlimApp()
    {
        return $this->slimApp;
    }

    public function runEmojiRoute()
    {
        $auth = $this->auth;
        $emoji = $this->emoji;

       /*
        * This verb returns error 404
        *
        * @param $request
        *
        * @param $response
        *
        * @return json $response
        *
        */
        $this->slimApp->get('/', function (Request $request, Response $response) {
            return $response->withStatus(404);

        });

        /*
        * This verb returns error 404
        *
        * @param $request
        *
        * @param $response
        *
        * @return json $response
        *
        */
        $this->slimApp->post('/', function (Request $request, Response $response) {
            return $response->withStatus(404);

        });

        /*
        * This endpoint authenticate the user
        *
        * @param $request
        *
        * @param $response
        *
        * @return json $response
        *
        */
        $this->slimApp->post('/auth/login', function (Request $request, Response $response) use ($auth) {
            return $auth->loginUser($request, $response);

        });

        /*
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

        $this->slimApp->get('/auth/logout', function (Request $request, Response $response, $args) use ($auth) {
            return $auth->logoutUser($request, $response, $args)->withStatus(200);

        })->add(new Middleware());

        /*
        * This verb returns all emoji
        *
        * @param $request
        *
        * @param $response
        *
        * @return json $response
        *
        */
        $this->slimApp->get('/emojis', function (Request $request, Response $response) use ($emoji) {
            return $emoji->listAllEmoji($response);

        });

        /*
        * This verb returns a single emoji
        *
        * @param $response
        *
        * @param $args
        *
        * @return json $response
        *
        */
        $this->slimApp->get('/emojis/{id}', function (Request $request, Response $response, $args) use ($emoji) {
            return $emoji->getSingleEmoji($response, $args);

        });

        /*
        * This verb creates a new  emoji
        *
        * @param $request
        *
        * @param $response
        *
        * @return json $response
        *
        */
        $this->slimApp->post('/emojis', function (Request $request, Response $response) use ($emoji) {
            return $emoji->createEmoji($request, $response);

        })->add(new Middleware());

        /*
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
        $this->slimApp->put('/emojis/{id}', function (Request $request, Response $response, $args) use ($emoji) {
            return $emoji->updateEmojiByPutVerb($request, $response, $args);

        })->add(new Middleware());

        /*
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
        $this->slimApp->patch('/emojis/{id}', function (Request $request, Response $response, $args) use ($emoji) {
            return $emoji->updateEmojiByPatchVerb($request, $response, $args);

        })->add(new Middleware());

        /*
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
        $this->slimApp->delete('/emojis/{id}', function (Request $request, Response $response, $args) use ($emoji) {
            return $emoji->deleteEmoji($request, $response, $args);

        })->add(new Middleware());
    }
}
