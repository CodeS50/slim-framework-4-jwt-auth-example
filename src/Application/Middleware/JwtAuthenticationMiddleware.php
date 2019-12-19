<?php


namespace App\Application\Middleware;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Exception\HttpUnauthorizedException;
use Tuupola\Middleware\JwtAuthentication;

class JwtAuthenticationMiddleware implements Middleware
{
    /**
     * @var string
     */
    private $secret_key;

    /**
     * JwtAuthenticationMiddleware constructor.
     * @param string $secret_key
     */
    public function __construct(string $secret_key)
    {
        $this->secret_key = $secret_key;
    }

    /**
     * {@inheritdoc}
     */
    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        $ja = new JwtAuthentication([
            "secret" => $this->secret_key,
            "secure" => false,
            "algorithm" => ["HS256"],
            "attribute" => false,
            "error" => function (ResponseInterface $response, $arguments) use ($request) {
                throw new HttpUnauthorizedException($request, $arguments["message"]);
            },
            "before" => function (Request $request, $arguments) {
                $token = $arguments["decoded"];
                $data = $token["data"] ?? false;
                $request = $request->withAttribute("user", $data);
                return $request;
            }
        ]);

        return $ja->process($request, $handler);
    }
}