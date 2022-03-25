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
    const STATUS_DONE = 'done';

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
    private $beginAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private $endAt;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'bookings')]
    private $user;

    #[ORM\Column(type: 'string', length: 255)]
    private $status = self::STATUS_IN_PROGRESS;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $totalPrice;

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
        return $this->beginAt;
    }

    public function setBeginAt(\DateTimeImmutable $beginAt): self
    {
        $this->beginAt = $beginAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeImmutable
    {
        return $this->endAt;
    }

    public function setEndAt(\DateTimeImmutable $endAt): self
    {
        $this->endAt = $endAt;

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

    public function isAllowedToCancel(): bool
    {
        if (null === $this->getBeginAt()) {
            return false;
        }

        $now = new \DateTimeImmutable();
        $diffInDays = (int) $now->diff($this->getBeginAt())->format("%r%a");

        if ($this->getStatus() === self::STATUS_ACCEPTED && $diffInDays >= 3) {
            return true;
        }

        return false;
    }

    public function getNightsNumber(): int
    {
        if (null === $this->getBeginAt() || null === $this->getEndAt()) {
            return false;
        }

        $diffInDays = (int) $this->getBeginAt()->diff($this->getEndAt())->format("%r%a");

        return ($diffInDays > 0) ? $diffInDays : 0;
    }

    public function getTotalPrice(): ?int
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(?int $totalPrice): self
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getTotalPriceInEuros(): ?float
    {
        return $this->totalPrice / 100;
    }
}
