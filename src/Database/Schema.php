<?php
/**
 * @author   Temitope Olotin <temitope.olotin@andela.com>
 * @license  <https://opensource.org/license/MIT> MIT
 */

namespace Laztopaz\EmojiRestfulAPI;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

class Schema 
{
    public function __construct()
    {
        $this->createUser();
        $this->createKeyword();
        $this->createCategory();
        $this->createEmoji();
    }

    public function createUser()
    {
        //Schema::dropIfExists('users');

        Capsule::schema()->create('users', function($table) {
            $table->increments('id');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('username');
            $table->string('password');
            $table->string('email')->unique();
            $table->timestamps();
        });

    }

    public function createKeyword()
    {
        //Schema::dropIfExists('keywords');

        Capsule::schema()->create('keywords', function($table) {
            $table->increments('id');
            $table->integer('emoji_id');
            $table->string('keyword_name');
            $table->timestamps();
        });

    }

    public function createCategory()
    {
        //Schema::dropIfExists('categories');

        Capsule::schema()->create('categories', function($table) {
            $table->increments('id');
            $table->string('category_name');
            $table->timestamps();
        });
    }

    public function createEmoji()
    {
        //Schema::dropIfExists('emojis');

        Capsule::schema()->create('emojis', function($table) {
            $table->increments('id');
            $table->string('name');
            $table->string('char');
            $table->string('category');
            $table->string('created_§by');
            $table->timestamps();
        });

    }
}