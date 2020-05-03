<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    const CUSTOMER        = 'customer';
    const SUPPLIER        = 'supplier';
    const AVAILABLE_TYPES = [self::CUSTOMER => self::CUSTOMER, self::SUPPLIER => self::SUPPLIER];

    use TimestampableTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="users")
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CustomerRequest", mappedBy="user")
     */
    private $customerRequests;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SupplierProposal", mappedBy="user")
     */
    private $supplierProposals;

    public function __construct()
    {
        $this->categories        = new ArrayCollection();
        $this->customerRequests  = new ArrayCollection();
        $this->supplierProposals = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->email;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string)$this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }

        return $this;
    }

    public function setCategories(Collection $categories)
    {
        $this->categories = $categories;
    }

    public function clearCategories()
    {
        $this->categories = new ArrayCollection();
    }

    /**
     * @return Collection|CustomerRequest[]
     */
    public function getCustomerRequests(): Collection
    {
        return $this->customerRequests;
    }

    public function addCustomerRequest(CustomerRequest $customerRequest): self
    {
        if (!$this->customerRequests->contains($customerRequest)) {
            $this->customerRequests[] = $customerRequest;
            $customerRequest->setUser($this);
        }

        return $this;
    }

    public function removeCustomerRequest(CustomerRequest $customerRequest): self
    {
        if ($this->customerRequests->contains($customerRequest)) {
            $this->customerRequests->removeElement($customerRequest);
            // set the owning side to null (unless already changed)
            if ($customerRequest->getUser() === $this) {
                $customerRequest->setUser(null);
            }
        }

        return $this;
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
            $supplierProposal->setUser($this);
        }

        return $this;
    }

    public function removeSupplierProposal(SupplierProposal $supplierProposal): self
    {
        if ($this->supplierProposals->contains($supplierProposal)) {
            $this->supplierProposals->removeElement($supplierProposal);
            // set the owning side to null (unless already changed)
            if ($supplierProposal->getUser() === $this) {
                $supplierProposal->setUser(null);
            }
        }

        return $this;
    }

}
