<?php

namespace App\Entity;

use App\Enum\PatientReferralStatus;
use App\Repository\PatientReferralRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PatientReferralRepository::class)]
class PatientReferral
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'patientReferral', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Patient $patient = null;

    #[ORM\OneToOne(inversedBy: 'patientReferralsSent', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Provider $sending_provider = null;

    #[ORM\OneToOne(inversedBy: 'patientReferralsReceived', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Provider $receiving_provider = null;

    #[ORM\Column(enumType: PatientReferralStatus::class)]
    private ?PatientReferralStatus $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(Patient $patient): static
    {
        $this->patient = $patient;

        return $this;
    }

    public function getSendingProvider(): ?Provider
    {
        return $this->sending_provider;
    }

    public function setSendingProvider(Provider $sending_provider): static
    {
        $this->sending_provider = $sending_provider;

        return $this;
    }

    public function getReceivingProvider(): ?Provider
    {
        return $this->receiving_provider;
    }

    public function setReceivingProvider(Provider $receiving_provider): static
    {
        $this->receiving_provider = $receiving_provider;

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
