<?php

declare(strict_types=1);

namespace App\ProxyChecker\Application\Proxy\Service;

use GuzzleHttp\Client;

readonly class ProxyChecker
{
    public function __construct(
    ) {
    }

    public function check(string $proxy): void
    {
        $client = new Client([
            'timeout' => 10,
            'proxy' => "http://{$proxy}",
        ]);

        $start = microtime(true);

        $response = $client->get('https://www.google.com');

        $end = microtime(true);
        $duration = ($end - $start) * 1000; // Преобразовываем в миллисекунды

        dd('Время скачивания через прокси: ' . $duration . ' мс');
    }
}
