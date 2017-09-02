<?php

namespace Tests\Unit;

use App\Dishes\ValidationCheckers\ItemPriceMustStartWithDollarSign;
use App\Dishes\ValidationCheckers\ItemsMustHavePrices;
use App\Dishes\ValidationCheckers\MustStartWithTotalAmountToSpend;
use App\Exceptions\DataSetFileValidationException;
use Tests\TestCase;

class ValidateDataSetTest extends TestCase
{
    /** @test */
    public function a_file_must_have_total_amount_to_spend_as_first_line()
    {
        $content = file_get_contents(base_path('tests/datasets/no_total_amount_as_first_line.txt'));

        try {
            (new MustStartWithTotalAmountToSpend($content))->check();
        } catch (DataSetFileValidationException $exception) {
            return $this->assertTrue(true);
        }

        $this->fail('File does not start with a dollar amount, but passed the test as it did start with a dollar amount.');
    }

    /** @test */
    public function items_must_have_a_price()
    {
        $content = file_get_contents(base_path('tests/datasets/items_without_prices.txt'));

        try {
            (new ItemsMustHavePrices($content))->check();
        } catch (DataSetFileValidationException $exception) {
            return $this->assertTrue(true);
        }

        $this->fail('Items did not have prices, but reported that they did.');
    }

    /** @test */
    public function items_prices_must_start_with_a_dollar_sign()
    {
        $content = file_get_contents(base_path('tests/datasets/items_without_price_dollar_signs.txt'));

        try {
            (new ItemPriceMustStartWithDollarSign($content))->check();
        } catch (DataSetFileValidationException $exception) {
            return $this->assertTrue(true);
        }

        $this->fail('Item prices did not start with a dollar sign, but reported that they did.');
    }
}
