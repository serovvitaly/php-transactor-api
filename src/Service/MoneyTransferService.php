<?php

namespace App\Service;

use App\Dto\TransactionDto;
use App\Factory\TransactionFactory;
use App\Repository\AccountRepository;
use App\Repository\TransactionRepository;
use App\Transaction\TransactionManager;
use PhpTransactor\Service\Exception\TransactionException;
use PhpTransactor\Service\Exception\ValidationException;
use PhpTransactor\Service\MoneyTransferService as PhpTransactorMoneyTransferService;
use PhpTransactor\Transaction\MoneyTransferTransaction;
use PhpTransactor\Validator\MoneyTransferValidator;

class MoneyTransferService implements MoneyTransferServiceInterface
{
    /**
     * @inheritdoc
     */
    public function performMoneyTransfer(TransactionDto $transactionDto)
    {
        $moneyTransferService = new PhpTransactorMoneyTransferService(
            new MoneyTransferValidator(),
            new MoneyTransferTransaction(
                new TransactionManager(),
                new AccountRepository(),
                new TransactionRepository(),
                new TransactionFactory()
            )
        );

        try {
            $moneyTransferService->performMoneyTransfer(
                $transactionDto->senderAccount,
                $transactionDto->recipientAccount,
                $transactionDto->transferringMoney
            );
        } catch (TransactionException $e) {
            throw new \App\Exception\ApiException($e->getMessage(), $e->getCode(), $e);
        } catch (ValidationException $e) {
            throw new \App\Exception\ApiException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
