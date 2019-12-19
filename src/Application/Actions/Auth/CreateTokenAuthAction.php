<?php
declare(strict_types=1);

namespace App\Application\Actions\Auth;

use App\Domain\User\User;
use Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface as Response;
use Codes50\Validator;
use Slim\Exception\HttpUnauthorizedException;

class CreateTokenAuthAction extends AuthAction
{
    /**
     * {@inheritdoc}
     * @throws HttpUnauthorizedException
     */
    protected function action(): Response
    {
        $params = $this->getFormData();
        $credentials = [
            "username" => $params->username,
            "password" => $params->password
        ];

        $validation = Validator::make($credentials, [
            "username" => [
                Validator::ATTR_TYPE => Validator::TYPE_STRING,
                Validator::ATTR_REQUIRED => true,
                Validator::ATTR_MAX_LENGTH => 255,
                Validator::ATTR_MIN_LENGTH => 3
            ],
            "password" => [
                Validator::ATTR_TYPE => Validator::TYPE_STRING,
                Validator::ATTR_REQUIRED => true
            ]
        ]);

        if ($validation->validate()) {
            $user = $this->userRepository->findUserOfUsernamePassword($credentials["username"], $credentials["password"]);
            if ($user instanceof User) {
                $token = $this->createToken($user);

                $this->logger->info("User of id " . $user->getId() . " auth token create");

                $data = [
                    "token" => $token,
                    "user" => $user
                ];
                return $this->respondWithData($data);
            } else {
                throw new HttpUnauthorizedException($this->request, "Username or password incorrect");
            }
        } else {
            throw new HttpUnauthorizedException($this->request, "Validation Failed: " . json_encode($validation->error->all()));
        }
    }

    /**
     * @param User $user
     * @param string $exp_date
     * @return string
     */
    private function createToken(User $user, string $exp_date = "+1 hours"): string
    {
        $timezone = "";
        if (date_default_timezone_get()) {
            $timezone = date_default_timezone_get();
        } elseif (ini_get('date.timezone')) {
            $timezone = ini_get('date.timezone');
        }

        $token = [
            "iss" => "http://localhost:8000",
            "iat" => time(),
            "nbf" => time(),
            "exp" => strtotime($exp_date),
            "timezone" => $timezone,
            "data" => [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
            ],
        ];
        $jwt = JWT::encode($token, $this->secret_key);
        return $jwt;
    }
}
