<?php

namespace App\Entity;

use App\Repository\RideRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RideRepository::class)]
class Ride
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $departure_time = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $arrival_time = null;

    #[ORM\Column(length: 32)]
    private ?string $departure_city = null;

    #[ORM\Column(length: 32)]
    private ?string $arrival_city = null;

    #[ORM\Column(length: 32)]
    private ?string $status = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $number_of_seats = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\ManyToOne(inversedBy: 'rides')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Car $car_id = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'rides')]
    private Collection $passenger_id;

    public function __construct()
    {
        $this->passenger_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDepartureTime(): ?\DateTimeInterface
    {
        return $this->departure_time;
    }

    public function setDepartureTime(\DateTimeInterface $departure_time): static
    {
        $this->departure_time = $departure_time;

        return $this;
    }

    public function getArrivalTime(): ?\DateTimeInterface
    {
        return $this->arrival_time;
    }

    public function setArrivalTime(\DateTimeInterface $arrival_time): static
    {
        $this->arrival_time = $arrival_time;

        return $this;
    }

    public function getDepartureCity(): ?string
    {
        return $this->departure_city;
    }

    public function setDepartureCity(string $departure_city): static
    {
        $this->departure_city = $departure_city;

        return $this;
    }

    public function getArrivalCity(): ?string
    {
        return $this->arrival_city;
    }

    public function setArrivalCity(string $arrival_city): static
    {
        $this->arrival_city = $arrival_city;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getNumberOfSeats(): ?int
    {
        return $this->number_of_seats;
    }

    public function setNumberOfSeats(int $number_of_seats): static
    {
        $this->number_of_seats = $number_of_seats;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getCarId(): ?Car
    {
        return $this->car_id;
    }

    public function setCarId(?Car $car_id): static
    {
        $this->car_id = $car_id;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getPassengerId(): Collection
    {
        return $this->passenger_id;
    }

    public function addPassengerId(User $passengerId): static
    {
        if (!$this->passenger_id->contains($passengerId)) {
            $this->passenger_id->add($passengerId);
        }

        return $this;
    }

    public function removePassengerId(User $passengerId): static
    {
        $this->passenger_id->removeElement($passengerId);

        return $this;
    }
}
