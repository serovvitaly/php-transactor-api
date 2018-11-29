<?php

namespace App\Dto;

use PhpTransactor\Entity\Account;
use PhpTransactor\ValueObject\Money;

class TransactionDto
{
    /** @var Account */
    public $senderAccount;
    /** @var Account */
    public $recipientAccount;
    /** @var Money */
    public $transferringMoney;

    public function __construct(Account $senderAccount, Account $recipientAccount, Money $transferringMoney)
    {
        $this->senderAccount = $senderAccount;
        $this->recipientAccount = $recipientAccount;
        $this->transferringMoney = $transferringMoney;
    }
}
