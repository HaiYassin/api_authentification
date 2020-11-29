<?php

namespace App\Tests\Functional\Controller;

use App\Entity\User;
use App\Service\Helper\JsonResponseHelper;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class UserControllerTest
 *
 * @group Functional
 * @group UserControllerTest
 */
class UserControllerTest extends WebTestCase
{
    /** @var EntityManagerInterface $em */
    private static EntityManagerInterface $em;

    /** @var KernelBrowser  */
    private static KernelBrowser $client;

    /** @var UserService|MockObject  $userService */
    private $userService;

    protected function setUp()
    {
        parent::setUp();

        self::$client = static::createClient();
        self::$container = static::$client->getContainer();
        self::$em = self::$container->get('doctrine')->getManager();
        self::$em->beginTransaction();

        $this->userService = $this->createMock(UserService::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        self::$em->getConnection()->rollBack();
    }

    public function testSuccessRegisterAction()
    {
        $client = static::$client;

        $client->request(
            Request::METHOD_POST,
            '/api/v1/user/register',
            [
                '_email' => 'test@test.fr',
                '_password' => 'test'
            ]
        );

        $request = $client->getResponse();

        $this->assertResponseHeaderSame('content-type', 'application/json');
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED, $request->getStatusCode());
        $this->assertEquals(JsonResponseHelper::SUCCESS_EMAIL_WAS_SENT, $request->getContent());
    }

    public function testErrorRegisterWithEmptyData()
    {
        $client = static::$client;

        $client->request(
            Request::METHOD_POST,
            '/api/v1/user/register'
        );

        $request = $client->getResponse();

        $this->assertResponseHeaderSame('content-type', 'application/json');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND, $request->getStatusCode());
        $this->assertEquals(JsonResponseHelper::ERROR_ON_DATA, $request->getContent());
    }

    public function testSuccessValidationUser()
    {
        $client = static::$client;

        /** @var User[] $users */
        $users = self::$em->getRepository(User::class)->findAll();

        $user = $users[0];

        $user
            ->setCreatedAt(new \DateTime('now'))
            ->setEnabled(new \DateTime('now'))
        ;

        self::$em->persist($user);
        self::$em->flush();

        $client->request(
            Request::METHOD_GET,
            '/api/v1/user/'.$user->getId().'/validation'
        );

        $request = $client->getResponse();

        $this->assertResponseHeaderSame('content-type', 'application/json');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK, $request->getStatusCode());
        $this->assertEquals(JsonResponseHelper::SUCCESS_USER_ENABLE, $request->getContent());

    }

    public function testErrorValidationUser()
    {
        $client = static::$client;

        /** @var User[] $users */
        $users = self::$em->getRepository(User::class)->findAll();

        $user = $users[0];

        $client->request(
            Request::METHOD_GET,
            '/api/v1/user/'.$user->getId().'/validation'
        );

        $request = $client->getResponse();

        $this->assertResponseHeaderSame('content-type', 'application/json');
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST, $request->getStatusCode());
        $this->assertEquals(JsonResponseHelper::ERROR_MESSAGE_MORE_THAN_REF, $request->getContent());
    }
}