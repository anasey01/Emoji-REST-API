<?php
/**
 * @author   Temitope Olotin <temitope.olotin@andela.com>
 * @license  <https://opensource.org/license/MIT> MIT
 */
namespace Laztopaz\EmojiRestfulAPI;

use Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Illuminate\Database\Capsule\Manager as Capsule;
use Laztopaz\EmojiRestfulAPI\UserController;

class Oauth
{

    /**
     * This method register a new user
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

            if (!  $this->verifyUserRegistration($userParams['username'], $userParams['email'])) {
                $boolResponse = $user->createUser([
                    'firstname'  => $userParams['firstname'],
                    'lastname'   => $userParams['lastname'],
                    'username'   => $userParams['username'],
                    'password'   => $userParams['password'],
                    'email'      => $userParams['email'],
                    'created_at' => date('Y-m-d h:i:s'),
                    'updated_at' => date('Y-m-d h:i:s')
                ]);

                if ($boolResponse) {
                    return $response->withJson(['message' => 'User successfully created'], 200);
                }

                return $response->withJson(['message' => 'User not created'], 400);
            }

            return $response->withJson(['message' => 'User already exists'], 400);
        }

    }

    /**
     * This method authenticate the user and log them in if the supplied
     * credentials are valid.
     *
     * @param array $loginParams
     *
     * @return json jwt
     */
    public function loginUser(Request $request, Response $response)
    {
        $loginParams = $request->getParsedBody();

        if (is_array($loginParams)) {
            $user = User::where('username', '=', $loginParams['username'])->get();
            $user = $user->first();

            $userInfo = ['id' => $user->id, 'username' => $user->username, 'email' => $user->email];

            if (password_verify($loginParams['password'], $user->password)) {
                $token = $this->buildAcessToken($userInfo);

                return $response->withAddedHeader('HTTP_AUTHORIZATION', $token)->withStatus(200)->write($token);
            }

            return $response->withJson(['status'], 400);
        }
    }

    /**
     * This method logout the user.
     *
     * @param $args logout
     *
     * @return $reponse
     */
    public function logoutUser(Request $request, Response $response, $args)
    {
        return $response->withJson(['message' => 'Logout successful'], 200);
    }

    /**
     * This method verifies a registered user
     *
     * @param $email
     * @param $username
     *
     * @return boolean true
     */
    public function verifyUserRegistration($username, $email)
    {
        if (isset($username) && isset($email)) {
            $userFound = Capsule::table('users')
            ->where('username', '=', strtoupper($username))
            ->orWhere('username', '=', strtolower($username))
            ->orWhere('username', '=', ucwords($username))
            ->where('email', '=', strtoupper($email))
            ->orWhere('email', '=', strtolower($email))
            ->orWhere('email', '=', ucwords($email))
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
        $expire = $notBefore + (float) strtotime('+30 days'); // Adding 30 days expiry date
        $serverName = 'http://sweatemoji.com/api'; // Retrieve the server name

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

            'dat'  => $userData         // User Information retrieved from the database
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
