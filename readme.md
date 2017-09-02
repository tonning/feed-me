## Challenge Description
> Write a program to read in the data file and find all combinations of dishes that add up exactly to the target price from the first line.  
If you can’t find a solution, your program should state that there isn’t one.  
You should also do some basic error checking on the format of the file, if its missing, etc.

## Setup
* Clone git repository `git clone https://github.com/tonning/feed-me.git`  
* Change into directory `cd feed-me`  
* Install composer dependencies `composer install`
* Run `php artisan feed:me`  
* Follow the prompt  

## Data Sets
More data sets can be checked by placing them in the `/storage/datasets/` directory and running the `php artisan feed:me` command.

The data set files must be in the following format:
* First line must contain the total amount to be spend and nothing else. 
* The total amount to be spend must have a dollar sign `$` in front of it.
* Each menu item must be on its own line.
* The price for each item must be separated by a comma (`,`) following immediately after the item name.
* The menu item price must have a dollar sign (`$`) in front of the amount. 

### Provided Sample Data Set
```text
$15.05
mixed fruit,$2.15
french fries,$2.75
side salad,$3.35
hot wings,$3.55
mozzarella sticks,$4.20
sampler plate,$5.80
```

## Tests
```shell
composer test
```  
