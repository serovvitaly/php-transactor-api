<?php

namespace App\Service;

use App\Dto\TransactionDto;
use App\Exception\ApiException;

interface MoneyTransferServiceInterface
{
    /**
     * @param TransactionDto $transactionDto
     * @throws ApiException
     */
    public function performMoneyTransfer(TransactionDto $transactionDto);
}
