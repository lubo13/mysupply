<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SupplierProposalRepository")
 */
class SupplierProposal implements SupplierProposalInterface, CustomUserInterface
{
    use TimestampableTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="supplierProposals")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid
     */
    private $user;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Range(
     *      min = 0,
     *      max = 100,
     *      minMessage = "You must type at least {{ limit }}",
     *      maxMessage = "You cannot be type more than {{ limit }}"
     * )
     */
    private $points;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $accepted;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SupplierBid", mappedBy="supplierProposal", orphanRemoval=true)
     */
    private $bids;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EvaluationScore", mappedBy="supplierProposal", orphanRemoval=true, cascade={"persist"})
     */
    private $evaluationScores;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CustomerRequest", inversedBy="supplierProposals")
     * @Assert\NotBlank
     * @Assert\Valid
     */
    private $customerRequest;

    public function __construct()
    {
        $this->bids             = new ArrayCollection();
        $this->evaluationScores = new ArrayCollection();
    }

    public function __toString()
    {
        $proposal = '';
        $proposal .= $this->getDescription() ?? ' ';
        $proposal .= 'Evaluation Scores: ';
        foreach ($this->evaluationScores as $score) {
            $proposal .= $score;
        }
        return $proposal;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(?int $points): self
    {
        $this->points = $points;

        return $this;
    }

    public function getAccepted(): ?bool
    {
        return $this->accepted;
    }

    public function setAccepted(?bool $accepted): self
    {
        $this->accepted = $accepted;

        return $this;
    }

    /**
     * @return Collection|SupplierBid[]
     */
    public function getBids(): Collection
    {
        return $this->bids;
    }

    public function addBid(SupplierBid $bid): self
    {
        if (!$this->bids->contains($bid)) {
            $this->bids[] = $bid;
            $bid->setSupplierProposal($this);
        }

        return $this;
    }

    public function removeBid(SupplierBid $bid): self
    {
        if ($this->bids->contains($bid)) {
            $this->bids->removeElement($bid);
            // set the owning side to null (unless already changed)
            if ($bid->getSupplierProposal() === $this) {
                $bid->setSupplierProposal(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|EvaluationScore[]
     */
    public function getEvaluationScores(): Collection
    {
        return $this->evaluationScores;
    }

    public function addEvaluationScore(EvaluationScore $evaluationScore): self
    {
        if (!$this->evaluationScores->contains($evaluationScore)) {
            $this->evaluationScores[] = $evaluationScore;
            $evaluationScore->setSupplierProposal($this);
        }

        return $this;
    }

    public function removeEvaluationScore(EvaluationScore $evaluationScore): self
    {
        if ($this->evaluationScores->contains($evaluationScore)) {
            $this->evaluationScores->removeElement($evaluationScore);
            // set the owning side to null (unless already changed)
            if ($evaluationScore->getSupplierProposal() === $this) {
                $evaluationScore->setSupplierProposal(null);
            }
        }

        return $this;
    }

    public function getCustomerRequest(): ?CustomerRequest
    {
        return $this->customerRequest;
    }

    public function setCustomerRequest(?CustomerRequest $customerRequest): self
    {
        $this->customerRequest = $customerRequest;

        return $this;
    }
}
