<?php

namespace App\Entity;

use App\Repository\NodeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NodeRepository::class)
 */
class Node
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $Clock;

    /**
     * @ORM\Column(type="float")
     */
    private $Voltage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Proof;

    /**
     * @ORM\Column(type="boolean")
     */
    private $verified;

    /**
     * @ORM\ManyToOne(targetEntity=Result::class, inversedBy="nodes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Result;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClock(): ?float
    {
        return $this->Clock;
    }

    public function setClock(float $Clock): self
    {
        $this->Clock = $Clock;

        return $this;
    }

    public function getVoltage(): ?float
    {
        return $this->Voltage;
    }

    public function setVoltage(float $Voltage): self
    {
        $this->Voltage = $Voltage;

        return $this;
    }

    public function getProof(): ?string
    {
        return $this->Proof;
    }

    public function setProof(?string $Proof): self
    {
        $this->Proof = $Proof;

        return $this;
    }

    public function getVerified(): ?bool
    {
        return $this->verified;
    }

    public function setVerified(bool $verified): self
    {
        $this->verified = $verified;

        return $this;
    }

    public function getResult(): ?Result
    {
        return $this->Result;
    }

    public function setResult(?Result $Result): self
    {
        $this->Result = $Result;

        return $this;
    }
}
