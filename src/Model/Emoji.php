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
     * Get emoji keywords.
     */
    public function keywords()
    {
        return $this->hasMany('Laztopaz\EmojiRestfulAPI\Keyword')->select(['emoji_id', 'keyword_name']);
    }

    /**
     * Get emoji category.
     */
    public function category()
    {
        return $this->hasOne('Laztopaz\EmojiRestfulAPI\Category', 'id', 'category')->select('id', 'category_name');
    }

    /**
     * Get emoji creator.
     */
    public function created_by()
    {
        return $this->hasOne('Laztopaz\EmojiRestfulAPI\User', 'id', 'created_by');
    }
}
