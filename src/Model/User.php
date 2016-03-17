<?php
/**
 * @author   Temitope Olotin <temitope.olotin@andela.com>
 * @license  <https://opensource.org/license/MIT> MIT
 */

namespace Laztopaz\EmojiRestfulAPI;

use Illuminate\Database\Eloquent\Model;

class User extends Model 
{
    protected $fillable = ['firstname', 'lastname', 'username', 'password', 'created_by', 'created_at', 'updated_at'];

    /**
     * 
     * Get creator of an emoji
     * 
     */
    public function emoji()
    {
        return $this->hasMany('Laztopaz\EmojiRestfulAPI\Emoji', 'created_by', 'id');
    }
}