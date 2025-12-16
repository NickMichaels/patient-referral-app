<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use JsonSerializable;
use Doctrine\ORM\Mapping as ORM;
use App\Enum\PatientReferralStatus;
use App\Repository\PatientReferralRepository;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: PatientReferralRepository::class)]
class PatientReferral implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'patientReferrals')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Patient $patient = null;

    #[ORM\ManyToOne(inversedBy: 'patientReferralsSent')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Provider $sendingProvider = null;

    #[ORM\ManyToOne(inversedBy: 'patientReferralsReceived')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Provider $receivingProvider = null;

    #[ORM\ManyToOne(inversedBy: 'patientReferralsSent')]
    private ?Practitioner $sendingPractitioner = null;

    #[ORM\ManyToOne(inversedBy: 'patientReferralsReceived')]
    private ?Practitioner $receivingPractitioner = null;

    #[ORM\Column(enumType: PatientReferralStatus::class)]
    private ?PatientReferralStatus $status = null;

    #[ORM\Column]
    private ?\DateTime $dateSent = null;

    /**
     * @var Collection<int, Appointment>
     */
    #[ORM\OneToMany(targetEntity: Appointment::class, mappedBy: 'patientReferral')]
    private Collection $appointments;

    public function __construct()
    {
        $this->appointments = new ArrayCollection();
    }

    /**
     * Serialize the Patient Referral
     *
     * @return array <string, int|string|null>
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'patient' => $this->getPatient()->getId(),
            'patientName' => $this->getPatient()->getName(),
            'sendingProvider' => $this->getSendingProvider()->getId(),
            'sendingProviderName' => $this->getSendingProvider()->getName(),
            'receivingProvider' => $this->getReceivingProvider()->getId(),
            'receivingProviderName' => $this->getReceivingProvider()->getName(),
            'sendingPractitioner' => ($this->getSendingPractitioner() !== null) ?
                $this->getSendingPractitioner()->getId() : '',
            'sendingPractitionerName' => ($this->getSendingPractitioner() !== null) ?
                $this->getSendingPractitioner()->getName() : '',
            'receivingPractitioner' => ($this->getReceivingPractitioner() !== null) ?
                $this->getReceivingPractitioner()->getId() : '',
            'receivingPractitionerName' => ($this->getReceivingPractitioner() !== null) ?
                $this->getReceivingPractitioner()->getName() : '',
            'status' => $this->getStatus()->value,
            'dateSent' => $this->getDateSent()->format('m-d-Y h:m:s'),
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): static
    {
        $this->patient = $patient;

        return $this;
    }

    public function getSendingProvider(): ?Provider
    {
        return $this->sendingProvider;
    }

    public function setSendingProvider(?Provider $sendingProvider): static
    {
        $this->sendingProvider = $sendingProvider;

        return $this;
    }

    public function getReceivingProvider(): ?Provider
    {
        return $this->receivingProvider;
    }

    public function setReceivingProvider(?Provider $receivingProvider): static
    {
        $this->receivingProvider = $receivingProvider;

        return $this;
    }

    public function getSendingPractitioner(): ?Practitioner
    {
        return $this->sendingPractitioner;
    }

    public function setSendingPractitioner(?Practitioner $sendingPractitioner): static
    {
        $this->sendingPractitioner = $sendingPractitioner;

        return $this;
    }

    public function getReceivingPractitioner(): ?Practitioner
    {
        return $this->receivingPractitioner;
    }

    public function setReceivingPractitioner(?Practitioner $receivingPractitioner): static
    {
        $this->receivingPractitioner = $receivingPractitioner;

        return $this;
    }

    public function getStatus(): ?PatientReferralStatus
    {
        return $this->status;
    }

    public function setStatus(PatientReferralStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getDateSent(): ?\DateTime
    {
        return $this->dateSent;
    }

    public function setDateSent(\DateTime $dateSent): static
    {
        $this->dateSent = $dateSent;

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
            $appointment->setPatientReferral($this);
        }

        return $this;
    }

    public function removeAppointment(Appointment $appointment): static
    {
        if ($this->appointments->removeElement($appointment)) {
            // set the owning side to null (unless already changed)
            if ($appointment->getPatientReferral() === $this) {
                $appointment->setPatientReferral(null);
            }
        }

        return $this;
    }
}
