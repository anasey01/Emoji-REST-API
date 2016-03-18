<?php
/**
 * @author   Temitope Olotin <temitope.olotin@andela.com>
 * @license  <https://opensource.org/license/MIT> MIT
 */

namespace Laztopaz\EmojiRestfulAPI;

use Laztopaz\EmojiRestfulAPI\Emoji;
use Laztopaz\EmojiRestfulAPI\Keyword;

class EmojiController {

    /**
     * 
     * This method list all emoji
     *
     * @return  json
     * 
     */
    public function listAllEmoji()
    {
        $emojis = Emoji::with('keywords','category', 'created_by')->get();
        $emojis = $emojis->toArray();

        return json_encode($this->formatEmoji($emojis));
    }

    /**
     * 
     * This method get a single emoji
     *
     * @params id
     *
     * @return json
     * 
     */
    public function getSingleEmoji( $id )
    {
        $emoji = Emoji::where('id', '=' , $id )->with('keywords','category', 'created_by')->get();
        $emoji = $emoji->toArray();

        return json_encode($this->formatEmoji($emoji));
        
    }

    /**
     * 
     * This method creates a new emoji
     *
     * @params $args
     *
     * @return  boolean true;
     * 
     */
    public function createEmoji( $args )
    {
        if ( is_array($args) ) {
            $created_at = date('Y-m-d h:i:s');
            $emoji = Emoji::create(['name' => $args['name'], 'char' => $args['char'], 'created_at' => $created_at, 'category' => $args['category'], 'created_by' => $args['owner']]);

            return json_encode(['statuscode' => 200 ,'response' => $emoji]);

        }
    }

    /**
     * 
     * This method updates an emoji
     *
     * @params $id
     *
     * @params $data
     *
     * @return  json
     * 
     */
    public function updateEmojiByPutVerb( $data, $id )
    {
        if ( is_array( $data ) && ( $id != "" ) ) {
            return json_encode( ['id' => $id, 'data' => $data] );
        }

    }

    /**
     * 
     * This method updates an emoji partially
     *
     * @params $id
     *
     * @params $data
     *
     * @return json
     */
    public function updateEmojiByPatchVerb( $data, $id )
    {
        if ( is_array( $data ) && ( $id != "" ) ) {
            return json_encode( ['id' => $id, 'data' => $data] );
        }

    }

    /**
     * This method deletes an emoji 
     *
     * @params $id
     *
     * @return json
     */
    public function deleteEmoji( $id )
    {
        if ( $id != "") {
            return json_encode( ['id' => $id] );
        }

    }

    /**
     * This method format emoji result
     *
     * @params $emojis
     *
     * @return array $emojis
     */

    private function formatEmoji(array $emojis)
    {
        foreach ($emojis as $key => &$value) {
            $value['created_by'] = $value['created_by']['firstname']." ".$value['created_by']['lastname'];
            $value['category'] = $value['category']['category_name'];
            $value['keywords'] = array_map(function($key){ return $key["keyword_name"]; }, $value['keywords']);
        }

        return $emojis;

    }

}