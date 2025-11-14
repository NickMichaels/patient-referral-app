<?php

namespace App\Entity;

use App\Repository\ProviderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProviderRepository::class)]
class Provider
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $specialty = null;

    #[ORM\Column(length: 255)]
    private ?string $address_line1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address_line2 = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    private ?string $state = null;

    #[ORM\Column]
    private ?int $zip = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    /**
     * @var Collection<int, Practicioner>
     */
    #[ORM\ManyToMany(targetEntity: Practicioner::class, mappedBy: 'provider')]
    private Collection $practicioners;

    #[ORM\Column(length: 15)]
    private ?string $phone = null;

    #[ORM\OneToOne(mappedBy: 'sending_provider', cascade: ['persist', 'remove'])]
    private ?PatientReferral $patientReferralsSent = null;

    #[ORM\OneToOne(mappedBy: 'receiving_provider', cascade: ['persist', 'remove'])]
    private ?PatientReferral $patientReferralsReceived = null;

    public function __construct()
    {
        $this->practicioners = new ArrayCollection();
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

    public function getSpecialty(): ?string
    {
        return $this->specialty;
    }

    public function setSpecialty(?string $specialty): static
    {
        $this->specialty = $specialty;

        return $this;
    }

    public function getAddressLine1(): ?string
    {
        return $this->address_line1;
    }

    public function setAddressLine1(string $address_line1): static
    {
        $this->address_line1 = $address_line1;

        return $this;
    }

    public function getAddressLine2(): ?string
    {
        return $this->address_line2;
    }

    public function setAddressLine2(string $address_line2): static
    {
        $this->address_line2 = $address_line2;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getZip(): ?int
    {
        return $this->zip;
    }

    public function setZip(int $zip): static
    {
        $this->zip = $zip;

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

    /**
     * @return Collection<int, Practicioner>
     */
    public function getPracticioners(): Collection
    {
        return $this->practicioners;
    }

    public function addPracticioner(Practicioner $practicioner): static
    {
        if (!$this->practicioners->contains($practicioner)) {
            $this->practicioners->add($practicioner);
            $practicioner->addProvider($this);
        }

        return $this;
    }

    public function removePracticioner(Practicioner $practicioner): static
    {
        if ($this->practicioners->removeElement($practicioner)) {
            $practicioner->removeProvider($this);
        }

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

    public function getPatientReferralsSent(): ?PatientReferral
    {
        return $this->patientReferralsSent;
    }

    public function setPatientReferralsSent(PatientReferral $patientReferralsSent): static
    {
        // set the owning side of the relation if necessary
        if ($patientReferralsSent->getSendingProvider() !== $this) {
            $patientReferralsSent->getSendingProvider($this);
        }

        $this->patientReferralsSent = $patientReferralsSent;

        return $this;
    }

    public function getPatientReferralsReceived(): ?PatientReferral
    {
        return $this->patientReferralsReceived;
    }

    public function setPatientReferralsReceived(PatientReferral $patientReferralsReceived): static
    {
        // set the owning side of the relation if necessary
        if ($patientReferralsReceived->getReceivingProvider() !== $this) {
            $patientReferralsReceived->setReceivingProvider($this);
        }

        $this->patientReferralsReceived = $patientReferralsReceived;

        return $this;
    }
}
