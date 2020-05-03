<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category implements CategoryInterface
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
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="categories")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CustomerRequest", mappedBy="category")
     */
    private $customerRequests;

    public function __construct()
    {
        $this->users            = new ArrayCollection();
        $this->customerRequests = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
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

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addCategory($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeCategory($this);
        }

        return $this;
    }

    public function setUsers(Collection $users)
    {
        $this->users = $users;
    }

    public function clearUsers()
    {
        $this->users = new ArrayCollection();
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
            $customerRequest->setCategory($this);
        }

        return $this;
    }

    public function removeCustomerRequest(CustomerRequest $customerRequest): self
    {
        if ($this->customerRequests->contains($customerRequest)) {
            $this->customerRequests->removeElement($customerRequest);
            // set the owning side to null (unless already changed)
            if ($customerRequest->getCategory() === $this) {
                $customerRequest->setCategory(null);
            }
        }

        return $this;
    }
}
