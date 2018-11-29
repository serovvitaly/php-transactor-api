<?php

namespace App\Assembler;

use App\Dto\TransactionDto;
use PhpTransactor\Entity\Account;
use PhpTransactor\Identifier\CurrencyIdentifier;
use PhpTransactor\ValueObject\Exception\NegativeMoneyValueException;
use PhpTransactor\ValueObject\Money;
use Symfony\Component\HttpFoundation\Request;

class TransactionDtoAssembler
{
    /**
     * @param Request $request
     * @return TransactionDto
     * @throws NegativeMoneyValueException
     */
    public function assembleFromRequest(Request $request): TransactionDto
    {
        $requestJsonStr = $request->getContent();
        $requestJsonObj = json_decode($requestJsonStr);

        $senderAccount = new Account();
        $senderAccount->setId($requestJsonObj->sender->id);
        $recipientAccount = new Account();
        $recipientAccount->setId($requestJsonObj->recipient->id);

        $transferringMoney = new Money(
            new CurrencyIdentifier($requestJsonObj->money->currency->code),
            $requestJsonObj->money->value
        );


        return new TransactionDto($senderAccount, $recipientAccount, $transferringMoney);
    }
}
