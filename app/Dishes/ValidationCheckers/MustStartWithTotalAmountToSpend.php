<?php

namespace App\Dishes\ValidationCheckers;

use App\Exceptions\DataSetFileValidationException;

class MustStartWithTotalAmountToSpend extends BaseValidationChecker
{
    public function check()
    {
        $amount = (substr($this->lines[0], 1));

        throw_unless(substr($this->lines[0], 0, 1) == '$', DataSetFileValidationException::class, 'First line does not start with dollar amount');

        throw_unless(is_numeric($amount), DataSetFileValidationException::class, 'Total amount is not a numeric value');

        return true;
    }
}
