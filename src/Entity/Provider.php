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

    #[ORM\Column(length: 50)]
    private ?string $phone = null;

    /**
     * @var Collection<int, PatientReferral>
     */
    #[ORM\OneToMany(targetEntity: PatientReferral::class, mappedBy: 'sending_provider')]
    private Collection $patientReferralsSent;

    /**
     * @var Collection<int, PatientReferral>
     */
    #[ORM\OneToMany(targetEntity: PatientReferral::class, mappedBy: 'receiving_provider')]
    private Collection $patientReferralsReceived;

    public function __construct()
    {
        $this->practicioners = new ArrayCollection();
        $this->patientReferralsSent = new ArrayCollection();
        $this->patientReferralsReceived = new ArrayCollection();
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

    /**
     * @return Collection<int, PatientReferral>
     */
    public function getPatientReferralsSent(): Collection
    {
        return $this->patientReferralsSent;
    }

    public function addPatientReferralsSent(PatientReferral $patientReferralsSent): static
    {
        if (!$this->patientReferralsSent->contains($patientReferralsSent)) {
            $this->patientReferralsSent->add($patientReferralsSent);
            $patientReferralsSent->setSendingProvider($this);
        }

        return $this;
    }

    public function removePatientReferralsSent(PatientReferral $patientReferralsSent): static
    {
        if ($this->patientReferralsSent->removeElement($patientReferralsSent)) {
            // set the owning side to null (unless already changed)
            if ($patientReferralsSent->getSendingProvider() === $this) {
                $patientReferralsSent->setSendingProvider(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PatientReferral>
     */
    public function getPatientReferralsReceived(): Collection
    {
        return $this->patientReferralsReceived;
    }

    public function addPatientReferralsReceived(PatientReferral $patientReferralsReceived): static
    {
        if (!$this->patientReferralsReceived->contains($patientReferralsReceived)) {
            $this->patientReferralsReceived->add($patientReferralsReceived);
            $patientReferralsReceived->setReceivingProvider($this);
        }

        return $this;
    }

    public function removePatientReferralsReceived(PatientReferral $patientReferralsReceived): static
    {
        if ($this->patientReferralsReceived->removeElement($patientReferralsReceived)) {
            // set the owning side to null (unless already changed)
            if ($patientReferralsReceived->getReceivingProvider() === $this) {
                $patientReferralsReceived->setReceivingProvider(null);
            }
        }

        return $this;
    }

}
