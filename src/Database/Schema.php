<?php
/**
 * @author   Temitope Olotin <temitope.olotin@andela.com>
 * @license  <https://opensource.org/license/MIT> MIT
 */
namespace Laztopaz\EmojiRestfulAPI;

use Illuminate\Database\Capsule\Manager as Capsule;

class Schema
{
    /**
     * This method migrates all database schema when this class is instatiated.
     */
    public function __construct()
    {
        $this->createUser();
        $this->createKeyword();
        $this->createCategory();
        $this->createEmoji();
    }

    /**
     * This method create users schema.
     */
    public function createUser()
    {
        if (! Capsule::schema()->hasTable('users')) {
            Capsule::schema()->create('users', function ($table) {
                $table->increments('id');
                $table->string('firstname');
                $table->string('lastname');
                $table->string('username');
                $table->string('password');
                $table->string('email')->unique();
                $table->timestamps();
            });
        }
    }

    /**
     * This method creates keyword schema.
     */
    public function createKeyword()
    {
        if (! Capsule::schema()->hasTable('keywords')) {
            Capsule::schema()->create('keywords', function ($table) {
                $table->increments('id');
                $table->integer('emoji_id');
                $table->string('keyword_name');
                $table->timestamps();
            });
        }
    }

    /**
     * This method creates emoji category schema.
     */
    public function createCategory()
    {
        if (! Capsule::schema()->hasTable('categories')) {
            Capsule::schema()->create('categories', function ($table) {
                $table->increments('id');
                $table->string('category_name');
                $table->timestamps();
            });
        }
    }

    /**
     * This method creates emoji  schema.
     */
    public function createEmoji()
    {
        if (! Capsule::schema()->hasTable('emojis')) {
            Capsule::schema()->create('emojis', function ($table) {
                $table->increments('id');
                $table->string('name');
                $table->string('char');
                $table->string('category');
                $table->string('created_by');
                $table->timestamps();
            });
        }
    }
}
