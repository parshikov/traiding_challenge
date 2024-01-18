<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class StockController extends Controller
{
    public function stock(Request $request, string $stock): JsonResponse
    {
        // In real life it is better to use a middleware for this
        $stock = strtoupper($stock);

        // Cache is better to be moved to controller constructor to get right connection only
        $result = Cache::remember(
            $stock,
            60,
            // may be it worth to check both pairs like USD:CHF and CHF:USD
            // but here we make only exactly requested pair
            fn() => Stock::query()->where('name', $stock)
                ->latest('created_at')->firstOrFail(),
        );

        return response()
            ->json($result);
    }

    public function trend(Request $request, string $stock): JsonResponse
    {
        $stock = strtoupper($stock);
        $data = Stock::where('name', $stock)->orderBy('created_at', 'desc')->limit(2)->get();

        if ($data->count() < 2) {
            return response()
                ->json([
                    'message' => 'Not enough data',
                ]);
        }

        /** @var Stock $last */
        /** @var Stock $previous */
        [$last, $previous] = $data;
        // result depends on the time of creating the data
        // in real life it must be calculated paying attention to the time interval between the prices
        $trend = round(($last->price - $previous->price) / $previous->price * 100, 4);

        return response()
            ->json(['trend' => $trend]);
    }
}
