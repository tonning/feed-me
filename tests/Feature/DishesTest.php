<?php

namespace Tests\Feature;

use App\Dishes\GimmeFoodForAllMyMoney;
use Tests\TestCase;

class DishesTest extends TestCase
{
    /** @test */
    public function can_find_all_available_combinations_of_appetizers()
    {
        $items = [
            ['name' => 'mixed fruit', 'price' => 215],
            ['name' => 'french fries', 'price' => 275],
            ['name' => 'side salad', 'price' => 335],
            ['name' => 'hot wings', 'price' => 355],
            ['name' => 'mozzarella sticks', 'price' => 420],
            ['name' => 'sampler plate', 'price' => 580],
        ];

        $bills = (new GimmeFoodForAllMyMoney())
            ->spend(1505)
            ->onTheseItems($items)
            ->showMeAllMyOptions();

        $this->assertCount(2, $bills);
        $this->assertArraySubset([
            ['name' => 'mixed fruit', 'price' => 215],
            ['name' => 'mixed fruit', 'price' => 215],
            ['name' => 'mixed fruit', 'price' => 215],
            ['name' => 'mixed fruit', 'price' => 215],
            ['name' => 'mixed fruit', 'price' => 215],
            ['name' => 'mixed fruit', 'price' => 215],
            ['name' => 'mixed fruit', 'price' => 215],
        ], $bills[0]);
        $this->assertArraySubset([
            ['name' => 'mixed fruit', 'price' => 215],
            ['name' => 'hot wings', 'price' => 355],
            ['name' => 'hot wings', 'price' => 355],
            ['name' => 'sampler plate', 'price' => 580],
        ], $bills[1]);
    }

    /** @test */
    public function can_find_all_available_combinations_of_entrees()
    {
        $items = [
            ['name' => 'smoked salmon', 'price' => 120],
            ['name' => 't-bone steak', 'price' => 240],
            ['name' => 'seafood plater', 'price' => 355],
        ];

        $bills = (new GimmeFoodForAllMyMoney())
            ->spend(1070)
            ->onTheseItems($items)
            ->showMeAllMyOptions();

        $this->assertCount(2, $bills);
        $this->assertArraySubset([
            ['name' => 'smoked salmon', 'price' => 120],
            ['name' => 'smoked salmon', 'price' => 120],
            ['name' => 'smoked salmon', 'price' => 120],
            ['name' => 'seafood plater', 'price' => 355],
            ['name' => 'seafood plater', 'price' => 355],
        ], $bills[0]);
        $this->assertArraySubset([
            ['name' => 'smoked salmon', 'price' => 120],
            ['name' => 't-bone steak', 'price' => 240],
            ['name' => 'seafood plater', 'price' => 355],
            ['name' => 'seafood plater', 'price' => 355],
        ], $bills[1]);
    }
}
