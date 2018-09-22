<?php declare(strict_types = 1); // atom

namespace Netmosfera\ConstantReferencesTests;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use IteratorAggregate;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class MagicMethods3 implements IteratorAggregate
{
    public $iteratorData = [
        11 => 22,
        33 => 44,
        55 => 66,
    ];

    function getIterator(){
        yield from $this->iteratorData;
    }
}
