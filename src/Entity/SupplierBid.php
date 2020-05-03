<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SupplierBidRepository")
 */
class SupplierBid implements SupplierBidInterface
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
    private $contracts;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $accepted;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0)
     * @Assert\NotBlank
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SupplierProposal", inversedBy="bids")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank
     */
    private $supplierProposal;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContracts(): ?string
    {
        return $this->contracts;
    }

    public function setContracts(string $contracts): self
    {
        $this->contracts = $contracts;

        return $this;
    }

    public function getAccepted(): ?bool
    {
        return $this->accepted;
    }

    public function setAccepted(bool $accepted): self
    {
        $this->accepted = $accepted;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

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
