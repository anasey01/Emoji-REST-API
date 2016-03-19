<?php
/**
 * @author   Temitope Olotin <temitope.olotin@andela.com>
 * @license  <https://opensource.org/license/MIT> MIT
 */

namespace Laztopaz\EmojiRestfulAPI;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use Laztopaz\EmojiRestfulAPI\Emoji;
use Laztopaz\EmojiRestfulAPI\Keyword;

class EmojiController {

    private $auth;

    public function __construct()
    {
        $this->auth = new OauthLogin();
    }

    /**
     * 
     * This method list all emojis
     *
     * @param $response
     * 
     * @return json $emojis
     * 
     */
    public function listAllEmoji(Response $response)
    {
        $emojis = Emoji::with('keywords','category', 'created_by')->get();
        $emojis = $emojis->toArray();

        if (count($emojis) > 0) {
            return $response
            ->withJson(['status'], 200)
            ->write(json_encode(
                $this->formatEmoji($emojis)
            ));
        }

        return $response->withJson(['status'], 404);

    }

    /**
     * 
     * This method get a single emoji
     *
     * @param $response
     *
     * @param $args
     *
     * @return json $emoji
     * 
     */
    public function getSingleEmoji(Response $response, $args)
    {
        $id  = $args['id'];
        $emoji = Emoji::where('id', '=', $id)->with('keywords', 'category', 'created_by')->get();
        $emoji = $emoji->toArray();

        if (count($emoji) > 0) {
            return $response
            ->withJson(['status'], 200)
            ->write(json_encode($this->formatEmoji($emoji)));
        }

        return $response->withJson(['status'], 404);

    }

    /**
     * 
     * This method creates a new emoji
     *
     * @param $args
     *
     * @return json $response;
     * 
     */
    public function createEmoji(Request $request, Response $response)
    {
        $requestParams = $request->getParsedBody();

        $token = $this->auth->buildAcessToken($_SESSION['userinfo']);

        $emojiKeyword = $requestParams['keywords'];

        if (is_array($requestParams)) {
            $created_at = date('Y-m-d h:i:s');

            $emoji = Emoji::create(
                [
                    'name' => $requestParams['name'], 
                    'char' => $requestParams['char'], 
                    'created_at' => $created_at, 
                    'category' => $requestParams['category'], 
                    'created_by' => $_SESSION['userinfo']['id']
                ]
            );

            if ($emoji->id) {
                // Create emoji keywords
                $createdKeyword = $this->createEmojiKeywords($emoji->id, $emojiKeyword);

                return $response->withJson(['status'], 201)->withAddedHeader('token', $token);
            }

            return $response->withJson(['status'], 204)->withAddedHeader('token', $token);

        }

    }

    /**
     * 
     * This method updates an emoji
     *
     * @param $request
     *
     * @param $response
     *
     * @return json
     * 
     */
    public function updateEmojiByPutVerb(Request $request, Response $response, $args)
    {
        $upateParams = $request->getParsedBody();

        if (is_array($upateParams)) {
            $id = $args['id'];

            $token = $this->auth->buildAcessToken($_SESSION['userinfo']);

            $emoji = Emoji::find($id);
    
            if ($emoji->id) {
                $emoji->name = $upateParams['name'];
                $emoji->char = $upateParams['char'];
                $emoji->category = $upateParams['category'];
                $emoji->updated_at = date('Y-m-d h:i:s');
                $emoji->save();

                return $response->withJson(['status'], 201)->withAddedHeader('token', $token);

            }

            return $response->withJson(['status'], 404)->withAddedHeader('token', $token);

        }

    }

    /**
     * 
     * This method updates an emoji partially
     *
     * @param $request
     *
     * @param $response
     *
     * @param $args
     *
     * @return json
     */
    public function updateEmojiByPatchVerb(Request $request, Response $response, $args)
    {
        $upateParams = $request->getParsedBody();

        if (is_array($upateParams)) {
            $id = $args['id'];

            $token = $this->auth->buildAcessToken($_SESSION['userinfo']);

            $emoji = Emoji::find($id);
            if ($emoji->id) {
                $emoji->name = $upateParams['name'];
                $emoji->updated_at = date('Y-m-d h:i:s');
                $emoji->save();

                return $response->withJson(['status'], 201)->withAddedHeader('token', $token);

            }

            return $response->withJson(['status'], 404)->withAddedHeader('token', $token);

        }

    }

    /**
     * This method deletes an emoji 
     *
     * @param $request
     *
     * @param $response
     * 
     * @param $args
     *
     * @return json
     */
    public function deleteEmoji(Request $request, Response $response, $args)
    {
        $id  = $args['id'];

        $emoji = Emoji::find(1);
        if ($emoji->id) {
            $emoji->delete();

            // Delete keywords assciated with the emoji
            Keyword::where('emoji_id', '=', $id)->delete();

            return $response->withJson(['status'], 204)->withAddedHeader('token', $token);

        }

        return $response->withJson(['status'], 404)->withAddedHeader('token', $token);
    }

    /**
     * This method creates emoji keywords
     *
     * @param $emoji_id
     *
     * @param $keywords
     *
     * @return $id
     */
    public function createEmojiKeywords($emoji_id, $keywords)
    {
        if ($keywords) {
            $splittedKeywords = explode(",", $keywords);

            $created_at = date('Y-m-d h:i:s');

            foreach ($splittedKeywords as $keyword) {
                $emojiKeyword = Keyword::create(
                    [
                        'emoji_id' => $emoji_id, 
                        'keyword_name' => $keyword, 
                        'created_at' => $created_at
                    ]
                );
            }
        }

        return $emojiKeyword->id;
    }

    /**
     * This method format emoji result
     *
     * @param $emojis
     *
     * @return array $emojis
     */
    public  function formatEmoji(array $emojis)
    {
        foreach ($emojis as $key => &$value) {
            $value['created_by'] = $value['created_by']['firstname']." ".$value['created_by']['lastname'];
            $value['category'] = $value['category']['category_name'];
            $value['keywords'] = array_map(function($key){ return $key["keyword_name"]; }, $value['keywords']);
        }

        return $emojis;

    }

}