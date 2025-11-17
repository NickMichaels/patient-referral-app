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
    private ?Provider $sendingProvider = null;

    #[ORM\ManyToOne(inversedBy: 'patientReferralsReceived')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Provider $receivingProvider = null;

    #[ORM\ManyToOne(inversedBy: 'patientReferralsSent')]
    private ?Practicioner $sendingPracticioner = null;

    #[ORM\ManyToOne(inversedBy: 'patientReferralsReceived')]
    private ?Practicioner $receivingPracticioner = null;

    #[ORM\Column(enumType: PatientReferralStatus::class)]
    private ?PatientReferralStatus $status = null;

    #[ORM\Column]
    private ?\DateTime $dateSent = null;

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'patient' => $this->getPatient()->getId(),
            'sendingProvider' => $this->getSendingProvider()->getId(),
            'receivingProvider' => $this->getReceivingProvider()->getId(),
            'sendingPracticioner' => ($this->getSendingPracticioner() !== null) ? 
                $this->getSendingPracticioner()->getId() : '',
            'receivingPracticioner' => ($this->getReceivingPracticioner() !== null) ? 
                $this->getReceivingPracticioner()->getId() : '',
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

    public function getSendingPracticioner(): ?Practicioner
    {
        return $this->sendingPracticioner;
    }

    public function setSendingPracticioner(?Practicioner $sendingPracticioner): static
    {
        $this->sendingPracticioner = $sendingPracticioner;

        return $this;
    }

    public function getReceivingPracticioner(): ?Practicioner
    {
        return $this->receivingPracticioner;
    }

    public function setReceivingPracticioner(?Practicioner $receivingPracticioner): static
    {
        $this->receivingPracticioner = $receivingPracticioner;

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
}
