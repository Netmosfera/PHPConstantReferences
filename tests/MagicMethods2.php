<?php declare(strict_types = 1); // atom

namespace Netmosfera\ConstantReferencesTests;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Error;
use Serializable;
use ArrayIterator;
use IteratorAggregate;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class MagicMethods2 implements IteratorAggregate, Serializable
{
    public $iteratorData = [
        11 => 22,
        33 => 44,
        55 => 66,
    ];

    function getIterator(){
        return new ArrayIterator($this->iteratorData);
    }

    function serialize(){
        return "serialize_function";
    }

    function unserialize($serialized){
        if($serialized !== "serialize_function"){
            throw new Error();
        }
    }
}
