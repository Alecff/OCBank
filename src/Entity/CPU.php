<?php

namespace App\Entity;

use App\Repository\CPURepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CPURepository::class)
 */
class CPU
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Manufacturer;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Name;

    /**
     * @ORM\Column(type="integer")
     */
    private $Cores;

    /**
     * @ORM\Column(type="integer")
     */
    private $Threads;

    /**
     * @ORM\Column(type="float")
     */
    private $BaseClock;

    /**
     * @ORM\Column(type="float")
     */
    private $BoostClock;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ReleaseYear;

    /**
     * @ORM\OneToMany(targetEntity=Result::class, mappedBy="CPU")
     */
    private $results;

    public function __construct()
    {
        $this->results = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getManufacturer(): ?string
    {
        return $this->Manufacturer;
    }

    public function setManufacturer(string $Manufacturer): self
    {
        $this->Manufacturer = $Manufacturer;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getCores(): ?int
    {
        return $this->Cores;
    }

    public function setCores(int $Cores): self
    {
        $this->Cores = $Cores;

        return $this;
    }

    public function getThreads(): ?int
    {
        return $this->Threads;
    }

    public function setThreads(int $Threads): self
    {
        $this->Threads = $Threads;

        return $this;
    }

    public function getBaseClock(): ?float
    {
        return $this->BaseClock;
    }

    public function setBaseClock(float $BaseClock): self
    {
        $this->BaseClock = $BaseClock;

        return $this;
    }

    public function getBoostClock(): ?float
    {
        return $this->BoostClock;
    }

    public function setBoostClock(float $BoostClock): self
    {
        $this->BoostClock = $BoostClock;

        return $this;
    }

    public function getReleaseYear(): ?int
    {
        return $this->ReleaseYear;
    }

    public function setReleaseYear(?int $ReleaseYear): self
    {
        $this->ReleaseYear = $ReleaseYear;

        return $this;
    }

    /**
     * @return Collection|Result[]
     */
    public function getResults(): Collection
    {
        return $this->results;
    }

    public function addResult(Result $result): self
    {
        if (!$this->results->contains($result)) {
            $this->results[] = $result;
            $result->setCPU($this);
        }

        return $this;
    }

    public function removeResult(Result $result): self
    {
        if ($this->results->contains($result)) {
            $this->results->removeElement($result);
            // set the owning side to null (unless already changed)
            if ($result->getCPU() === $this) {
                $result->setCPU(null);
            }
        }

        return $this;
    }
}
