<?php

namespace App\Repository;

use PhpTransactor\Entity\Transaction;
use PhpTransactor\Repository\TransactionRepositoryInterface;

class TransactionRepository implements TransactionRepositoryInterface
{

    public function persist(Transaction $transaction): void
    {
        // TODO: Implement persist() method.
    }
}
