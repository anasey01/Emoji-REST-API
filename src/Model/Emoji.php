<?php
/**
 * @author   Temitope Olotin <temitope.olotin@andela.com>
 * @license  <https://opensource.org/license/MIT> MIT
 */
namespace Laztopaz\EmojiRestfulAPI;

use Illuminate\Database\Eloquent\Model;

class Emoji extends Model
{
    protected $fillable = ['name', 'char', 'category', 'created_by', 'created_at', 'updated_at'];

    /**
<<<<<<< HEAD
     * Get emoji keywords
=======
     * Get emoji keywords.
>>>>>>> 59d00e685bf02dd02892708a7ff4c53c06c0437a
     */
    public function keywords()
    {
        return $this->hasMany('Laztopaz\EmojiRestfulAPI\Keyword')->select(['emoji_id', 'keyword_name']);
    }

    /**
<<<<<<< HEAD
     * Get emoji category
=======
     * Get emoji category.
>>>>>>> 59d00e685bf02dd02892708a7ff4c53c06c0437a
     */
    public function category()
    {
        return $this->hasOne('Laztopaz\EmojiRestfulAPI\Category', 'id', 'category')->select('id', 'category_name');
    }

    /**
<<<<<<< HEAD
     * Get emoji creator
=======
     * Get emoji creator.
>>>>>>> 59d00e685bf02dd02892708a7ff4c53c06c0437a
     */
    public function created_by()
    {
        return $this->hasOne('Laztopaz\EmojiRestfulAPI\User', 'id', 'created_by');
    }
}
