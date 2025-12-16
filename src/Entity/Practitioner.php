<?php

namespace App\Entity;

use App\Repository\PractitionerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: PractitionerRepository::class)]
class Practitioner implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $jobTitle = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $specialty = null;

    #[ORM\Column(length: 30)]
    private ?string $licenseNumber = null;

    /**
     * @var Collection<int, Provider>
     */
    #[ORM\ManyToMany(targetEntity: Provider::class, inversedBy: 'practitioners')]
    private Collection $provider;

    #[ORM\Column(length: 50)]
    private ?string $phone = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    /**
     * @var Collection<int, PatientReferral>
     */
    #[ORM\OneToMany(targetEntity: PatientReferral::class, mappedBy: 'sendingPractitioner')]
    private Collection $patientReferralsSent;

    /**
     * @var Collection<int, PatientReferral>
     */
    #[ORM\OneToMany(targetEntity: PatientReferral::class, mappedBy: 'receivingPractitioner')]
    private Collection $patientReferralsReceived;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $updatedAt = null;

    /**
     * @var Collection<int, PractitionerSchedule>
     */
    #[ORM\OneToMany(targetEntity: PractitionerSchedule::class, mappedBy: 'practitioner')]
    private Collection $practitionerSchedules;

    /**
     * @var Collection<int, Appointment>
     */
    #[ORM\OneToMany(targetEntity: Appointment::class, mappedBy: 'practitioner')]
    private Collection $appointments;

    public function __construct()
    {
        $this->provider = new ArrayCollection();
        $this->patientReferralsSent = new ArrayCollection();
        $this->patientReferralsReceived = new ArrayCollection();
        $this->practitionerSchedules = new ArrayCollection();
        $this->appointments = new ArrayCollection();
    }

    /**
     * Serialize the Practitioner
     *
     * @return array <string, int|string|null>
     */
    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'jobTitle' => $this->getJobTitle(),
            'specialty' => $this->getSpecialty(),
            'licenseNumber' => $this->getLicenseNumber(),
            'phone' => $this->getPhone(),
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

    public function getJobTitle(): ?string
    {
        return $this->jobTitle;
    }

    public function setJobTitle(string $jobTitle): static
    {
        $this->jobTitle = $jobTitle;

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
        return $this->licenseNumber;
    }

    public function setLicenseNumber(string $licenseNumber): static
    {
        $this->licenseNumber = $licenseNumber;

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
            $patientReferralsSent->setSendingPractitioner($this);
        }

        return $this;
    }

    public function removePatientReferralsSent(PatientReferral $patientReferralsSent): static
    {
        if ($this->patientReferralsSent->removeElement($patientReferralsSent)) {
            // set the owning side to null (unless already changed)
            if ($patientReferralsSent->getSendingPractitioner() === $this) {
                $patientReferralsSent->setSendingPractitioner(null);
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
            $patientReferralsReceived->setReceivingPractitioner($this);
        }

        return $this;
    }

    public function removePatientReferralsReceived(PatientReferral $patientReferralsReceived): static
    {
        if ($this->patientReferralsReceived->removeElement($patientReferralsReceived)) {
            // set the owning side to null (unless already changed)
            if ($patientReferralsReceived->getReceivingPractitioner() === $this) {
                $patientReferralsReceived->setReceivingPractitioner(null);
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
     * @return Collection<int, PractitionerSchedule>
     */
    public function getPractitionerSchedules(): Collection
    {
        return $this->practitionerSchedules;
    }

    public function addPractitionerSchedule(PractitionerSchedule $practitionerSchedule): static
    {
        if (!$this->practitionerSchedules->contains($practitionerSchedule)) {
            $this->practitionerSchedules->add($practitionerSchedule);
            $practitionerSchedule->setPractitioner($this);
        }

        return $this;
    }

    public function removePractitionerSchedule(PractitionerSchedule $practitionerSchedule): static
    {
        if ($this->practitionerSchedules->removeElement($practitionerSchedule)) {
            // set the owning side to null (unless already changed)
            if ($practitionerSchedule->getPractitioner() === $this) {
                $practitionerSchedule->setPractitioner(null);
            }
        }

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
            $appointment->setPractitioner($this);
        }

        return $this;
    }

    public function removeAppointment(Appointment $appointment): static
    {
        if ($this->appointments->removeElement($appointment)) {
            // set the owning side to null (unless already changed)
            if ($appointment->getPractitioner() === $this) {
                $appointment->setPractitioner(null);
            }
        }

        return $this;
    }
}
