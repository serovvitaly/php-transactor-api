<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TransactionRepository")
 */
class Transaction
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $senderAccountId;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $recipientAccountId;

    /**
     * @ORM\Column(type="integer")
     */
    private $moneyMinorUnits;

    /**
     * @ORM\Column(type="integer")
     */
    private $statusCode;

    public function getId(): int
    {
        return $this->id;
    }

    public function getSenderAccountId(): string
    {
        return $this->senderAccountId;
    }

    public function setSenderAccountId(string $senderAccountId): self
    {
        $this->senderAccountId = $senderAccountId;
        return $this;
    }

    public function getRecipientAccountId(): string
    {
        return $this->senderAccountId;
    }

    public function setRecipientAccountId(string $recipientAccountId): self
    {
        $this->recipientAccountId = $recipientAccountId;
        return $this;
    }

    /**
     * @return int
     */
    public function getMoneyMinorUnits(): int
    {
        return $this->moneyMinorUnits;
    }

    /**
     * @param int $moneyMinorUnits
     * @return Transaction
     */
    public function setMoneyMinorUnits(int $moneyMinorUnits): self
    {
        $this->moneyMinorUnits = $moneyMinorUnits;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     */
    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }
}
