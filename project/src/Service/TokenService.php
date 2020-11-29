<?php


namespace App\Service;


use App\Entity\Token;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class TokenService
 */
class TokenService
{
    /** @var EntityManagerInterface  */
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param User $user
     *
     * @throws \Exception
     *
     * @return Token
     */
    public function create(User $user)
    {
        $token = new Token();

        $token->setCode($this->tokenCode());
        $user->addToken($token);

        $this->entityManager->persist($token);

        return $token;
    }

    /**
     * @param int $length
     *
     * @return string
     */
    public function tokenCode(int $length = 4)
    {
        $digit = [];
        for ($i=0; $i<$length; $i++) {
            $digit[] = rand(0, 9);
        }

        return implode($digit);
    }
}