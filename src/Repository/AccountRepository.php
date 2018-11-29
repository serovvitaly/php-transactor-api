<?php

namespace App\Repository;

use PhpTransactor\Entity\Account;
use PhpTransactor\Identifier\AccountIdentifier;
use PhpTransactor\Repository\AccountRepositoryInterface;

class AccountRepository implements AccountRepositoryInterface
{

    public function findOrMakeByIdentifier(AccountIdentifier $identifier): Account
    {
        // TODO: Implement findOrMakeByIdentifier() method.
    }

    public function persist(Account $account): void
    {
        // TODO: Implement persist() method.
    }
}
