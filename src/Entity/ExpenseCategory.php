<?php

namespace App\Entity;

use App\Repository\ExpenseCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExpenseCategoryRepository::class)]
class ExpenseCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $faIcon = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Expense::class)]
    private Collection $expenses;

    #[ORM\Column(nullable: true)]
    private ?int $budgetEstimate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $occurrence = null;

    public function __construct()
    {
        $this->expenses = new ArrayCollection();
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

    public function getFaIcon(): ?string
    {
        return $this->faIcon;
    }

    public function setFaIcon(?string $faIcon): self
    {
        $this->faIcon = $faIcon;

        return $this;
    }

    /**
     * @return Collection<int, Expense>
     */
    public function getExpenses(): Collection
    {
        return $this->expenses;
    }

    public function addExpense(Expense $expense): self
    {
        if (!$this->expenses->contains($expense)) {
            $this->expenses->add($expense);
            $expense->setCategory($this);
        }

        return $this;
    }

    public function removeExpense(Expense $expense): self
    {
        if ($this->expenses->removeElement($expense)) {
            // set the owning side to null (unless already changed)
            if ($expense->getCategory() === $this) {
                $expense->setCategory(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getBudgetEstimate(): ?int
    {
        return $this->budgetEstimate;
    }

    public function setBudgetEstimate(int $budgetEstimate): self
    {
        $this->budgetEstimate = $budgetEstimate;

        return $this;
    }

    public function getOccurrence(): ?string
    {
        return $this->occurrence;
    }

    public function setOccurrence(string $occurrence): self
    {
        $this->occurrence = $occurrence;

        return $this;
    }
}
