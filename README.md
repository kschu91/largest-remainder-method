[![Build Status](https://travis-ci.org/kschu91/largest-remainder-method.svg?branch=master)](https://travis-ci.org/kschu91/largest-remainder-method)
[![Code Coverage](https://scrutinizer-ci.com/g/kschu91/largest-remainder-method/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/kschu91/largest-remainder-method/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/kschu91/largest-remainder-method/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/kschu91/largest-remainder-method/?branch=master)

# largest remainder method algorithm

A PHP implementation of the [largest remainder method](https://en.wikipedia.org/wiki/Largest_remainder_method) algorithm. This method is the most common way to get rid of rounding issues when working with rounded percentage values.

## The problem

Let´s use an example:
```
18.562874251497007%
20.958083832335326%
18.562874251497007%
19.161676646706585%
22.75449101796407%
```
When rounding the above percentages using PHP´s rounding functions, we get:

```
19%
21%
19%
19%
23%
```

Which in fact sums up to `101%` instead of `100%`. The largest remainder method solves this issue by doing the following steps:

1. Rounding all values down to the nearest integer value;
2. Determining the difference between the sum of the rounded values and total value;
3. Distributing the difference between the rounded values in decreasing order of their decimal parts.

## Installation

```bash
composer require "kschu91/largest-remainder-method"
```

If you are not familiar with composer:
[composer basic usage](https://getcomposer.org/doc/01-basic-usage.md)

### Requirements
- PHP >= 7.0

## Basic Usage

```php
$numbers = [
    18.562874251497007,
    20.958083832335326,
    18.562874251497007,
    19.161676646706585,
    22.75449101796407
];

$lr = new LargestRemainder($numbers);

print_r($lr->round());
```
which results in:
```
Array
(
    [0] => 19
    [1] => 21
    [2] => 18
    [3] => 19
    [4] => 23
)
```

## Working with decimals aka. precision
The default precision is set to `0`. But you can change this behaviour by using the `setPrecision` method:
```php
$numbers = [
    18.562874251497007,
    20.958083832335326,
    18.562874251497007,
    19.161676646706585,
    22.75449101796407
];

$lr = new LargestRemainder($numbers);
$lr->setPrecision(2);

print_r($lr->round());
```
which results in:
```
Array
(
    [0] => 18.55
    [1] => 20.94
    [2] => 18.55
    [3] => 19.15
    [4] => 22.74
)
```

## Working with complex arrays/objects
Mostly, you won´t have the numbers you want to apply this algorithm on in a simple key value paired arrays as in the examples above. You rather have them in objects or associative arrays.
That´s why this library also supports callbacks for applying this algorithm.

You just have to supply 2 callbacks to the `usort` method. The first one, to fetch the relevant number from the object. And the second one to write the rounded number back to the object.
```php
$objects = [
    ['a' => 18.562874251497007],
    ['a' => 20.958083832335326],
    ['a' => 18.562874251497007],
    ['a' => 19.161676646706585],
    ['a' => 22.75449101796407]
];

$lr = new LargestRemainder($objects);
$lr->setPrecision(2);

print_r($lr->uround(
    function ($item) {
        return $item['a'];
    },
    function (&$item, $value) {
        $item['a'] = $value;
    }
));
```
which results in:
```
Array
(
    [0] => Array
        (
            [a] => 18.55
        )

    [1] => Array
        (
            [a] => 20.94
        )

    [2] => Array
        (
            [a] => 18.55
        )

    [3] => Array
        (
            [a] => 19.15
        )

    [4] => Array
        (
            [a] => 22.74
        )

)
```