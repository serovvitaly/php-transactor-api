<?php

namespace App\Controller;

use App\Assembler\TransactionDtoAssembler;
use App\Exception\ApiException;
use App\Service\MoneyTransferServiceInterface;
use PhpTransactor\ValueObject\Exception\NegativeMoneyValueException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Контроллер "Денежный перевод"
 * @package App\Controller
 */
class TransferController extends AbstractController
{
    /** @var TransactionDtoAssembler */
    private $transactionDtoAssembler;
    /** @var MoneyTransferServiceInterface */
    private $moneyTransferService;

    public function __construct(
        TransactionDtoAssembler $transactionDtoAssembler,
        MoneyTransferServiceInterface $moneyTransferService
    ) {
        $this->transactionDtoAssembler = $transactionDtoAssembler;
        $this->moneyTransferService = $moneyTransferService;
    }

    /**
     * @Route("/transfer", name="transfer", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function perform(Request $request)
    {
        try {
            $transactionDto = $this->transactionDtoAssembler->assembleFromRequest($request);
            $this->moneyTransferService->performMoneyTransfer($transactionDto);
            return $this->json([
                'message' => 'Welcome to your new controller! ',
                'path' => 'src/Controller/TransactionController.php',
            ]);
        } catch (NegativeMoneyValueException $e) {
            return $this
                ->json([
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ])
                ->setStatusCode(400);
        } catch (ApiException $e) {
            return $this
                ->json([
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ])
                ->setStatusCode(400);
        }
    }
}
