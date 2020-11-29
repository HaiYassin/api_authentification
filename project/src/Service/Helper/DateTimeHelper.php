<?php


namespace App\Service\Helper;

/**
 * Class DateTimeHelper
 */
class DateTimeHelper
{
    /**
     * @throws \Exception
     *
     * @return \DateTime
     */
    public function getNowDateTime(): \DateTime
    {
        return new \DateTime('now');
    }

    /**
     * @param \DateTime $dateTime
     * @param \DateTime $dateTimeNow
     *
     * @return \DateInterval
     */
    public function getDateInterval(\DateTime $dateTime, \DateTime $dateTimeNow): \DateInterval
    {
        $dateInterval = $dateTimeNow->diff($dateTime);

        return $dateInterval;
    }
}