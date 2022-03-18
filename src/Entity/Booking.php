<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookingRepository::class)]
class Booking
{
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_DECLINED = 'declined';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_IN_PROGRESS = 'in_progress';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Hotel::class, inversedBy: 'bookings')]
    #[ORM\JoinColumn(nullable: false)]
    private $hotel;

    #[ORM\ManyToOne(targetEntity: Suite::class, inversedBy: 'bookings')]
    #[ORM\JoinColumn(nullable: false)]
    private $suite;

    #[ORM\Column(type: 'datetime_immutable')]
    private $begin_at;

    #[ORM\Column(type: 'datetime_immutable')]
    private $end_at;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'bookings')]
    private $user;

    #[ORM\Column(type: 'string', length: 255)]
    private $status = self::STATUS_IN_PROGRESS;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHotel(): ?Hotel
    {
        return $this->hotel;
    }

    public function setHotel(?Hotel $hotel): self
    {
        $this->hotel = $hotel;

        return $this;
    }

    public function getSuite(): ?Suite
    {
        return $this->suite;
    }

    public function setSuite(?Suite $suite): self
    {
        $this->suite = $suite;

        return $this;
    }

    public function getBeginAt(): ?\DateTimeImmutable
    {
        return $this->begin_at;
    }

    public function setBeginAt(\DateTimeImmutable $begin_at): self
    {
        $this->begin_at = $begin_at;

        return $this;
    }

    public function getEndAt(): ?\DateTimeImmutable
    {
        return $this->end_at;
    }

    public function setEndAt(\DateTimeImmutable $end_at): self
    {
        $this->end_at = $end_at;

        return $this;
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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
