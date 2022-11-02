<?php

namespace App\Entity;

use App\Repository\InvestmentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvestmentRepository::class)]
class Investment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $payFrequency = null;

    #[ORM\Column]
    private ?int $amount = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $startOn = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $nextDueDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $reference = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $underTaxScheme = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $taxDeduction = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $investmentOption = null;

    #[ORM\Column(nullable: true)]
    private ?int $interestGain = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPayFrequency(): ?string
    {
        return $this->payFrequency;
    }

    public function setPayFrequency(string $payFrequency): self
    {
        $this->payFrequency = $payFrequency;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getStartOn(): ?\DateTimeInterface
    {
        return $this->startOn;
    }

    public function setStartOn(\DateTimeInterface $startOn): self
    {
        $this->startOn = $startOn;

        return $this;
    }

    public function getNextDueDate(): ?\DateTimeInterface
    {
        return $this->nextDueDate;
    }

    public function setNextDueDate(?\DateTimeInterface $nextDueDate): self
    {
        $this->nextDueDate = $nextDueDate;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): self
    {
        $this->reference = $reference;

        return $this;
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

    public function getUnderTaxScheme(): ?string
    {
        return $this->underTaxScheme;
    }

    public function setUnderTaxScheme(?string $underTaxScheme): self
    {
        $this->underTaxScheme = $underTaxScheme;

        return $this;
    }

    public function getTaxDeduction(): ?string
    {
        return $this->taxDeduction;
    }

    public function setTaxDeduction(?string $taxDeduction): self
    {
        $this->taxDeduction = $taxDeduction;

        return $this;
    }

    public function getInvestmentOption(): ?string
    {
        return $this->investmentOption;
    }

    public function setInvestmentOption(?string $investmentOption): self
    {
        $this->investmentOption = $investmentOption;

        return $this;
    }

    public function getCurrentValue(): int
    {
        $currentValue = ($this->getInterestGain() / 100) * $this->getAmountInvested();
        if ($currentValue > 1) {
            return $this->getAmountInvested() + $currentValue;
        }
        
        return $this->getAmountInvested() - $currentValue;
    }


    public function getAmountInvested(): int
    {
        if ($this->payFrequency == 'multiple' || $this->payFrequency == 'onetime') {
            return $this->amount;
        }
       

        $today = new \DateTimeImmutable('now');
        if ($this->startOn == $today) {
            return $this->amount;
        } 

         // Calculates the difference between DateTime objects
        $interval = date_diff($this->startOn, $today);
        $installmentMonth = $interval->format('%m');
        $installmentYear = $interval->format('%Y');
        if ($this->payFrequency == 'annually') {
            if ($installmentYear == 0) {
                return $this->amount;
            }
            return $this->amount * $installmentYear;
        }

        if ($installmentYear == 0) {
            return $this->amount * $installmentMonth;
        }

        

        return $this->amount * (($installmentYear * 12) + $installmentMonth);
    }

    public function getInterestGain(): ?int
    {
        return $this->interestGain;
    }

    public function setInterestGain(?int $interestGain): self
    {
        $this->interestGain = $interestGain;

        return $this;
    }
}
