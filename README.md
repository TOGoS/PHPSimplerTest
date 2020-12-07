# PHPSimplerTest, a.k.a. togos/simpler-test

Nearly drop-in replacement for PHPUnit, assuming you're only extending TestCase
and and calling ```assert{True,False,Equals,NotEquals,Null,NotNull}```
from ```testX``` methods.

Inherit from ```TOGoS_SimplerTest_TestCase```
and use ```phpsimplertest``` to run your tests from the command-line.

For examples, see PHPSimplerTest's own tests and Makefile.


## Installing

Using composer:

```
composer require phpsimplertest=^1.2
```


## Running

Assuming you have used Composer to install phpsimplest
and that you have a source directory, ```src/test/php```, containing
source code for test case classes:

```
vendor/bin/phpsimplertest --bootstrap=vendor/autoload.php --colorful-output src/test/php
```

```--colorful-output``` results in a nice green line of text being output
when all goes well.
If you are running tests as part a script,
you probably want to omit that.
