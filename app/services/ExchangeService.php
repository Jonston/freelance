<?php

namespace App\Services;

use GuzzleHttp\Client;

class ExchangeService
{
    private $client;

    const TYPE_SALE = 'sale';

    const TYPE_BUY = 'buy';

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @param float $amount
     * @param string $type
     * @return float
     */
    public function RUB2UAH(float $amount = 1, $type = self::TYPE_SALE)
    {
        $response = $this->client->request('GET', 'https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=11');

        $rate = json_decode($response->getBody()->getContents(), true)[2];

        return $rate[$type] * $amount;
    }

    /**
     * @param float $amount
     * @param string $type
     * @return float
     */
    public function UAH2RUB(float $amount = 1, $type = self::TYPE_SALE)
    {
        $rate = $this->RUB2UAH($amount, ($type === self::TYPE_SALE) ? self::TYPE_BUY : self::TYPE_SALE);

        return 1 / $rate * $amount;
    }


}
