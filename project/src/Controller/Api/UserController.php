<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Service\EmailService;
use App\Service\Helper\JsonResponseHelper;
use App\Service\TokenService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 *
 * @Route("/api/v1", name="api_v1_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/user", name="index_user", methods={"GET"})
     */
    public function indexAction()
    {
        return $this->render('User/index.html.twig');
    }

    /**
     * @Route("/user/register", name="register_user", methods={"POST"})
     *
     * @param Request $request
     * @param UserService $userService
     * @param TokenService $tokenService
     * @param EmailService $emailService
     * @param JsonResponseHelper $jsonResponseHelper
     *
     * @throws TransportExceptionInterface
     * @throws \Exception
     *
     * @return JsonResponse
     */
    public function registerAction(
        Request $request,
        UserService $userService,
        TokenService $tokenService,
        EmailService $emailService,
        JsonResponseHelper $jsonResponseHelper
    )
    {
        $data = [];

        $data['email'] = $request->request->get('_email', null);
        $data['password'] = $request->request->get('_password', null);

        if (empty($data['email']) || empty($data['password'])) {
            return $jsonResponseHelper
                ->getJsonResponse(JsonResponseHelper::ERROR_ON_DATA,Response::HTTP_NOT_FOUND);
        }

        $user  = $userService->create($data);
        $tokenService->create($user);

        $this->getDoctrine()->getManager()->flush();

        $emailService->sendAnEmail($user);

        return $jsonResponseHelper
            ->getJsonResponse(JsonResponseHelper::SUCCESS_EMAIL_WAS_SENT, Response::HTTP_CREATED);
    }

    /**
     * @Route("/user/{userId}/validation", name="validation_user", methods={"GET"})
     *
     * @param int $userId
     * @param UserService $userService
     * @param JsonResponseHelper $jsonResponseHelper
     *
     * @throws \Exception
     *
     * @return JsonResponse
     */
    public function validationUserAction(
        int $userId,
        UserService $userService,
        JsonResponseHelper $jsonResponseHelper
    )
    {
        /** @var User|null $user */
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy([
                'id' => $userId
            ]);

        if (empty($user)) {
            return $jsonResponseHelper
                ->getJsonResponse(JsonResponseHelper::ERROR_USER_NOT_FOUND, Response::HTTP_FORBIDDEN);
        }

        $status = $userService->enableUser($user);

        if (!$status) {
            return $jsonResponseHelper
                ->getJsonResponse(JsonResponseHelper::ERROR_MESSAGE_MORE_THAN_REF, Response::HTTP_BAD_REQUEST);
        }

        $this->getDoctrine()->getManager()->flush();

        return $jsonResponseHelper
            ->getJsonResponse(JsonResponseHelper::SUCCESS_USER_ENABLE, Response::HTTP_OK);
    }
}