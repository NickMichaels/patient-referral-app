<?php

namespace App\Entity;

use App\Repository\PatientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PatientRepository::class)]
class Patient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private array $data = [];

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 50)]
    private ?string $phone = null;

    /**
     * @var Collection<int, PatientReferral>
     */
    #[ORM\OneToMany(targetEntity: PatientReferral::class, mappedBy: 'patient')]
    private Collection $patientReferrals;

    public function __construct()
    {
        $this->patientReferrals = new ArrayCollection();
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

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

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

    /**
     * @return Collection<int, PatientReferral>
     */
    public function getPatientReferrals(): Collection
    {
        return $this->patientReferrals;
    }

    public function addPatientReferral(PatientReferral $patientReferral): static
    {
        if (!$this->patientReferrals->contains($patientReferral)) {
            $this->patientReferrals->add($patientReferral);
            $patientReferral->setPatient($this);
        }

        return $this;
    }

    public function removePatientReferral(PatientReferral $patientReferral): static
    {
        if ($this->patientReferrals->removeElement($patientReferral)) {
            // set the owning side to null (unless already changed)
            if ($patientReferral->getPatient() === $this) {
                $patientReferral->setPatient(null);
            }
        }

        return $this;
    }
}
