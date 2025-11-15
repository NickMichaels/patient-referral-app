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

    #[ORM\Column(length: 50)]
    private ?string $phone = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    /**
     * @var Collection<int, PatientReferral>
     */
    #[ORM\OneToMany(targetEntity: PatientReferral::class, mappedBy: 'sending_practicioner')]
    private Collection $patientReferralsSent;

    /**
     * @var Collection<int, PatientReferral>
     */
    #[ORM\OneToMany(targetEntity: PatientReferral::class, mappedBy: 'receiving_practicioner')]
    private Collection $patientReferralsReceived;


    public function __construct()
    {
        $this->provider = new ArrayCollection();
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
            $patientReferralsSent->setSendingPracticioner($this);
        }

        return $this;
    }

    public function removePatientReferralsSent(PatientReferral $patientReferralsSent): static
    {
        if ($this->patientReferralsSent->removeElement($patientReferralsSent)) {
            // set the owning side to null (unless already changed)
            if ($patientReferralsSent->getSendingPracticioner() === $this) {
                $patientReferralsSent->setSendingPracticioner(null);
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
            $patientReferralsReceived->setReceivingPracticioner($this);
        }

        return $this;
    }

    public function removePatientReferralsReceived(PatientReferral $patientReferralsReceived): static
    {
        if ($this->patientReferralsReceived->removeElement($patientReferralsReceived)) {
            // set the owning side to null (unless already changed)
            if ($patientReferralsReceived->getReceivingPracticioner() === $this) {
                $patientReferralsReceived->setReceivingPracticioner(null);
            }
        }

        return $this;
    }

}
