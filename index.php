<?php
/**
 * @author   Temitope Olotin <temitope.olotin@andela.com>
 * @license  <https://opensource.org/license/MIT> MIT
 */
require 'vendor/autoload.php';

use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;

use Illuminate\Database\Capsule\Manager as Capsule;
use Laztopaz\EmojiRestfulAPI\DatabaseConnection;
use Laztopaz\EmojiRestfulAPI\EmojiController;
use Laztopaz\EmojiRestfulAPI\SlimRouteApp;
use Laztopaz\EmojiRestfulAPI\Oauth;


$capsule = new Capsule();

new DatabaseConnection($capsule);

$auth = new Oauth();

$emoji = new EmojiController($auth);

$app = new SlimRouteApp($auth, $emoji);

$app->setUpSlimApp()->run();
