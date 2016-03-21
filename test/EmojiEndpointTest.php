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

class EmojiEndPointTest extends PHPUnit_Framework_TestCase
{
    protected $app;
    protected $response;

    public function setUp()
    {
        $app = new \Slim\App();
        $this->app = $app;

    }

     public function request($method, $path, $options = array())
     {
         // Prepare a mock environment
         $env = Environment::mock(array_merge([
            'REQUEST_METHOD' => $method,
            'PATH_INFO' => $path,
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
     * This method defines a post request for all emojis endpoint
     * @param  $path
     * @param  $options
     * @return $request
     */
    public function post($path, $options = array())
    {
        $this->request('POST', $path, $options);
    }

    /**
     * This method defines a put request for all emojis endpoint
     * @param  $path
     * @param  $options
     * @return $request
     */
    public function put($path, $options = array())
    {
        $this->request('PUT', $path, $options);
    }

    /**
     * This method defines a patch request for all emojis endpoint
     * @param  $path
     * @param  $options
     * @return $request
     */
    public function patch($path, $options = array())
    {
        $this->request('PATCH', $path, $options);
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

}