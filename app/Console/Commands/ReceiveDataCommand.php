<?php

namespace App\Console\Commands;

use App\Interfaces\Currencies;
use App\Models\Stock;
use Illuminate\Console\Command;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ReceiveDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:receive-data-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Receive stock data from the API';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // With 10 requests it is ok
        // but with 1000 requests it is better to spread them in time
        // and use some queue and processing by chunks
        //
        // It is better to get retry settings from config
        $responses = HTTP::retry(3, 100)
            ->pool($this->getPoolQuery(...));

        collect($responses)->each(function ($response) {
            if ($response->getStatusCode() !== 200) {
                $this->error('Error with API');

                // Problem after retrying
                // It is better to report the problem
                // but don stop the process
                // to somewhere like sentry
                return;
            }

            $data = json_decode($response->getBody()->getContents(), true);

            if (! $data) {
                $this->error('Error with API');

                // Detect problems with api
                // For example with api limit exceeding
                // It is better to report the problem
                // to somewhere like sentry
                return;
            }

            $data = $data['Realtime Currency Exchange Rate'] ?? null;

            if (! $data) {
                $this->error('Error with format');

                return;
            }

            $stock = (new Stock)->fill([
                'name' => $data['1. From_Currency Code'].':'.$data['3. To_Currency Code'],
                'price' => $data['5. Exchange Rate'] * 10000, // keep in integer with 4 decimal places
            ]);

            $stock->save();

            // Cache settings like connection and ttl
            // should be moved to config
            Cache::set($stock->name, $stock->price, 60);
        });
    }

    protected function getPoolQuery(Pool $pool): array
    {
        return collect(Currencies::CURRENCIES)->map(function ($currency) use ($pool) {
            [$from, $to] = explode(':', $currency);
            // Move to some service behind the service in the container
            $pool->get('https://www.alphavantage.co/query', [
                'function' => 'CURRENCY_EXCHANGE_RATE',
                'from_currency' => $from,
                'to_currency' => $to,
                'apikey' => env('API_KEY'),
            ]);
        })->toArray();
    }
}
