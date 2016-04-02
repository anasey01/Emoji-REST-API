<?php
/**
 * @author   Temitope Olotin <temitope.olotin@andela.com>
 * @license  <https://opensource.org/license/MIT> MIT
 */
namespace Laztopaz\EmojiRestfulAPI;

use Firebase\JWT\JWT;
use Illuminate\Database\Capsule\Manager as Capsule;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Oauth
{
    /**
     * This method register a new user.
     *
     * @param $request
     * @param $response
     *
     * @return json response
     */
    public function registerUser(Request $request, Response $response)
    {
        $userParams = $request->getParsedBody();

        if (is_array($userParams)) {
            $user = new UserController();
            $emoji = new EmojiController($this);

            $validateResponse = $emoji->validateUserInput([
                'firstname',
                'lastname',
                'username',
                'password',
                'email',
            ], $userParams);

            if (is_array($validateResponse)) {
                return $response->withJson($validateResponse, 400);
            }

            if (!$this->verifyUserRegistration($userParams['username'], $userParams['email'])) {
                return $this->runRegisterUser($user, $userParams, $response);
            }

            return $response->withJson(['message' => 'User already exists'], 400);
        }
    }

    /**
     * This method creates user.
     *
     * @param $user
     * @param $userParams
     * @param $response
     *
     * @return json $response
     */
    public function runRegisterUser($user, $userParams, $response)
    {
        $boolResponse = $user->createUser([
            'firstname'  => $userParams['firstname'],
            'lastname'   => $userParams['lastname'],
            'username'   => strtolower($userParams['username']),
            'password'   => $userParams['password'],
            'email'      => strtolower($userParams['email']),
            'created_at' => date('Y-m-d h:i:s'),
            'updated_at' => date('Y-m-d h:i:s'),
        ]);

        if ($boolResponse) {
            return $response->withJson(['message' => 'User successfully created'], 201);
        }

        return $response->withJson(['message' => 'User not created'], 400);
    }

    /**
     * This method authenticate the user and log them in if the supplied
     * credentials are valid.
     *
     * @return json jwt
     */
    public function loginUser(Request $request, Response $response)
    {
        $loginParams = $request->getParsedBody();

        if (is_array($loginParams)) {
            $user = User::where('username', '=', $loginParams['username'])->get()->first();

            if (count($user) > 0) {
                $userInfo = ['id' => $user->id,];

                if (password_verify($loginParams['password'], $user->password)) {
                    $token = $this->buildAcessToken($userInfo);

                    return $response->withAddedHeader('HTTP_AUTHORIZATION', $token)
                    ->withStatus(200)
                    ->write($token);
                }
            }

            return $response->withJson(['message' => 'Login credentials incorrect'], 400);
        }
    }

    /**
     * This method logout the user.
     *
     * @param $args logout
     *
     * @return $response
     */
    public function logoutUser(Request $request, Response $response, $args)
    {
        return $response->withJson(['message' => 'Logout successful'], 200);
    }

    /**
     * This method verifies a registered user.
     *
     * @param $email
     * @param $username
     *
     * @return bool true
     */
    public function verifyUserRegistration($username, $email)
    {
        if (isset($username, $email)) {
            $userFound = Capsule::table('users')
            ->Where('username', '=', strtolower($username))
            ->orWhere('email', '=', strtolower($email))
            ->get();

            if (count($userFound) > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * This method builds an access token for a login user;.
     *
     * @param $userData
     *
     * @return string $token
     */
    public function buildAcessToken(array $userData)
    {
        $tokenId = base64_encode(mcrypt_create_iv(32));
        $issuedAt = time();
        $notBefore = $issuedAt;
        $expire = (float) strtotime('+30 days'); // Adding 30 days expiry date
        $serverName = 'http://localhost:8000/emojis'; // the server name

        /*
         *
         * Create the token params as an array
         */
        $data = [
            'iat'  => $issuedAt,         // Issued at: time when the token was generated
            'jti'  => $tokenId,          // Json Token Id: an unique identifier for the token
            'iss'  => $serverName,       // Issuer
            'nbf'  => $notBefore,        // Not before
            'exp'  => $expire,           // Expire
            'dat'  => $userData,         // User Information retrieved from the database
        ];

        $loadEnv = DatabaseConnection::loadEnv();

        $secretKey = base64_decode(getenv('secret'));

        $jwt = JWT::encode(
        $data,      //Data to be encoded in the JWT
        $secretKey, // The signing key
        'HS512'     // Algorithm used to sign the token
        );
        $unencodedArray = ['jwt' => $jwt];

        return json_encode($unencodedArray);
    }
}
