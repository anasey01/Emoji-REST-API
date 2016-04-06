<?php
/**
 * @author   Temitope Olotin <temitope.olotin@andela.com>
 * @license  <https://opensource.org/license/MIT> MIT
 */
namespace Laztopaz\EmojiRestfulAPI;

use Exception;
use Firebase\JWT\JWT;
use Illuminate\Database\Capsule\Manager as Capsule;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class EmojiController
{
    private $auth;

    public function __construct(Oauth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * This method list all emojis.
     *
     * @param $response
     *
     * @return json $emojis
     */
    public function listAllEmoji(Response $response)
    {
        $emojis = Emoji::with('keywords', 'category', 'created_by')
        ->get()
        ->toArray();

        if (count($emojis) > 0) {
            return $response
            ->withJson($this->formatEmoji($emojis), 200);
        }

        return $response->withJson(['message' => 'No Emoji to display now'], 404);
    }

    /**
     * This method get a single emoji.
     *
     * @param $response
     * @param $args
     *
     * @return json $emoji
     */
    public function getSingleEmoji(Response $response, $args)
    {
        $emoji = null;

        if ($args['id']) {
            $emoji = Emoji::where('id', '=', $args['id'])
            ->with('keywords', 'category', 'created_by')
            ->get();

            if (count($emoji) <= 0) {
                $emoji = Emoji::where('name', '=', strtolower($args['id']))
                ->with('keywords', 'category', 'created_by')
                ->get();
            }
        }

        $emoji = $emoji->toArray();

        if (count($emoji) > 0) {
            return $response
            ->withJson($this->formatEmoji($emoji), 200);
        }

        return $response->withJson(['message' => 'Emoji not found'], 404);

    }

    /**
     * This method creates a new emoji.
     *
     * @param $args
     *
     * @return json $response;
     */
    public function createEmoji(Request $request, Response $response)
    {
        $requestParams = $request->getParsedBody();

        if (is_array($requestParams)) {
            if (!$this->checkForDuplicateEmoji($requestParams['name'])) {
                // Validate the user input fields
                $validateResponse = $this->validateUserInput(['name', 'char', 'category', 'keywords'], $requestParams);

                if (is_array($validateResponse)) {
                    return $response->withJson($validateResponse, 400);
                }

                return $this->runCreateEmoji($request, $response, $requestParams);
            }

            return $response->withJson(['message' => 'Emoji cannot be duplicated'], 400);

        }
    }

    /**
     * This method creates emoji and keywords associated with it.
     *
     * @param $emoji
     * @param $request
     * @param $response
     * @param $requestParams
     *
     * @return json response
     */
    public function runCreateEmoji($request, $response, $requestParams)
    {
        $emoji = Emoji::create([
            'name'       => strtolower($requestParams['name']),
            'char'       => $requestParams['char'],
            'created_at' => date('Y-m-d h:i:s'),
            'category'   => $requestParams['category'],
            'created_by' => $this->getCurrentUserId($request, $response),
        ]);
        if ($emoji->id) {
            $createdKeyword = $this->createEmojiKeywords($emoji->id, $requestParams['keywords']);

            return $response->withJson($emoji->toArray(), 201);

        }
    }

    /**
     * This method updates an emoji.
     *
     * @param $request
     * @param $response
     *
     * @return json
     */
    public function updateEmojiByPutVerb(Request $request, Response $response, $args)
    {
        $updateParams = $request->getParsedBody();

        if (is_array($updateParams)) {
            $emoji = Emoji::find($args['id']);

            if (count($emoji) > 0) { // Validate the user input fields
                $validateResponse = $this->validateUserInput(['name', 'char', 'category'], $updateParams);
                if (is_array($validateResponse)) {
                    return $response->withJson($validateResponse, 400);
                }

                if (is_null($this->findTheOwner($args, $request, $response)->first())) {
                    return $response->withJson([
                        'message' => 'Emoji cannot be updated because you are not the creator',
                    ], 401);
                }

                return $this->runUpdateEmoji($emoji, $response, $updateParams);
            }

            return $response->withJson([
                'message' => 'Record cannot be updated because the id supplied is invalid',
            ], 404);
            
        }
    }

    /**
     * This method updates an emoji.
     *
     * @param $emoji
     * @param $response
     * @param $updateParams
     *
     * @return json $response
     */
    public function runUpdateEmoji($emoji, $response, $updateParams)
    {
        $emoji->name = $updateParams['name'];
        $emoji->char = $updateParams['char'];
        $emoji->category = $updateParams['category'];
        $emoji->updated_at = date('Y-m-d h:i:s');
        $emoji->save();

        return $response->withJson(['message' => 'Record updated successfully'], 200);
    }

    /**
     * This method updates an emoji partially.
     *
     * @param $request
     * @param $response
     *
     * @return json
     */
    public function updateEmojiByPatchVerb(Request $request, Response $response, $args)
    {
        $upateParams = $request->getParsedBody();

        if (is_array($upateParams)) {
            $emoji = Emoji::find($args['id']);
            if (count($emoji) > 0) {
                //Validate user inputs
                $validateResponse = $this->validateUserInput(['name'], $upateParams);
                if (is_array($validateResponse)) {
                    return $response->withJson($validateResponse, 400);
                }

                if (is_null($this->findTheOwner($args, $request, $response)->first())) {
                    return $response->withJson([
                        'message' => 'Emoji cannot be updated because you are not the creator',
                    ], 401);
                }

                $emoji->name = $upateParams['name'];
                $emoji->updated_at = date('Y-m-d h:i:s');
                $emoji->save();

                return $response->withJson($emoji->toArray(), 200);
            }

            return $response->withJson([
                'message' => 'No record to update because the id supplied is invalid',
            ], 404);
        }
    }

    /**
     * This method deletes an emoji.
     *
     * @param $request
     * @param $response
     * @param $args
     *
     * @return json
     */
    public function deleteEmoji(Request $request, Response $response, $args)
    {
        $emoji = Emoji::find($args['id']);
        if (count($emoji) > 0) {
            $emojis = $this->findTheOwner($args, $request, $response);

            if (! is_null($emojis)) {
                $emojis->delete();
            }

            if ($emojis) {
                // Delete keywords associated with the emoji
                Keyword::where('emoji_id', '=', $args['id'])->delete();

                return $response->withJson(['message' => 'Emoji was sucessfully deleted'], 200);
            }

            return $response->withJson([
                'message' => 'Emoji cannot be deleted because you are not the creator',
            ], 401);
        }

        return $response->withJson([
            'message' => 'Emoji cannot be deleted because the id supplied is invalid',
        ], 404);
    }

    /**
     * This method creates emoji keywords.
     *
     * @param $request
     * @param $response
     * @param $args
     *
     * @return $id
     */
    public function createEmojiKeywords($emoji_id, $keywords)
    {
        if ($keywords) {
            $splittedKeywords = explode(',', $keywords);

            $created_at = date('Y-m-d h:i:s');

            foreach ($splittedKeywords as $keyword) {
                $emojiKeyword = Keyword::create([
                        'emoji_id'     => $emoji_id,
                        'keyword_name' => $keyword,
                        'created_at'   => $created_at,
                ]);
            }
        }

        return $emojiKeyword->id;
    }

    /**
     * This method format emoji result.
     *
     * @param $emojis
     *
     * @return array $emojis
     */
    public function formatEmoji(array $emojis)
    {
        foreach ($emojis as $key => &$value) {
            $value['created_by'] = $value['created_by']['firstname'].' '.$value['created_by']['lastname'];
            $value['category'] = $value['category']['category_name'];
            $value['keywords'] = array_map(function ($key) { return $key['keyword_name']; }, $value['keywords']);
        }

        return $emojis;
    }

    /**
     * This method authenticate and return user id.
     */
    public function getCurrentUserId($request, $response)
    {
        $loadEnv = DatabaseConnection::loadEnv();

        $jwtoken = $request->getHeader('HTTP_AUTHORIZATION');

        try {
            if (isset($jwtoken)) {
                $secretKey = base64_decode(getenv('secret'));

                $jwt = json_decode($jwtoken[0], true);

                //decode the JWT using the key from config
                $decodedToken = JWT::decode($jwt['jwt'], $secretKey, ['HS512']);

                $tokenInfo = (array) $decodedToken;

                $userInfo = (array) $tokenInfo['dat'];

                return $userInfo['id'];
            }
        } catch (Exception $e) {
            return $response->withJson(['status' => $e->getMessage()], 401);
        }
    }

    /**
     * This method checks for duplicate emoji.
     *
     * @param $name
     *
     * @return bool true
     */
    public function checkForDuplicateEmoji($emojiName)
    {
        if (isset($emojiName)) {
            $emojiFound = Capsule::table('emojis')
            ->where('name', '=', strtolower($emojiName))
            ->get();

            if (count($emojiFound) > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * This method solves for rightful of a record
     */
    public function findTheOwner($args, $request, $response)
    {
        return Capsule::table('emojis')
        ->where('id', '=', $args['id'])
        ->where('created_by', '=', $this->getCurrentUserId($request, $response));
    }

    /**
     * This method will
     * verify the fields supplied by the user when posting to the API
     * and also validate their input for empty values.
     *
     * @param $expectedFields
     * @param $suppliedFields
     *
     * @return json response
     */
    public function validateUserInput(array $expectedFields, array $suppliedFields)
    {
        $counter = 0;

        if (count($suppliedFields) < count($expectedFields)) {
            return ['message' => 'All fields must be supplied'];
        } else { // Check whether the field supplied by the user is what we expect from them
            foreach ($suppliedFields as $key => $value) {
                if (!in_array($key, array_merge($expectedFields, ['created_at', 'updated_at', 'created_by']))) {
                    $counter++;
                }
            }
            if ($counter > 0) {
                $counter = 0;

                return ['message' => 'Unwanted fields must be removed'];
            } else { // Check whether all fields have corresponding values
                foreach ($suppliedFields as $key => $value) {
                    if ($value == '') {
                        $counter++;
                    }
                }
                if ($counter > 0) {
                    return ['message' => 'All fields are required'];
                } else {
                    return true;
                }
            }
        }
    }
}
