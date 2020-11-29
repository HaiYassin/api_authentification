<?php

namespace App\Service;

use App\Entity\User;
use App\Service\Helper\DateTimeHelper;
use App\Service\Helper\JsonResponseHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserService
 */
class UserService
{
    const FORMAT_INTERVAL_LIST = ['days', 'y', 'm', 'd', 'h', 'i'];
    const ONE = 1;

    const MESSAGE_DATA_IS_EMPTY = 'End of process, because data is empty.';

    /** @var EntityManagerInterface */
    private EntityManagerInterface $entityManager;
    /** @var JsonResponseHelper $jsonResponseHelper **/
    private JsonResponseHelper $jsonResponseHelper;
    /** @var DateTimeHelper */
    private DateTimeHelper $dateTimeHelper;

    /**
     * UserService constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param JsonResponseHelper $jsonResponseHelper
     * @param DateTimeHelper $dateTimeHelper
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        JsonResponseHelper $jsonResponseHelper,
        DateTimeHelper $dateTimeHelper)
    {
        $this->entityManager = $entityManager;
        $this->jsonResponseHelper = $jsonResponseHelper;
        $this->dateTimeHelper = $dateTimeHelper;
    }

    /**
     * @param array $data
     * 
     * @return User|JsonResponse
     */
    public function create(array $data)
    {
        if (empty($data)) {
            return $this->jsonResponseHelper
                ->getJsonResponse(self::MESSAGE_DATA_IS_EMPTY, Response::HTTP_BAD_REQUEST);
        }
        
        $user = new User();
        $user
            ->setEmail($data['email'])
            ->setPassword($data['password'])
            ;
        
        $this->entityManager->persist($user);

        return $user;
    }

    /**
     * @param User $user
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function enableUser(User $user)
    {
        if ($this->isUserAlreadyValidated($user)) {
            return false;
        }

        $tokenCreateAt = $user->getTokens()->first()->getCreateAt();
        $dateTimeNow = $this->dateTimeHelper->getNowDateTime();
        $dateInterval = $this->dateTimeHelper->getDateInterval($tokenCreateAt, $dateTimeNow);

        if(!$this->checkRuleOnDateInterval($dateInterval)) {
            return false;
        }

        $user->setEnabled($dateTimeNow);
        $token = $user->getTokens()->first()->setValidateAt($dateTimeNow);

        $this->entityManager->persist($user);
        $this->entityManager->persist($token);

        return true;
    }

    /**
     * @param \DateInterval $dateInterval
     *
     * @return bool
     */
    private function checkRuleOnDateInterval(\DateInterval $dateInterval): bool
    {
        $intervalFormatsList = self::FORMAT_INTERVAL_LIST;

        foreach ($intervalFormatsList as $intervalFormat){

            if ($this->isGranderThan($dateInterval->$intervalFormat)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param int $time
     *
     * @return bool
     */
    private function isGranderThan(int $time): bool
    {
        return ($time > self::ONE) ? true : false;
    }

    private function isUserAlreadyValidated(User $user) {
        $tokenStatus = $user->getTokens()->first()->getValidateAt();

        if (empty($user->getUpdatedAt()) || empty($tokenStatus)){
            return false;
        }

        return true;
    }
}