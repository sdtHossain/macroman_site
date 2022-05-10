<?php

namespace App\Entity;

use App\Repository\ContactUsMessageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContactUsMessageRepository::class)
 */
class ContactUsMessage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $website;

    /**
     * @ORM\Column(type="text")
     */
    private $message;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $spamScore;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getName(): ?string
    {
        return $this->fname .' '. $this->lname;
    }

    public function getfName(): ?string
    {
        return $this->fname;
    }

    public function setfName(string $name): self
    {
        $this->fname = $name;

        return $this;
    }

    public function getlName(): ?string
    {
        return $this->lname;
    }

    public function setlName(string $name): self
    {
        $this->lname = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getSpamScore(): ?int
    {
        return $this->spamScore;
    }

    public function setSpamScore(int $spamScore): self
    {
        $this->spamScore = $spamScore;

        return $this;
    }
}
