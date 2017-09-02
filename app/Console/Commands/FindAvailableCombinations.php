<?php

namespace App\Console\Commands;

use App\Dishes\GimmeFoodForAllMyMoney;
use App\Dishes\ValidateDataSet;
use App\Exceptions\DataSetFileValidationException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class FindAvailableCombinations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feed:me';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show all available combinations of menu items.';

    protected $file;

    protected $lines;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $files = Storage::disk('datasets')->files();

        if (! $files) {
            return $this->error('No data sets found. Please place your data set files in the "/storage/datasets/" directory.');
        }

        $this->file = $this->choice('Which file would you like to check?', $files, 0);

        try {
            ValidateDataSet::file(storage_path('datasets/' . $this->file));
        } catch (DataSetFileValidationException $exception) {
            return $this->error($exception->getMessage());
        }

        $this->lines = $this->getLinesFromFile($this->file);
        $amount = $this->getTotalAmount();
        $items = $this->getItems();

        $bills = (new GimmeFoodForAllMyMoney())
            ->spend($amount)
            ->onTheseItems($items)
            ->showMeAllMyOptions();

        return $this->displayResults($amount, $items, $bills);
    }

    /**
     * Read the lines from the given file and convert into an array.
     *
     * @param $file
     *
     * @return mixed
     */
    protected function getLinesFromFile($file)
    {
        $content = Storage::disk('datasets')->get($file);

        $lines = preg_split('/\n|\r\n?/', $content);

        return $this->sanitizeContent($lines);
    }

    /**
     * Convert a dollar amount string to cents.
     *
     * @param $dollarAmountString
     *
     * @return int
     */
    protected function convertDollarAmountStringToCents($dollarAmountString)
    {
        $dollarAmount = ltrim($dollarAmountString, '$');

        return (int)($dollarAmount * 100);
    }

    /**
     * Convert from cents to dollar amount.
     *
     * @param $cents
     *
     * @return string
     */
    protected function convertCentsToDollarAmountString($cents)
    {
        return '$' . number_format(($cents / 100), 2, '.', ' ');
    }

    /**
     * Get the total amount to spend.
     *
     * @return int
     */
    protected function getTotalAmount()
    {
        return $this->convertDollarAmountStringToCents($this->lines[0]);
    }

    /**
     * Get menu items.
     *
     * @return array
     */
    protected function getItems()
    {
        $items = $this->lines;

        unset($items[0]);

        return collect($items)->transform(function ($item) {
            $line = explode(',', $item);

            $name = $line[0];
            $price = $this->convertDollarAmountStringToCents($line[1]);

            return ['name' => $name, 'price' => $price];
        })->values()->toArray();

        return $items;
    }

    /**
     * Sanitize the lines by removing empty lines.
     *
     * @param $lines
     *
     * @return mixed
     */
    private function sanitizeContent($lines)
    {
        return collect($lines)->reject(function ($line) {
            return ! $line;
        })
            ->values()
            ->toArray();
    }

    /**
     * Display the available combinations.
     *
     * @param $amount
     * @param $items
     * @param $bills
     */
    protected function displayResults($amount, $items, $bills)
    {
        $this->line("If you have {$this->convertCentsToDollarAmountString($amount)} to spend on the these items:");
        $this->table(['Dish', 'Price'], $this->transformItemsForTableView($items));

        $this->line('');

        if (! $bills) {
            return $this->comment('Oh bummer! Then you would have to go to bed hungry. There are no available combinations.');
        } elseif (count($bills) > 1) {
            $this->line('Then you would be able to choose between the following combinations:');
        } else {
            $this->comment('Then these are the yummy dishes you would be able to get:');
        }

        foreach ($bills as $index => $bill) {
            $this->line('');

            if (count($bills) > 1) {
                $combinationNumber = $index + 1;
                $this->comment("Combination #{$combinationNumber}");
            }

            $this->table(['Dish', 'Price'], $this->transformItemsForTableView($bill));
        }
    }

    /**
     * @param $items
     *
     * @return $this
     */
    protected function transformItemsForTableView($items)
    {
        return collect($items)->transform(function ($item) {
            return [
                'name' => $item['name'],
                'price' => $this->convertCentsToDollarAmountString($item['price']),
            ];
        });
    }
}
