<?php
/**
 * @author   Temitope Olotin <temitope.olotin@andela.com>
 * @license  <https://opensource.org/license/MIT> MIT
 */
require 'vendor/autoload.php';
use Illuminate\Database\Capsule\Manager as Capsule;
use Laztopaz\EmojiRestfulAPI\DatabaseConnection;
use Laztopaz\EmojiRestfulAPI\EmojiController;
use Laztopaz\EmojiRestfulAPI\Oauth;
use Laztopaz\EmojiRestfulAPI\SlimRouteApp;

$capsule = new Capsule();
new DatabaseConnection($capsule);
$emoji = new EmojiController(new Oauth());
$app = new SlimRouteApp(new Oauth(), $emoji);
$app->setUpSlimApp()->run();
