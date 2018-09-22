# PHP Constant References

Allows objects to be saved in PHP constants. PHP normally allows only primitive types
in constants - this library makes possible to save anything in them, as in ECMAScript.
Constant references may contain mutable data, but they can only be assigned once.

```php
<?php

namespace Netmosfera\ConstantReferencesExample;

use const Vendor\Library\MY_CONSTANT;
use function Netmosfera\ConstantReferences\define;

// Define a constant - set it to a Closure:
define("Vendor\\Library\\MY_CONSTANT", function(String $message){
    return "42 " . $message;
});

// Execute the function in the constant reference:
assert("42 hello" === (MY_CONSTANT)("hello"));

// Save it somewhere else:
$myVar = MY_CONSTANT;
assert("42 hello" === $myVar("hello"));
```

All operations are supported with the following exceptions, which cause an error:

```
(MY_CONSTANT)->write = 123;
unset((MY_CONSTANT)->write);
(MY_CONSTANT)[123] = 123;
unset((MY_CONSTANT)[123]);
```

## OPCache support:

OPCache looks to be mostly working. Follows a list of optimizations that might be enabled
with this library (source:
[zend_optimizer.h](https://lxr.room11.org/xref/php-src%40master/ext/opcache/Optimizer/zend_optimizer.h)).
It is recommended to test each one of these.

```
#define ZEND_OPTIMIZER_PASS_1  (1<<0)   /* CSE, STRING construction       */ WORKS
#define ZEND_OPTIMIZER_PASS_2  (1<<1)   /* Constant conversion and jumps  */ DOES NOT WORK
#define ZEND_OPTIMIZER_PASS_3  (1<<2)   /* ++, +=, series of jumps        */ WORKS
#define ZEND_OPTIMIZER_PASS_4  (1<<3)   /* INIT_FCALL_BY_NAME -> DO_FCALL */ WORKS
#define ZEND_OPTIMIZER_PASS_5  (1<<4)   /* CFG based optimization         */ WORKS
#define ZEND_OPTIMIZER_PASS_6  (1<<5)   /* DFA based optimization         */ WORKS
#define ZEND_OPTIMIZER_PASS_7  (1<<6)   /* CALL GRAPH optimization        */ WORKS
#define ZEND_OPTIMIZER_PASS_8  (1<<7)   /* SCCP (constant propagation)    */ WORKS
#define ZEND_OPTIMIZER_PASS_9  (1<<8)   /* TMP VAR usage                  */ WORKS
#define ZEND_OPTIMIZER_PASS_10 (1<<9)   /* NOP removal                    */ WORKS
#define ZEND_OPTIMIZER_PASS_11 (1<<10)  /* Merge equal constants          */ WORKS
#define ZEND_OPTIMIZER_PASS_12 (1<<11)  /* Adjust used stack              */ WORKS
#define ZEND_OPTIMIZER_PASS_13 (1<<12)  /* Remove unused variables        */ WORKS
#define ZEND_OPTIMIZER_PASS_14 (1<<13)  /* DCE (dead code elimination)    */ WORKS
#define ZEND_OPTIMIZER_PASS_15 (1<<14)  /* (unsafe) Collect constants     */ WORKS
#define ZEND_OPTIMIZER_PASS_16 (1<<15)  /* Inline functions               */ DOES NOT WORK
```

```
[opcache]
opcache.enable = 1
opcache.optimization_level = 0x0fffffffffffff0f
```
