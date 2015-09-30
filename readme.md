[![Build Status](https://travis-ci.org/mobileka/liner.svg)](https://travis-ci.org/mobileka/liner)
[![Code Climate](https://codeclimate.com/github/mobileka/liner.svg)](https://codeclimate.com/github/mobileka/liner)
[![Coverage Status](https://coveralls.io/repos/mobileka/liner/badge.svg?branch=master)](https://coveralls.io/r/mobileka/liner?branch=master)

Liner is a dead simple file reader which allows setting offset and limit (by lines).

Requires PHP 5.4 or newer.

## Installation

`composer require mobileka/liner`

And sometimes I find myself looking for this line in installation section:

`"mobileka/liner": "1.0.*"`

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

// You can also pass a closure as a third argument to mutate the result
$iHateExclamationMarks = $liner->read(0, 0, function($file, $line) {
    return str_replace('!', '', $line);
});

// almost forgot to mention that you can get the number of lines
$liner->getNumberOfLines();

// and that you can also delegate methods to SplFileObject
$liner->eof();
$liner->rewind();
```

## License

Liner is an open-source software and licensed under the [MIT License](https://github.com/mobileka/liner/blob/master/license).
