<?php

namespace App\Constant;

class Currency
{
    const EURO = 'EURO';
    const DOLLAR = 'DOLLAR';
    const FRANC_SUISSE = 'FRANC_SUISSE';
    const POUND_STERLING = 'POUND_STERLING';

    const CURRENCIES = [
        self::DOLLAR,
        self::EURO,
        self::FRANC_SUISSE,
        self::POUND_STERLING,
    ];
}
