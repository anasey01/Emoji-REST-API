<?php
/**
 * @author   Temitope Olotin <temitope.olotin@andela.com>
 * @license  <https://opensource.org/license/MIT> MIT
 */

namespace Laztopaz\EmojiRestfulAPI;

use Dotenv\Dotenv;

class DatabaseConnection 
{
    private $capsule;

    /**
     *
     * This constructor accept Capsule; a connection
     * string for connecting to the database
     * 
     * @param $capsule 
     */
    public function __construct($capsule)
    {
        $this->capsule = $capsule;
        self::loadEnv();
        $this->setUpDatabase();
    }

    /**
     * 
     * This method setup the PDO database connection and also
     * start the database connection
     * 
     */
    private function setUpDatabase()
    {
         $this->capsule->addConnection(
            [
                'driver'    => getenv('driver'),
                'host'      => getenv('host'),
                'database'  => getenv('database'),
                'username'  => getenv('username'),
                'password'  => getenv('password'),
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'port'      => getenv('port'),
                'prefix'    => '',
                'strict'    => true
            ]);

         $this->capsule ->setAsGlobal();
         $this->capsule ->bootEloquent();
     }

    /**
     * 
     * Load Dotenv to grant getenv() access to 
     * environment variables in .env file
     * 
     */
     public static function loadEnv()
     {
         if (! getenv('APP_ENV')) {
             $dotenv = new Dotenv(__DIR__.'/../../');
             $dotenv->load();
         }
     }

}

