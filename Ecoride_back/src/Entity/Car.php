<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarRepository::class)]
class Car
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 32)]
    private ?string $Brand_name = null;

    #[ORM\Column(length: 32)]
    private ?string $model_name = null;

    #[ORM\Column(length: 32)]
    private ?string $color = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $first_registration_date = null;

    #[ORM\Column]
    private ?bool $electric = null;

    #[ORM\OneToMany(targetEntity: Ride::class, mappedBy: 'car_id')]
    private Collection $rides;

    #[ORM\ManyToOne(inversedBy: 'cars')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user_id = null;

    #[ORM\Column(length: 32)]
    private ?string $registration_number = null;

    public function __construct()
    {
        $this->rides = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrandName(): ?string
    {
        return $this->Brand_name;
    }

    public function setBrandName(string $Brand_name): static
    {
        $this->Brand_name = $Brand_name;

        return $this;
    }

    public function getModelName(): ?string
    {
        return $this->model_name;
    }

    public function setModelName(string $model_name): static
    {
        $this->model_name = $model_name;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getFirstRegistrationDate(): ?\DateTimeInterface
    {
        return $this->first_registration_date;
    }

    public function setFirstRegistrationDate(\DateTimeInterface $first_registration_date): static
    {
        $this->first_registration_date = $first_registration_date;

        return $this;
    }

    public function isElectric(): ?bool
    {
        return $this->electric;
    }

    public function setElectric(bool $electric): static
    {
        $this->electric = $electric;

        return $this;
    }

    /**
     * @return Collection<int, Ride>
     */
    public function getRides(): Collection
    {
        return $this->rides;
    }

    public function addRide(Ride $ride): static
    {
        if (!$this->rides->contains($ride)) {
            $this->rides->add($ride);
            $ride->setCarId($this);
        }

        return $this;
    }

    public function removeRide(Ride $ride): static
    {
        if ($this->rides->removeElement($ride)) {
            // set the owning side to null (unless already changed)
            if ($ride->getCarId() === $this) {
                $ride->setCarId(null);
            }
        }

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getRegistrationNumber(): ?string
    {
        return $this->registration_number;
    }

    public function setRegistrationNumber(string $registration_number): static
    {
        $this->registration_number = $registration_number;

        return $this;
    }
}
