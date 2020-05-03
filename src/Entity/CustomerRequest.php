<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CustomerRequestRepository")
 */
class CustomerRequest implements CustomerRequestInterface, CustomUserInterface
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="customerRequests")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     * @Assert\NotBlank
     * @Assert\Valid
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="customerRequests")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     * @Assert\Valid
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EvaluationCriteria", mappedBy="customerRequest", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $evaluationCriterias;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SupplierProposal", mappedBy="customerRequest")
     */
    private $supplierProposals;

    public function __construct()
    {
        $this->evaluationCriterias = new ArrayCollection();
        $this->supplierProposals   = new ArrayCollection();
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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

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

    /**
     * @return Collection|EvaluationCriteria[]
     */
    public function getEvaluationCriterias(): Collection
    {
        return $this->evaluationCriterias;
    }

    public function addEvaluationCriteria(EvaluationCriteria $evaluationCriteria): self
    {
        if (!$this->evaluationCriterias->contains($evaluationCriteria)) {
            $this->evaluationCriterias[] = $evaluationCriteria;
            $evaluationCriteria->setCustomerRequest($this);
        }

        return $this;
    }

    public function removeEvaluationCriteria(EvaluationCriteria $evaluationCriteria): self
    {
        if ($this->evaluationCriterias->contains($evaluationCriteria)) {
            $this->evaluationCriterias->removeElement($evaluationCriteria);
            // set the owning side to null (unless already changed)
            if ($evaluationCriteria->getCustomerRequest() === $this) {
                $evaluationCriteria->setCustomerRequest(null);
            }
        }

        return $this;
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        $total = 0;

        foreach ($this->evaluationCriterias as $evaluationCriteria) {
            $total += $evaluationCriteria->getWeight();
        }

        if ($total > 100) {
            $context->buildViolation('The sum of the percents must be between %min% and %max%.')
                ->setParameter('%min%', 0)
                ->setParameter('%max%', 100)
                ->setTranslationDomain('number_validation')
                ->addViolation();
        }
    }

    /**
     * @return Collection|SupplierProposal[]
     */
    public function getSupplierProposals(): Collection
    {
        return $this->supplierProposals;
    }

    public function addSupplierProposal(SupplierProposal $supplierProposal): self
    {
        if (!$this->supplierProposals->contains($supplierProposal)) {
            $this->supplierProposals[] = $supplierProposal;
            $supplierProposal->setCustomerRequest($this);
        }

        return $this;
    }

    public function removeSupplierProposal(SupplierProposal $supplierProposal): self
    {
        if ($this->supplierProposals->contains($supplierProposal)) {
            $this->supplierProposals->removeElement($supplierProposal);
            // set the owning side to null (unless already changed)
            if ($supplierProposal->getCustomerRequest() === $this) {
                $supplierProposal->setCustomerRequest(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|SupplierProposal[]
     */
    public function setSupplierProposals(Collection $supplierProposals)
    {
        $this->supplierProposals = $supplierProposals;
    }

}
