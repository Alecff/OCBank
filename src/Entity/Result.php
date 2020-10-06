<?php

namespace App\Entity;

use App\Repository\ResultRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ResultRepository::class)
 */
class Result
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="results")
     * @ORM\JoinColumn(nullable=false)
     */
    private $User;

    /**
     * @ORM\ManyToOne(targetEntity=CPU::class, inversedBy="results")
     * @ORM\JoinColumn(nullable=false)
     */
    private $CPU;

    /**
     * @ORM\OneToMany(targetEntity=Node::class, mappedBy="Result")
     */
    private $nodes;

    public function __construct()
    {
        $this->nodes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getCPU(): ?CPU
    {
        return $this->CPU;
    }

    public function setCPU(?CPU $CPU): self
    {
        $this->CPU = $CPU;

        return $this;
    }

    /**
     * @return Collection|Node[]
     */
    public function getNodes(): Collection
    {
        return $this->nodes;
    }

    public function addNode(Node $node): self
    {
        if (!$this->nodes->contains($node)) {
            $this->nodes[] = $node;
            $node->setResult($this);
        }

        return $this;
    }

    public function removeNode(Node $node): self
    {
        if ($this->nodes->contains($node)) {
            $this->nodes->removeElement($node);
            // set the owning side to null (unless already changed)
            if ($node->getResult() === $this) {
                $node->setResult(null);
            }
        }

        return $this;
    }
}
