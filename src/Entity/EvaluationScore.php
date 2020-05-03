<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EvaluationScoreRepository")
 */
class EvaluationScore implements EvaluationScoreInterface
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
     * @ORM\ManyToOne(targetEntity="App\Entity\EvaluationCriteria")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     * @Assert\NotBlank
     * @Assert\Valid
     */
    private $evaluationCriteria;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SupplierProposal", inversedBy="evaluationScores")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank
     * @Assert\Valid
     */
    private $supplierProposal;

    public function __toString()
    {
        $evaluationScore = '';
        if ($this->evaluationCriteria) {
            $evaluationScore .= $this->evaluationCriteria . ' -> ';
        }
        $evaluationScore .= $this->description . '; ' ?? '';
        return $evaluationScore;
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

    public function getEvaluationCriteria(): ?EvaluationCriteria
    {
        return $this->evaluationCriteria;
    }

    public function setEvaluationCriteria(?EvaluationCriteria $evaluationCriteria): self
    {
        $this->evaluationCriteria = $evaluationCriteria;

        return $this;
    }

    public function getSupplierProposal(): ?SupplierProposal
    {
        return $this->supplierProposal;
    }

    public function setSupplierProposal(?SupplierProposal $supplierProposal): self
    {
        $this->supplierProposal = $supplierProposal;

        return $this;
    }
}
