<?php
/**
 * @author   Temitope Olotin <temitope.olotin@andela.com>
 * @license  <https://opensource.org/license/MIT> MIT
 */
namespace Laztopaz\EmojiRestfulAPI;

use Exception;
use Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Middleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        $loadEnv = DatabaseConnection::loadEnv();

        $authHeader = $request->getHeader('HTTP_AUTHORIZATION');

        try {
            if (is_array($authHeader) && ! empty($authHeader)) {
                $secretKey = base64_decode(getenv('secret'));
                $jwt = json_decode($authHeader[0], true);
                //decode the JWT using the key from config

                $decodedToken = JWT::decode($jwt['jwt'], $secretKey, ['HS512']);

                return $next($request, $response);
            }
        } catch (Exception $e) {
            return $response->withJson(['status' => $e->getMessage()], 401);
        }

        return $response->withJson(['message' => 'User unauthorized due to invalid token'], 401);
    }
}
