<?php

namespace Tests\Unit\Console\Commands;

use App\Console\Commands\ReceiveDataCommand;
use App\Interfaces\Currencies;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ReceiveDataCommandTest extends TestCase
{
    use RefreshDatabase;

    public function testHandleCommand()
    {
        $command = new ReceiveDataCommand();

        $sequence = Http::fakeSequence();
        foreach (Currencies::CURRENCIES as $currency) {
            [$from, $to] = explode(':', $currency);
            $price = fake()->randomFloat(4, 1, 1000);
            $sequence->push(
                <<<JSON
{
    "Realtime Currency Exchange Rate":
    {
        "1. From_Currency Code": "$from",
        "3. To_Currency Code": "$to",
        "5. Exchange Rate": "$price"
    }
}
JSON
            );
        }
        $command->handle();

        $this->assertDatabaseCount('stocks', count(Currencies::CURRENCIES));
    }
}
