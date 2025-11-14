<?php

namespace App\Entity;

use App\Repository\PracticionerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PracticionerRepository::class)]
class Practicioner
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $job_title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $specialty = null;

    #[ORM\Column(length: 30)]
    private ?string $license_number = null;

    /**
     * @var Collection<int, Provider>
     */
    #[ORM\ManyToMany(targetEntity: Provider::class, inversedBy: 'practicioners')]
    private Collection $provider;

    #[ORM\Column(length: 15)]
    private ?string $phone = null;

    public function __construct()
    {
        $this->provider = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getJobTitle(): ?string
    {
        return $this->job_title;
    }

    public function setJobTitle(string $job_title): static
    {
        $this->job_title = $job_title;

        return $this;
    }

    public function getSpecialty(): ?string
    {
        return $this->specialty;
    }

    public function setSpecialty(?string $specialty): static
    {
        $this->specialty = $specialty;

        return $this;
    }

    public function getLicenseNumber(): ?string
    {
        return $this->license_number;
    }

    public function setLicenseNumber(string $license_number): static
    {
        $this->license_number = $license_number;

        return $this;
    }

    /**
     * @return Collection<int, Provider>
     */
    public function getProvider(): Collection
    {
        return $this->provider;
    }

    public function addProvider(Provider $provider): static
    {
        if (!$this->provider->contains($provider)) {
            $this->provider->add($provider);
        }

        return $this;
    }

    public function removeProvider(Provider $provider): static
    {
        $this->provider->removeElement($provider);

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }
}
