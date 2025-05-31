<?php

namespace App\Entity;

use App\Repository\PasswordResetRequestRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PasswordResetRequestRepository::class)]
class PasswordResetRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\Column(type: 'string', length: 128, unique: true)]
    private $token;

    #[ORM\Column(type: 'datetime', nullable: false)]
    private $requestedAt;

    #[ORM\Column(type: 'string', length: 45, nullable: true)]
    private $requestIp;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $resetAt;

    #[ORM\Column(type: 'string', length: 45, nullable: true)]
    private $resetIp;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private $successful = false;

    public function getId(): ?int 
    { 
        return $this->id; 
    }

    public function getUser(): ?User 
    { 
        return $this->user; 
    }

    public function setUser(?User $user): self 
    { 
        $this->user = $user; 
        return $this; 
    }

    public function getToken(): ?string 
    { 
        return $this->token; 
    }

    public function setToken(string $token): self 
    { 
        $this->token = $token; 
        return $this; 
    }

    public function getRequestedAt(): ?\DateTimeInterface 
    { 
        return $this->requestedAt; 
    }

    public function setRequestedAt(\DateTimeInterface $requestedAt): self 
    { 
        $this->requestedAt = $requestedAt; 
        return $this; 
    }

    public function getRequestIp(): ?string 
    { 
        return $this->requestIp; 
    }

    public function setRequestIp(?string $requestIp): self 
    { 
        $this->requestIp = $requestIp; 
        return $this; 
    }

    public function getResetAt(): ?\DateTimeInterface 
    { 
        return $this->resetAt; 
    }

    public function setResetAt(?\DateTimeInterface $resetAt): self 
    { 
        $this->resetAt = $resetAt; 
        return $this; 
    }

    public function getResetIp(): ?string 
    { 
        return $this->resetIp; 
    }

    public function setResetIp(?string $resetIp): self 
    { 
        $this->resetIp = $resetIp; 
        return $this; 
    }

    public function isSuccessful(): bool 
    { 
        return $this->successful; 
    }

    public function setSuccessful(bool $successful): self 
    { 
        $this->successful = $successful; 
        return $this; 
    }
}
