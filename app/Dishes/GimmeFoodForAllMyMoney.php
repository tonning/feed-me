<?php

namespace App\Dishes;

class GimmeFoodForAllMyMoney
{
    protected $amountToSpend;
    protected $bills = [];
    protected $iterationCount = 0;
    protected $menuItems;

    /**
     * Set total amount to spend.
     *
     * @param int $amount
     *
     * @return $this
     */
    public function spend(int $amount)
    {
        $this->amountToSpend = $amount;

        return $this;
    }

    /**
     * Set items to choose from.
     *
     * @param array $items
     *
     * @return $this
     */
    public function onTheseItems(array $items)
    {
        $this->menuItems = $items;

        return $this;
    }

    /**
     * Return all available combinations.
     *
     * @return array|void
     */
    public function showMeAllMyOptions()
    {
        $this->lookThroughMenuItems($this->menuItems, $this->amountToSpend);

        return $this->bills;
    }

    /**
     * Iterate over menu items.
     *
     * @param $amountToSpend
     * @param $menuItems
     * @param array $bill
     *
     * @return array|void
     */
    protected function lookThroughMenuItems($menuItems, $amountToSpend, $bill = [])
    {
        $orderItems = $menuItems;
        $this->iterationCount++;

        foreach ($menuItems as $index => $item) {
            $moneyLeftToSpend = $amountToSpend - $item['price'];

            if ($moneyLeftToSpend == 0) {
                $bill[] = $item; // Add last item to the bill.

                $this->saveBill($bill);

                return;
            }

            if ($moneyLeftToSpend > 0) {
                $this->lookThroughMenuItems($orderItems, $moneyLeftToSpend, $this->addItemToBill($bill, $item));
            }

            /*
            | Since at this point we have checked for all combinations in this search element,
            | for example the first step will find everything that has at least one "mixed fruit",
            | which means we can safely remove it to reduce further number of iterations.
            */
            unset($orderItems[$index]);
        }

        return $this->bills;
    }

    /**
     * Add an item to a bill.
     *
     * @param $bill
     * @param $item
     *
     * @return array
     */
    protected function addItemToBill($bill, $item)
    {
        return array_merge($bill, [$item]);
    }

    /**
     * Add a bill to our collection of bills.
     *
     * @param $bill
     */
    protected function saveBill($bill)
    {
        $this->bills[] = $bill;
    }
}
