<?php


namespace App\Service;

use App\Entity\User;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

/**
 * Class EmailService
 */
class EmailService
{
    const URL = "http://localhost:8082/api/v1/user/";
    const URI = "/validation";

    /** @var MailerInterface $mailer */
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param string $email
     * @param User $user
     *
     * @throws TransportExceptionInterface
     */
    public function sendAnEmail(User $user)
    {
        $mail = (new Email())
            ->from('hello@test.fr')
            ->to($user->getEmail())
            ->subject('Confirm your account.')
            ->html($this->getHtml($user))
            ;

            $this->mailer->send($mail);
    }

    /**
     * @param User $user
     *
     * @return string
     */
    private function getBody(User $user)
    {
        $tokenCode = $user->getTokens()->first()->getCode();

        $email = $user->getEmail();

        return "Hello, thank you for your subscribed with your $email.
                Please confirm your account with this Code : $tokenCode.
                Its available just during 1min.";
    }

    /**
     * @param User $user
     *
     * @return string
     */
    private function getHtml(User $user)
    {
        $body = $this->getBody($user);
        $url = self::URL . $user->getId() . self::URI;

        return "$body Please, click here : <a href='$url'>Activation link</a>";
    }
}