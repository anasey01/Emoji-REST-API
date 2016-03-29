<?php
/**
 * @author   Temitope Olotin <temitope.olotin@andela.com>
 * @license  <https://opensource.org/license/MIT> MIT
 */
namespace Laztopaz\EmojiRestfulAPI;

class UserController
{
    /**
     * This method creates a user account
     *
     * @param $data
     *
     * @return int $statuscode
     */
    public function createUser(array $data)
    {
        if (is_array($data)) {
            $passwordHashed = password_hash($data['password'], PASSWORD_BCRYPT);

            $user = User::create(['firstname' => $data['firstname'], 'lastname' => $data['lastname'], 'username' => $data['username'], 'password' => $passwordHashed, 'email' => $data['email'], 'created_at' => date('Y-m-d h:i:s'), 'updated_at' => date('Y-m-d h:i:s')]);

            if ($user->id) {
                return json_encode(['statuscode' => 201, 'response' => 'Created']);
            }

            return json_encode(['statuscode' => 500, 'response' => 'Internal error']);
        }
    }
}
