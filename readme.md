[![Build Status](https://travis-ci.org/mobileka/liner.svg)](https://travis-ci.org/mobileka/liner)
[![Code Climate](https://codeclimate.com/github/mobileka/liner.svg)](https://codeclimate.com/github/mobileka/liner)
[![Coverage Status](https://coveralls.io/repos/mobileka/liner/badge.svg?branch=master)](https://coveralls.io/r/mobileka/liner?branch=master)

Liner is a fast and dead simple file reader which allows reading files line by line.

It has been tested with a number of huge files including those with more than 5 million rows and proved itself as a fast and efficient file reader.

Requires PHP 5.4 or newer.

## Installation

`composer require mobileka/liner:1.1.*`

And sometimes I find myself looking for this line in installation section:

`"mobileka/liner": "1.1.*"`

## Usage

```php

$liner = new Liner('path/to/a/file'); // or SplFileObject instance

// Read the whole file
$liner->read();

// Only the first line
$liner->read(1);

// Only the second line
$liner->read(1, 1);

// Read 100 lines starting with the 6th line
$liner->read(100, 5);

// You can also pass a closure as a third argument to mutate the result without iterating over it
// Here's how you can read a CSV file:
$csvAsArray = $liner->read(0, 0, function($file, $line) {
    $line = trim($line);
    return explode(',', $line);
});

// A line will be ignored if modifier returns null
$anEmptyArray = $liner->read(0, 0, function($file, $line) {
    return null;
});

// almost forgot to mention that you can get the number of lines
$liner->getNumberOfLines();

// and that you can also delegate methods to SplFileObject (is it a good idea though?)
$liner->eof();
$liner->rewind();
```

## License

Liner is an open-source software and licensed under the [MIT License](https://github.com/mobileka/liner/blob/master/license).
