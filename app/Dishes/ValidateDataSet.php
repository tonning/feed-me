<?php

namespace App\Dishes;

use App\Dishes\ValidationCheckers\ItemPriceMustStartWithDollarSign;
use App\Dishes\ValidationCheckers\ItemsMustHavePrices;
use App\Dishes\ValidationCheckers\MustStartWithTotalAmountToSpend;

class ValidateDataSet
{
    protected $content;

    public function __construct($file)
    {
        $this->content = file_get_contents($file);
    }

    public static function file($file)
    {
        (new self($file))->check();
    }

    public function check()
    {
        $checkers = [
            MustStartWithTotalAmountToSpend::class,
            ItemsMustHavePrices::class,
            ItemPriceMustStartWithDollarSign::class,
        ];

        foreach ($checkers as $checker) {
            (new $checker($this->content))->check();
        }

        return true;
    }
}
