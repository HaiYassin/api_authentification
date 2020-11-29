<?php


namespace App\Service\Helper;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class JsonResponseHelper
 */
class JsonResponseHelper
{
    CONST SUCCESS_EMAIL_WAS_SENT        = 'An email was sent to enable the account.';
    CONST SUCCESS_USER_ENABLE           = 'Your account is now enable.';

    CONST ERROR_ON_DATA                 = 'Error, please check your data.';
    CONST ERROR_USER_NOT_FOUND          = 'Error, there is not an user found.';
    const ERROR_MESSAGE_MORE_THAN_REF   = 'Error, End of process because this token time is not valid.';

    /**
     * @param string $data
     * @param int $code
     *
     * @return JsonResponse
     */
    public function getJsonResponse(string $data, int $code)
    {
        return new JsonResponse(
            $data,
            $code,
            [],
            true
        );
    }
}