<?php

namespace App\Repository;

use App\Entity\Transaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PhpTransactor\Entity\Transaction as DomainTransaction;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Transaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transaction[]    findAll()
 * @method Transaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionRepository extends ServiceEntityRepository implements TransactionRepositoryInterface
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    /**
     * @param DomainTransaction $domainTransaction
     * @throws \Doctrine\ORM\ORMException
     */
    public function persist(DomainTransaction $domainTransaction): void
    {
        $transaction = (new Transaction())
            ->setSenderAccountId($domainTransaction->getSenderAccountId())
            ->setRecipientAccountId($domainTransaction->getRecipientAccountId())
            ->setMoneyMinorUnits($domainTransaction->getMoneyMinorUnits())
            ->setStatusCode($domainTransaction->getStatusCode());

        $this->getEntityManager()->persist($transaction);
        $this->getEntityManager()->flush();
    }
}
