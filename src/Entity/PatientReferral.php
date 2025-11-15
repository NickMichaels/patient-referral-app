<?php

namespace App\Entity;

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
    private ?Provider $sending_provider = null;

    #[ORM\ManyToOne(inversedBy: 'patientReferralsReceived')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Provider $receiving_provider = null;

    #[ORM\ManyToOne(inversedBy: 'patientReferralsSent')]
    private ?Practicioner $sending_practicioner = null;

    #[ORM\ManyToOne(inversedBy: 'patientReferralsReceived')]
    private ?Practicioner $receiving_practicioner = null;

    #[ORM\Column(enumType: PatientReferralStatus::class)]
    private ?PatientReferralStatus $status = null;

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'patient' => $this->getPatient()->getId(),
            'sending_provider' => $this->getSendingProvider()->getId(),
            'receiving_provider' => $this->getReceivingProvider()->getId(),
            'sending_practicioner' => ($this->getSendingPracticioner() !== null) ? 
                $this->getSendingPracticioner()->getId() : '',
            'receiving_practicioner' => ($this->getReceivingPracticioner() !== null) ? 
                $this->getReceivingPracticioner()->getId() : '',
            'status' => $this->getStatus()->value,
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
        return $this->sending_provider;
    }

    public function setSendingProvider(?Provider $sending_provider): static
    {
        $this->sending_provider = $sending_provider;

        return $this;
    }

    public function getReceivingProvider(): ?Provider
    {
        return $this->receiving_provider;
    }

    public function setReceivingProvider(?Provider $receiving_provider): static
    {
        $this->receiving_provider = $receiving_provider;

        return $this;
    }

    public function getSendingPracticioner(): ?Practicioner
    {
        return $this->sending_practicioner;
    }

    public function setSendingPracticioner(?Practicioner $sending_practicioner): static
    {
        $this->sending_practicioner = $sending_practicioner;

        return $this;
    }

    public function getReceivingPracticioner(): ?Practicioner
    {
        return $this->receiving_practicioner;
    }

    public function setReceivingPracticioner(?Practicioner $receiving_practicioner): static
    {
        $this->receiving_practicioner = $receiving_practicioner;

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
}
