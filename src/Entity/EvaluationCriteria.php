<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EvaluationCriteriaRepository")
 */
class EvaluationCriteria implements EvaluationCriteriaInterface
{
    use TimestampableTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     */
    private $weight;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CustomerRequest", inversedBy="evaluationCriterias")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank
     * @Assert\Valid
     */
    private $customerRequest;

    public function __toString()
    {
        return $this->name . ' -> ' . $this->getWeight() . '%';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

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
