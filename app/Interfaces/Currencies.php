<?php

namespace App\Interfaces;

// TODO Ok for coding challenge but replace with real storage
interface Currencies
{
    public const USDJPY = 'USD:JPY';
    public const GBPJPY = 'GBP:JPY';
    public const AUDJPY = 'AUD:JPY';
    public const NZDJPY = 'NZD:JPY';
    public const CADJPY = 'CAD:JPY';
    public const CHFJPY = 'CHF:JPY';
    public const EURUSD = 'EUR:USD';
    public const GBPUSD = 'GBP:USD';
    public const AUDUSD = 'AUD:USD';

    public const CURRENCIES = [
        self::USDJPY,
        self::GBPJPY,
        self::AUDJPY,
        self::NZDJPY,
        self::CADJPY,
        self::CHFJPY,
        self::EURUSD,
        self::GBPUSD,
        self::AUDUSD,
    ];
}
