<?php declare(strict_types = 1); // atom

namespace Netmosfera\ConstantReferencesTests;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use ArrayAccess;
use Iterator;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * @method CALL_METHOD($a, $b, $c, $d, $e)
 * @method static CALL_STATIC_METHOD($a, $b, $c, $d, $e)
 */
class MagicMethods implements ArrayAccess, Iterator
{
    public $maxIterations = 4;
    public $iteration = "";

    public $rewinds = 0;
    function rewind(){
        $this->iteration .= "-rewind" . ++$this->rewinds;
    }

    public $nexts = 0;
    function next(){
        $this->iteration .= "-next" . ++$this->nexts;
    }

    public $valids = 0;
    function valid(){
        $this->iteration .= "-valid" . ++$this->valids;
        return $this->valids <= $this->maxIterations;
    }

    public $keys = 0;
    function key(){
        $this->iteration .= "-key" . ++$this->keys;
        return $this->keys;
    }

    public $currents = 0;
    function current(){
        $this->iteration .= "-current" . ++$this->currents;
        return $this->currents;
    }

    public $setResult;
    public $issetResult;
    public $unsetResult;
    function __get($name)                       { return "__get: " . $name; }
    function __set($name, $value)               { $this->setResult = "__set: " . $name . " = " . $value; }
    function __isset($name)                     { $this->issetResult = "__isset: " . $name; return TRUE; }
    function __unset($name)                     { $this->unsetResult = "__unset: " . $name; }

    public $offsetSetResult;
    public $offsetExistsResult;
    public $offsetUnsetResult;
    function offsetGet($offset)                 { return "offsetGet: " . $offset; }
    function offsetSet($offset, $value)         { $this->offsetSetResult = "offsetSet: " . $offset . " = " . $value; }
    function offsetExists($offset)              { $this->offsetExistsResult = "offsetExists: " . $offset; return TRUE; }
    function offsetUnset($offset)               { $this->offsetUnsetResult = "offsetUnset: " . $offset; }

    public $cloned;
    function __clone()                          { $this->cloned = TRUE; }

    public $debugInfo;
    function __debugInfo()                      { $this->debugInfo = TRUE; return []; }

    public $sleeping;
    function __sleep()                          { $this->sleeping = TRUE; return ['sleeping']; }

    function __toString()                       { return "stringified"; }

    function __invoke()                         { return "__invoke: (" . implode(", ", func_get_args()) . ")"; }

    function __call($name, $args)               { return "__call: " . $name . "(" . implode(", ", $args) . ")"; }

    static function __callStatic($name, $args)  { return "__callStatic: " . $name . "(" . implode(", ", $args) . ")"; }

    // __set_state and __wakeup are not required to be tested
}
