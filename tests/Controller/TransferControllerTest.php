<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TransferControllerTest extends WebTestCase
{
    /** @var Client */
    private $client;

    public function setUp()
    {
        parent::setUp();

        $this->client = static::createClient();
    }

    public function testPerformMoneyTransfer()
    {
        $this->client->request('POST', '/transfer', [], [], [], json_encode([
            'sender' => [
                'id' => 2002001000200000 . 9850,
                'type' => 'account-identifier',
            ],
            'recipient' => [
                'id' => 1002001000200000 . 9850,
                'type' => 'account-identifier',
            ],
            'money' => [
                'value' => 10,
                'currency' => [
                    'code' => 9850,
                ]
            ]
        ]));

        $response = $this->client->getResponse();

        //$this->assertEquals(200, $response->getStatusCode());

        $this->assertJson($response->getContent());
    }

    /**
     * Негативный тест
     * @dataProvider validationExceptionsDataProvider
     * @param $senderAccountId
     * @param $recipientAccountId
     * @param $transferringMoney
     * @param $errorCode
     * @param $errorMessage
     */
    public function testValidationException(
        $senderAccountId,
        $recipientAccountId,
        $transferringMoney,
        $errorCode,
        $errorMessage
    ) {
        $this->client->request('POST', '/transfer', [], [], [], json_encode([
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
        ]));

        $response = $this->client->getResponse();

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
    public function validationExceptionsDataProvider()
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
        ];
    }
}
