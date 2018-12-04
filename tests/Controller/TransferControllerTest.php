<?php

namespace App\Tests\Controller;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TransferControllerTest extends WebTestCase
{
    /** @var Client */
    private $client;
    /** @var RegistryInterface $doctrine */
    private $doctrine;

    public function setUp()
    {
        parent::setUp();

        self::bootKernel();

        $this->doctrine = self::$container->get('doctrine');

        $this->client = static::createClient();
    }

    /**
     * Очищает таблицу от записей
     */
    private function truncate()
    {
        /** @var \Doctrine\DBAL\Driver\Connection $connection */
        $connection = $this->doctrine->getConnection();
        $connection->exec('truncate table transaction');
    }

    /**
     * Выполняет HTTP запрос
     */
    private function request(array $data): Response
    {
        $this->client->request('POST', '/transfer', [], [], [], json_encode($data));
        return $this->client->getResponse();
    }

    /**
     * @dataProvider moneyTransferDataProvider
     * @param $senderAccountId
     * @param $recipientAccountId
     * @param $transferringMoney
     */
    public function testPerformMoneyTransfer(
        $senderAccountId,
        $recipientAccountId,
        $transferringMoney
    ) {

        $this->truncate();

        $response = $this->request([
            'sender' => [
                'id' => $senderAccountId,
                'type' => 'account-identifier',
            ],
            'recipient' => [
                'id' => $recipientAccountId,
                'type' => 'account-identifier',
            ],
            'money' => [
                'value' => $transferringMoney[1],
                'currency' => [
                    'code' => $transferringMoney[0],
                ]
            ]
        ]);

        //$this->assertEquals(200, $response->getStatusCode(), $response->getContent());

        $this->assertJson($response->getContent());

        $this->truncate();
    }

    public function moneyTransferDataProvider()
    {
        return [
            [
                /** Перевод с системного счета на пользовательский счет */
                '00000000000000009850',
                '10020010002000009850',
                [9850, 10],
            ],
            [
                /** Перевод между пользовательскими счетами */
                '10010010002000009850',
                '20020020002000009850',
                [9850, 10],
            ],
            [
                /** Перевод с пользовательского счета на системный счет */
                '20020010002000009850',
                '00000000000000009850',
                [9850, 10],
            ],
        ];
    }

    /**
     * Негативный тест
     * @dataProvider apiExceptionDataProvider
     * @param $senderAccountId
     * @param $recipientAccountId
     * @param $transferringMoney
     * @param $errorCode
     * @param $errorMessage
     */
    public function testApiException(
        $senderAccountId,
        $recipientAccountId,
        $transferringMoney,
        $errorCode,
        $errorMessage
    ) {
        $response = $this->request([
            'sender' => [
                'id' => $senderAccountId,
                'type' => 'account-identifier',
            ],
            'recipient' => [
                'id' => $recipientAccountId,
                'type' => 'account-identifier',
            ],
            'money' => [
                'value' => $transferringMoney[1],
                'currency' => [
                    'code' => $transferringMoney[0],
                ]
            ]
        ]);

        $this->assertEquals(400, $response->getStatusCode());

        $this->assertJson($response->getContent());

        $this->assertJsonStringEqualsJsonString(
            $response->getContent(),
            '{"code":' . $errorCode . ',"message":"' . $errorMessage . '"}'
        );
    }

    /**
     * Негативные кейсы
     * @return array
     */
    public function apiExceptionDataProvider()
    {
        return [
            [
                /** Перевод между одним счетом */
                1002001000200000 . 9850,
                1002001000200000 . 9850,
                [9850, 1],
                0,
                'Impossible transfer money between same account'
            ], [
                /** Сумма перевода равна 0 */
                1002001000200000 . 9850,
                2002001000100000 . 9850,
                [9850, 0],
                0,
                'Attempt transfer zero amount'
            ], [
                1002001000200000 . 9851,
                1002001000100000 . 9851,
                [9850, 1],
                0,
                'Currencies mismatch money transfer'
            ],
            [
                1002001000200000 . 9851,
                1002001000100000 . 9850,
                [9850, 1],
                0,
                'Currencies mismatch money transfer'
            ],
            [
                1002001000200000 . 9850,
                1002001000100000 . 9851,
                [9850, 1],
                0,
                'Currencies mismatch money transfer'
            ],
            [
                1002001000200000 . 9852,
                1002001000100000 . 9851,
                [9850, 1],
                0,
                'Currencies mismatch money transfer'
            ],
            [
                /** Исключение "SenderBalanceIsEmptyException" */
                1002001000200000 . 9852,
                2002001000100000 . 9852,
                [9852, 10],
                0,
                'Sender balance is empty'
            ],
        ];
    }
}
