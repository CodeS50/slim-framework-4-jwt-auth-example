<?php
declare(strict_types=1);

namespace App\Application\Actions\Auth;

use App\Application\Actions\Action;
use App\Domain\User\UserRepository;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

abstract class AuthAction extends Action
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var string
     */
    protected $secret_key;

    /**
     * @param LoggerInterface $logger
     * @param UserRepository  $userRepository
     */
    public function __construct(LoggerInterface $logger, UserRepository $userRepository, ContainerInterface $c)
    {
        parent::__construct($logger);
        $this->userRepository = $userRepository;
        $this->secret_key = (string)$c->get('secret_key');
    }
}
