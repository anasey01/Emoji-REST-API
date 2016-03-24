<?php
/**
 * @author   Temitope Olotin <temitope.olotin@andela.com>
 * @license  <https://opensource.org/license/MIT> MIT
 */

namespace Laztopaz\EmojiRestfulAPI\Test;

require_once __DIR__ .'/../vendor/autoload.php';

use Slim\Http\Environment;
use \Slim\App;
use Slim\Http\Request;
use PHPUnit_Framework_TestCase;

use Laztopaz\EmojiRestfulAPI\EmojiController;
use Laztopaz\EmojiRestfulAPI\User;
use Laztopaz\EmojiRestfulAPI\Oauth;
use Laztopaz\EmojiRestfulAPI\Emoji;
use Laztopaz\EmojiRestfulAPI\Keyword;
use Laztopaz\EmojiRestfulAPI\SlimRouteApp;
use Laztopaz\EmojiRestfulAPI\Schema;
use Laztopaz\EmojiRestfulAPI\UploadTableInfo;

use Illuminate\Database\Capsule\Manager as Capsule;
use Laztopaz\EmojiRestfulAPI\DatabaseConnection;

class EmojiEndPointTest extends PHPUnit_Framework_TestCase
{
    protected $app;
    protected $response;
    protected $emojis;

    public function setUp()
    {
        $capsule = new Capsule();

        new DatabaseConnection($capsule);

        //new Schema;
        new UploadTableInfo;

        $auth = new Oauth;

        $emoji = new EmojiController($auth);

        $app = new SlimRouteApp($auth, $emoji);

        $this->app = $app->setUpSlimApp();

    }

     public function request($method, $path, $options = array())
     {
         // Prepare a mock environment
         $env = Environment::mock(array_merge([
            'REQUEST_METHOD' => $method,
            'PATH_INFO' => $path,
            'CONTENT_TYPE' => 'application/json',
            'SERVER_NAME' => 'slim-test.dev',
            ], $options));

        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $this->response = $this->app->run(true);
    }

    /**
     * This method defines a get request for all emojis endpoint
     * @param  $path
     * @param  $options
     * @return $request
     */
    public function get($path, $options = array())
    {
        $this->request('GET', $path, $options);
    }

    /**
     * This method defines a get request for all emojis endpoint
     * @param  $path
     * @param  $options
     * @return $request
     */
    public function post($path, $options = array())
    {
        $this->request('POST', $path, $options);
    }

    /**
     * This method ascertain that emoji index page return status code 404
     * @param  void
     * @return booleaan true
     */
    public function testPostIndex()
    {
        $this->post('/', ['ACCEPT' => 'application/json']);
        $this->assertEquals('404', $this->response->getStatusCode());
    }

    /**
     * This method ascertain that emoji index page return status code 404
     * @param  void
     * @return booleaan true
     */
    public function testIndex()
    {
        $this->get('/', ['ACCEPT' => 'application/json']);
        $this->assertEquals('404', $this->response->getStatusCode());
    }

    public function testgetAllEmoji()
    {
         $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/emojis',
            'CONTENT_TYPE' => 'application/json',
            'PATH_INFO'      => '/emojis',
            ]);

        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);
    
        $data = json_decode($response->getBody(), true);
        $this->assertSame($response->getStatusCode(), 200);

    }

    public function testSingleEmoji()
    {
         $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/emojis/1',
            'CONTENT_TYPE' => 'application/json',
            'PATH_INFO'      => '/emojis',
            ]);

        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);

        $data = json_decode($response->getBody(), true);
        $this->assertSame($response->getStatusCode(), 200);

    }

    public function testuserLogout()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/auth/logout',
            'CONTENT_TYPE' => 'application/json',
            'PATH_INFO'      => '/auth',
            ]);

        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);

        $data = json_decode($response->getBody(), true);
        $this->assertSame($response->getStatusCode(), 401);
    }

    public function testuserLogin()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/auth/login',
            'CONTENT_TYPE' => 'application/json',
            'PATH_INFO'      => '/auth',
            ]);

        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);

        $data = json_decode($response->getBody(), true);
        $this->assertSame($response->getStatusCode(), 200);
    }

    public function testThatLoginCredentialWhereUsedToLogin()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/auth/login',
            'CONTENT_TYPE' => 'application/x-www-form-urlencoded',
            'PATH_INFO'      => '/auth',
            ]);

        $req = Request::createFromEnvironment($env);
        $req = $req->withParsedBody(['username' => 'laztopaz','password' => 'tope0852']);

        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);

        $data = json_decode($response->getBody(), true);

        $token = $data['token'];

        $this->assertArrayHasKey('token', $data);
        $this->assertSame($response->getStatusCode(), 200);
    }

    public function testPostEmoji()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI'    => '/emojis',
            'CONTENT_TYPE' => 'application/x-www-form-urlencoded'
            ]);

        $req = Request::createFromEnvironment($env);
        $req = $req->withParsedBody(
                [
                    'name' => 'FACE WITH TEARS OF JOY', 
                    'char' => '/u{1F602}', 
                    'created_at' => date('Y-m-d h:i:s'), 
                    'category' => 1, 
                    'created_by' => 1
                ]
            );
        $this->app->getContainer()['request'] = $req;

        $response = $this->app->run(true);

        $data = json_decode($response->getBody(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($data[0]['char'], $emoji->id);
        $this->assertSame($data[0]['name'], $emoji->name);
    }

     public function testGetSingleEmojiReturnsEmojiWithStatusCode200()
     {
        $emoji = Emoji::get()->first();        
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI'    => '/emojis/'.$emoji->id,
            'PATH_INFO'      => '/emojis',
            ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $data = json_decode($response->getBody(), true);

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($data[0]['id'], $emoji->id);
        $this->assertSame($data[0]['name'], $emoji->name);
    }

    public function testGetAllEmojiReturnEmojisWithStatusCode200()
    {
        $emoji = Emoji::get();
        
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI'    => '/emojis',
            'PATH_INFO'      => '/emojis',
            ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $data = json_decode($response->getBody(), true);
        $this->assertSame($response->getStatusCode(), 200);
    }

}