<?php

namespace Tests\Feature;

use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testCorrectPrice(): void
    {
        $stock = Stock::factory()->create([
            'name' => 'XOF:HNL',
            'price' => 1236,
        ]);

        $response = $this->get("/stock/$stock->name");

        $response->assertStatus(200);
        $response->assertContent('{"name":"XOF:HNL","price":1236}');
    }

    public static function correctStockTrendProvider(): array
    {
        return [
            'positive' => [200, 100, '{"trend":100}',],
            'negative' => [100, 200, '{"trend":-50}',],
            'zero' => [100, 100, '{"trend":0}',],
        ];
    }

    /**
     * @param $last
     * @param $prev
     * @param $expected
     *
     * @return void
     * @dataProvider correctStockTrendProvider
     */
    public function testCorrectTrend(int $last, int $prev, string $expected): void
    {
        $stockName = 'XOF:HNL';
        Carbon::setTestNow(Carbon::create(2024, 1, 18, 14, 45, 56));
        Stock::factory()->create([
            'name' => $stockName,
            'price' => $prev,
        ]);
        Carbon::setTestNow(Carbon::create(2024, 1, 18, 14, 45, 57));
        Stock::factory()->create([
            'name' => $stockName,
            'price' => $last,
        ]);
        Carbon::setTestNow();

        $response = $this->get("/trend/$stockName");

        $response->assertStatus(200);
        $response->assertContent($expected);
    }

    public function testInCorrectTrendWithOneRecord(): void
    {
        $stockName = 'XOF:HNL';
        Stock::factory()->create([
            'name' => $stockName,
            'price' => 100,
        ]);
        Carbon::setTestNow();

        $response = $this->get("/trend/$stockName");

        $response->assertStatus(200);
        $response->assertContent('{"message":"Not enough data"}');
    }

    public function testPriceWithoutData(): void
    {
        $stockName = 'XOF:HNL';
        $response = $this->get("/stock/$stockName");

        $response->assertStatus(404);
    }
}
