<?php

namespace Rodrifarias\Blog\Domain\UseCase\User\Repository;

use Rodrifarias\Blog\Domain\UseCase\User\Entity\User as UserEntityDomain;
use Rodrifarias\Blog\Domain\UseCase\User\Exception\UserNotFoundException;
use Rodrifarias\Blog\Infra\Database\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class UserRepositoryDatabase implements UserRepositoryInterface
{
    private EntityRepository $userRepository;

    public function __construct(private EntityManagerInterface $entityManager)
    {
        $this->userRepository = $this->entityManager->getRepository(User::class);
    }

    public function save(UserEntityDomain $userDomainEntity): void
    {
        $user = new User();
        $user->setId($userDomainEntity->id());
        $user->setName($userDomainEntity->name());
        $user->setEmail($userDomainEntity->email());
        $user->setPassword($userDomainEntity->password());

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function findByEmail(string $email): ?User
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);
        return $user ?? null;
    }

    /**
     * @throws UserNotFoundException
     */
    public function findById(string $id): User
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    /**
     * @return User[]
     */
    public function findAll(): array
    {
        return $this->userRepository->findAll();
    }

    public function update(string $id, string $name, ?string $email = null): void
    {
        $user = $this->findById($id);
        $user->setName($name);

        if ($email) {
            $user->setEmail($email);
        }

        $this->entityManager->flush();
    }

    public function delete(string $id): void
    {
        $user = $this->userRepository->find($id);

        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}
