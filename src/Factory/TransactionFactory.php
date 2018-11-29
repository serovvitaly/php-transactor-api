<?php

namespace App\Factory;

use PhpTransactor\Entity\Account;
use PhpTransactor\Entity\Transaction;
use PhpTransactor\Factory\TransactionFactoryInterface;
use PhpTransactor\ValueObject\Money;

class TransactionFactory implements TransactionFactoryInterface
{
    public function make(Account $senderAccount, Account $recipientAccount, Money $transferringMoney): Transaction
    {
        return new Transaction($senderAccount, $recipientAccount, $transferringMoney);
    }
}
