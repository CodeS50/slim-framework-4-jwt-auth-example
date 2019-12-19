<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\User;

use App\Domain\User\User;
use App\Domain\User\UserNotFoundException;
use App\Domain\User\UserRepository;

class InMemoryUserRepository implements UserRepository
{
    /**
     * @var User[]
     */
    private $users;

    /**
     * InMemoryUserRepository constructor.
     *
     * @param array|null $users
     */
    public function __construct(array $users = null)
    {
        $this->users = $users ?? [
                1 => new User(1, 'bill.gates', '123456', 'Bill', 'Gates'),
                2 => new User(2, 'steve.jobs', '123456', 'Steve', 'Jobs'),
                3 => new User(3, 'mark.zuckerberg', '123456', 'Mark', 'Zuckerberg'),
                4 => new User(4, 'evan.spiegel', '123456', 'Evan', 'Spiegel'),
                5 => new User(5, 'jack.dorsey', '123456', 'Jack', 'Dorsey'),
            ];
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
        return array_values($this->users);
    }

    /**
     * {@inheritdoc}
     */
    public function findUserOfId(int $id): User
    {
        if (!isset($this->users[$id])) {
            throw new UserNotFoundException();
        }

        return $this->users[$id];
    }

    /**
     * @inheritDoc
     */
    public function findUserOfUsernamePassword(string $username, string $password): ?User
    {
        $has_users = array_filter($this->users, function (User $user) use ($username, $password) {
            return ($user->getUsername() === $username && $user->getPassword() === $password);
        });

        if(count($has_users) > 0) {
            return array_shift($has_users);
        } else {
            return null;
        }
    }
}
