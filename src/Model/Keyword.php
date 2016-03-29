<?php
/**
 * @author   Temitope Olotin <temitope.olotin@andela.com>
 * @license  <https://opensource.org/license/MIT> MIT
 */
namespace Laztopaz\EmojiRestfulAPI;

use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    protected $fillable = ['emoji_id', 'keyword_name', 'created_at', 'updated_at'];

    /**
     * Get emoji keywords
     */
    public function emoji()
    {
        return $this->belongsTo('Laztopaz\EmojiRestfulAPI\Emoji');
    }
}
