<?php

namespace App\Entity;

use App\Repository\ProviderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: ProviderRepository::class)]
class Provider implements JsonSerializable
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
    private ?string $addressLine1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $addressLine2 = null;

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
    #[ORM\OneToMany(targetEntity: PatientReferral::class, mappedBy: 'sendingProvider')]
    private Collection $patientReferralsSent;

    /**
     * @var Collection<int, PatientReferral>
     */
    #[ORM\OneToMany(targetEntity: PatientReferral::class, mappedBy: 'receivingProvider')]
    private Collection $patientReferralsReceived;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $updatedAt = null;

    /**
     * @var Collection<int, Appointment>
     */
    #[ORM\OneToMany(targetEntity: Appointment::class, mappedBy: 'provider')]
    private Collection $appointments;

    public function __construct()
    {
        $this->practicioners = new ArrayCollection();
        $this->patientReferralsSent = new ArrayCollection();
        $this->patientReferralsReceived = new ArrayCollection();
        $this->appointments = new ArrayCollection();
    }

    /**
     * Serialize the Patient Referral
     *
     * @return array <string, int|string|null>
     */
    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'specialty' => $this->getSpecialty(),
            'addressLine1' => $this->getAddressLine1(),
            'addressLine2' => $this->getAddressLine2(),
            'city' => $this->getCity(),
            'state' => $this->getState(),
            'email' => $this->getEmail()
        ];
    }

    #[ORM\PrePersist]
    public function updateCreatedAt(): void
    {
        $this->createdAt = new \DateTime();
    }

    #[ORM\PreUpdate]
    public function updateUpdatedAt(): void
    {
        $this->updatedAt = new \DateTime();
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
        return $this->addressLine1;
    }

    public function setAddressLine1(string $addressLine1): static
    {
        $this->addressLine1 = $addressLine1;

        return $this;
    }

    public function getAddressLine2(): ?string
    {
        return $this->addressLine2;
    }

    public function setAddressLine2(string $addressLine2): static
    {
        $this->addressLine2 = $addressLine2;

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

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, Appointment>
     */
    public function getAppointments(): Collection
    {
        return $this->appointments;
    }

    public function addAppointment(Appointment $appointment): static
    {
        if (!$this->appointments->contains($appointment)) {
            $this->appointments->add($appointment);
            $appointment->setProvider($this);
        }

        return $this;
    }

    public function removeAppointment(Appointment $appointment): static
    {
        if ($this->appointments->removeElement($appointment)) {
            // set the owning side to null (unless already changed)
            if ($appointment->getProvider() === $this) {
                $appointment->setProvider(null);
            }
        }

        return $this;
    }
}
