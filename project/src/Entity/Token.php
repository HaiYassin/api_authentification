<?php

namespace App\Entity;

use App\Repository\TokenRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TokenRepository::class)
 *
 * @ORM\Table(name="`token`")
 *
 * @ORM\HasLifecycleCallbacks()
 */
class Token
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=4)
     */
    private $code;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $validateAt;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="tokens")
     * @ORM\JoinColumn(name="user_token", referencedColumnName="id")
     */
    private $user;

    /**
     * Token constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        $this->createAt = new \DateTime();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return Token
     */
    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    /**
     * @param \DateTimeInterface $createAt
     *
     * @return Token
     */
    public function setCreateAt(\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getValidateAt(): ?\DateTimeInterface
    {
        return $this->validateAt;
    }

    /**
     * @param \DateTimeInterface|null $validateAt
     *
     * @return Token
     */
    public function setValidateAt(?\DateTimeInterface $validateAt): self
    {
        $this->validateAt = $validateAt;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     *
     * @return Token
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
