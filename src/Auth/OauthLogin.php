<?php
/**
 * @author   Temitope Olotin <temitope.olotin@andela.com>
 * @license  <https://opensource.org/license/MIT> MIT
 */

namespace Laztopaz\EmojiRestfulAPI;

use Laztopaz\EmojiRestfulAPI\User;
use \Firebase\JWT\JWT;

class OauthLogin {

    /**
     * This method authenticate user and log them in if the supplied
     * credentials are valid
     * 
     * @param  array  $loginParams
     * 
     * @return json jwt
     * 
     */
    public function authenticateUser(array $loginParams)
    {
        if (is_array($loginParams)) {
            $user = User::where('username', '=', $loginParams['username'])->get();
            $user = $user->first();
            
            $userInfo = ['id' => $user->id, 'username' => $user->username, 'email' => $user->email];

            if (password_verify($loginParams['password'], $user->password)) {
                return json_encode(['statuscode' => 200, 'response' => 'loggedin']);
            }

            return json_encode(['statuscode' => 400, 'response' => 'Bad request']);
        }
    }

}