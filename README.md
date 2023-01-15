# Euribor rates

A simple library to get Euribor rates.

## Installing

Use Composer to install it:

```
composer require filippo-toso/euribor
```

## How does it work?

It's really easy:

```php
use FilippoToso\Euribor\Euribor;

$results = Euribor::read();

print_r($results);
```
That's it!