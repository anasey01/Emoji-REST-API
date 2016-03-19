<?php
/**
 * @author   Temitope Olotin <temitope.olotin@andela.com>
 * @license  <https://opensource.org/license/MIT> MIT
 */
namespace Laztopaz\EmojiRestfulAPI;

use Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Middleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        $loadEnv = DatabaseConnection::loadEnv();

        $authHeader = $request->getHeader('token');

        try {
            if (is_array($authHeader) && !empty($authHeader)) {
                $jwtoken = $authHeader[0];

                $secretKey = base64_decode(getenv('secret'));

                $jwt = json_decode($jwtoken, true);

                //decode the JWT using the key from config
                $decodedToken = JWT::decode($jwt['jwt'], $secretKey, ['HS512']);

                return $next($request, $response);
            }
        } catch (\Exception $e) {
            return $response->withJson(['status' => $e->getMessage()], 401);
        }

        return $response->withJson(['status'], 401);
    }
}
