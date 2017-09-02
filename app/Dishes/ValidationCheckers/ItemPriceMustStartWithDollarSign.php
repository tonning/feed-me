<?php

namespace App\Dishes\ValidationCheckers;

use App\Exceptions\DataSetFileValidationException;

class ItemPriceMustStartWithDollarSign extends BaseValidationChecker
{
    public function check()
    {
        unset($this->lines[0]);

        foreach ($this->lines as $index => $line) {
            $line = explode(',', $line);

            $lineNumber = $index + 1;

            throw_unless(substr($line[1], 0, 1) == '$', DataSetFileValidationException::class,
                "{$line[0]}'s price (on line {$lineNumber}) does not have a price");
        }

        return true;
    }
}
