<?php

namespace App\DataFixtures;

use App\Entity\Token;
use App\Entity\User;
use App\Service\TokenService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    /** @var TokenService  */
    private TokenService $tokenService;

    public function __construct(TokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 4; $i++) {

            $token = new Token();
            $token->setCode($this->tokenService->tokenCode());

            $manager->persist($token);

            $user = new User();
            $user
                ->setEmail("email$i@fixture.fr")
                ->setPassword("password$i")
                ->addToken($token)
            ;

            $manager->persist($user);
        }

        $manager->flush();
    }
}
