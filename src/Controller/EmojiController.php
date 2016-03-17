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
     * This method list all emoji
     *
     * @return  json
     */
    public function listAllEmoji()
    {
        $emojis = Emoji::with('keywords','category', 'created_by')->get();
        $emojis = $emojis->toArray();

        return json_encode($this->formatEmoji($emojis));
    }

    /**
     * This method get a single emoji
     *
     * @params id
     *
     * @return json
     */
    public function getSingleEmoji( $id )
    {
        $emoji = Emoji::where('id', '=' , $id )->with('keywords','category', 'created_by')->get();
        $emoji = $emoji->toArray();

        return json_encode($this->formatEmoji($emoji));
        
    }

    /**
     * This method creates a new emoji
     *
     * @params $args
     *
     * @return  boolean true;
     */
    public function createEmoji( $args )
    {
        if ( is_array($args) ) {
            $name = $args['name'];
            $char = $args['char'];
            $created_at = date('Y-m-d h:i:s');
            $category = $args['category'];
            $created_by = $args['owner'];

            $response = Emoji::create(['name' => $name, 'char' => $char, 'created_at' => $created_at, 'category' => $category, 'created_by' => $created_by]);

            //Keyword::create(['emoji_id' => $response->id])

            //return json_encode(['statuscode' => 200 ,'response' => $response]);


            //  //$hash = password_hash($args['password'], PASSWORD_BCRYPT);
        }
    }

    /**
     * This method updates an emoji
     *
     * @params $id
     *
     * @params $data
     *
     * @return  boolean true
     */
    public function updateEmojiByPutVerb( $data, $id )
    {
        if ( is_array( $data ) && ( $id != "" ) ) {
            return json_encode( ['id' => $id, 'data' => $data] );
        }

    }

    /**
     * This method updates an emoji partially
     *
     * @params $id
     *
     * @params $data
     *
     * @return  boolean true
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
     * @return  boolean true
     */
    public function deleteEmoji( $id )
    {
        if ( $id != "") {
            return json_encode( ['id' => $id] );
        }

    }

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