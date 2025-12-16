<?php

namespace App\Entity;

use App\Enum\ScheduleDayOfWeek;
use App\Repository\PractitionerScheduleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PractitionerScheduleRepository::class)]
class PractitionerSchedule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'practitionerSchedules')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Practitioner $practitioner = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTime $shiftStart = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTime $shiftEnd = null;

    #[ORM\Column(enumType: ScheduleDayOfWeek::class)]
    private ?ScheduleDayOfWeek $dayOfWeek = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPractitioner(): ?Practitioner
    {
        return $this->practitioner;
    }

    public function setPractitioner(?Practitioner $practitioner): static
    {
        $this->practitioner = $practitioner;

        return $this;
    }

    public function getShiftStart(): ?\DateTime
    {
        return $this->shiftStart;
    }

    public function setShiftStart(\DateTime $shiftStart): static
    {
        $this->shiftStart = $shiftStart;

        return $this;
    }

    public function getShiftEnd(): ?\DateTime
    {
        return $this->shiftEnd;
    }

    public function setShiftEnd(\DateTime $shiftEnd): static
    {
        $this->shiftEnd = $shiftEnd;

        return $this;
    }

    public function getDayOfWeek(): ?ScheduleDayOfWeek
    {
        return $this->dayOfWeek;
    }

    public function setDayOfWeek(ScheduleDayOfWeek $dayOfWeek): static
    {
        $this->dayOfWeek = $dayOfWeek;

        return $this;
    }
}
