<?php
/**
 * @author   Temitope Olotin <temitope.olotin@andela.com>
 * @license  <https://opensource.org/license/MIT> MIT
 */

namespace Laztopaz\EmojiRestfulAPI;

use Illuminate\Database\Eloquent\Model;

class Category extends Model 
{
    protected $fillable = ['category_name', 'created_at', 'updated_at'];

    /**
     * 
     * Get emoji category
     * 
     */
    public function emoji()
    {
        return $this->belongsTo('Laztopaz\EmojiRestfulAPI\Emoji');
    }

}