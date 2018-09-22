<?php declare(strict_types = 1); // atom

namespace Netmosfera\ConstantReferencesTests;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Error\Notice;
use function Netmosfera\ConstantReferences\define;
use function unserialize;
use function serialize;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class ExtensionTest extends TestCase
{
    function test_is_case_sensitive(){
        define("CASE_SENSITIVE", 123);
        self::assertTrue(defined("CASE_SENSITIVE"));
        self::assertFalse(defined("case_sensitive"));
    }

    function test_can_only_assign_once(){
        $this->expectException(Notice::CLASS);
        define("ASSIGN_ONCE", 123);
        define("ASSIGN_ONCE", 555);
    }

    function test_can_only_assign_once_2(){
        $this->expectException(Notice::CLASS);
        define("ASSIGN_ONCE", 123);
        \define("ASSIGN_ONCE", 555);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function test_closure_same(){
        $closure = function(){};
        define("MY_FUNC_0", $closure);
        if(FALSE){ \define("MY_FUNC_0", $closure); }
        self::assertSame($closure, MY_FUNC_0);
    }

    function test_closure_call(){
        $closure = function(...$arguments){ return $arguments; };
        define("MY_FUNC_1", $closure);
        if(FALSE){ \define("MY_FUNC_1", $closure); }
        $arguments = [1, 2, 3, "foo", MY_FUNC_1];
        self::assertSame($arguments, (MY_FUNC_1)(...$arguments));
    }

    function test_closure_this(){
        $closure = function(){ return $this; };
        define("MY_FUNC_2", $closure);
        if(FALSE){ \define("MY_FUNC_2", $closure); }
        self::assertSame($this, (MY_FUNC_2)());
    }

    function test_closure_rebind(){
        $object = new class(){};
        $closure = function(){ return $this; };
        define("MY_FUNC_3", $closure);
        if(FALSE){ \define("MY_FUNC_3", $closure); }
        self::assertSame($object, (MY_FUNC_3)->call($object));
        self::assertSame($object, (MY_FUNC_3)->bindTo($object)());
        self::assertSame($object, (MY_FUNC_3)->bindTo($object)->__invoke());
    }

    function test_closure_imports(){
        $a = 100; $b = 20; $c = 3;
        $closure = function() use(&$a, &$b, &$c){ return $a + $b + $c; };
        define("MY_FUNC_4", $closure);
        if(FALSE){ \define("MY_FUNC_4", $closure); }
        self::assertSame(123, (MY_FUNC_4)());
        $a = 200; $b = 30; $c = 4;
        self::assertSame(234, (MY_FUNC_4)());
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function test_object_same(){
        $object = new class(){};
        define("MY_CONSTANT_0", $object);
        if(FALSE){ \define("MY_CONSTANT_0", $object); }
        self::assertSame($object, MY_CONSTANT_0);
    }

    function test_object_method(){
        $object = new class(){ function test(){ return 123; } };
        define("MY_CONSTANT_1", $object);
        if(FALSE){ \define("MY_CONSTANT_1", $object); }
        self::assertSame(123, (MY_CONSTANT_1)->test());
    }

    function test_object_method_returning_self(){
        $object = new class(){ function fooBar(){ return $this; } };
        define("MY_CONSTANT_2", $object);
        if(FALSE){ \define("MY_CONSTANT_2", $object); }
        self::assertSame(MY_CONSTANT_2, (MY_CONSTANT_2)->fooBar());
        self::assertSame(MY_CONSTANT_2, (MY_CONSTANT_2)->fooBar()->fooBar());
        self::assertSame(MY_CONSTANT_2, (MY_CONSTANT_2)->fooBar()->fooBar()->fooBar());
        self::assertSame($object, (MY_CONSTANT_2)->fooBar());
        self::assertSame($object, (MY_CONSTANT_2)->fooBar()->fooBar());
        self::assertSame($object, (MY_CONSTANT_2)->fooBar()->fooBar()->fooBar());
    }

    function test_object_field(){
        $object = new class(){ public $foo = 123; };
        define("MY_CONSTANT_3", $object);
        if(FALSE){ \define("MY_CONSTANT_3", $object); }
        self::assertSame(123, (MY_CONSTANT_3)->foo);
    }

    function test_object_field_returning_self(){
        $object = new class(){ public $foo; };
        $object->foo = $object;
        define("MY_CONSTANT_4", $object);
        if(FALSE){ \define("MY_CONSTANT_4", $object); }
        self::assertSame(MY_CONSTANT_4, (MY_CONSTANT_4)->foo);
        self::assertSame(MY_CONSTANT_4, (MY_CONSTANT_4)->foo->foo);
        self::assertSame(MY_CONSTANT_4, (MY_CONSTANT_4)->foo->foo->foo);
        self::assertSame($object, (MY_CONSTANT_4)->foo);
        self::assertSame($object, (MY_CONSTANT_4)->foo->foo);
        self::assertSame($object, (MY_CONSTANT_4)->foo->foo->foo);
    }

    function test_object_magic_methods(){
        $object = new MagicMethods();
        define("MY_CONSTANT_5", $object);
        if(FALSE){ \define("MY_CONSTANT_5", $object); }

        //----------------------------------------------------------------------------------

        self::assertSame("__get: get_property", (MY_CONSTANT_5)->get_property);

        // @TBD Cannot use temporary expression in write context
        // (MY_CONSTANT_5)->set_property = "set_property_value";
        (MY_CONSTANT_5)->__set("set_property", "set_property_value");
        self::assertSame("__set: set_property = set_property_value", (MY_CONSTANT_5)->setResult);

        isset((MY_CONSTANT_5)->isset_property);
        self::assertSame("__isset: isset_property", (MY_CONSTANT_5)->issetResult);

        // @TBD Cannot use temporary expression in write context
        // unset((MY_CONSTANT_5)->unset_property);
        (MY_CONSTANT_5)->__unset("unset_property");
        self::assertSame("__unset: unset_property", (MY_CONSTANT_5)->unsetResult);

        //----------------------------------------------------------------------------------

        self::assertSame("offsetGet: 55", (MY_CONSTANT_5)[55]);
        self::assertSame("offsetGet: get_key", (MY_CONSTANT_5)["get_key"]);

        // @TBD Cannot use temporary expression in write context
        // (MY_CONSTANT_5)[66] = "set_dimension";
        (MY_CONSTANT_5)->offsetSet(66, "set_array_value");
        self::assertSame("offsetSet: 66 = set_array_value", (MY_CONSTANT_5)->offsetSetResult);
        // (MY_CONSTANT_5)["key"] = "set_key";
        (MY_CONSTANT_5)->offsetSet("set_key", "set_key_value");
        self::assertSame("offsetSet: set_key = set_key_value", (MY_CONSTANT_5)->offsetSetResult);

        isset((MY_CONSTANT_5)[77]);
        self::assertSame("offsetExists: 77", (MY_CONSTANT_5)->offsetExistsResult);
        isset((MY_CONSTANT_5)["isset_key"]);
        self::assertSame("offsetExists: isset_key", (MY_CONSTANT_5)->offsetExistsResult);

        // @TBD Cannot use temporary expression in write context
        // unset((MY_CONSTANT_5)[88]);
        (MY_CONSTANT_5)->offsetUnset(88);
        self::assertSame("offsetUnset: 88", (MY_CONSTANT_5)->offsetUnsetResult);
        // unset((MY_CONSTANT_5)["unset_key"]);
        (MY_CONSTANT_5)->offsetUnset("unset_key");
        self::assertSame("offsetUnset: unset_key", (MY_CONSTANT_5)->offsetUnsetResult);

        //----------------------------------------------------------------------------------

        $clone = clone MY_CONSTANT_5;
        self::assertSame(TRUE, $clone->cloned);
        self::assertNotSame(MY_CONSTANT_5, $clone);
        self::assertNotSame($object, $clone);

        //----------------------------------------------------------------------------------

        $debug = print_r(MY_CONSTANT_5, TRUE);
        self::assertNotNull($debug);
        self::assertNotSame("", $debug);
        self::assertSame(TRUE, (MY_CONSTANT_5)->debugInfo);

        //----------------------------------------------------------------------------------

        $serialized = serialize(MY_CONSTANT_5);
        self::assertNotNull($serialized);
        self::assertNotSame("", $serialized);
        self::assertSame(TRUE, (MY_CONSTANT_5)->sleeping);

        //----------------------------------------------------------------------------------

        self::assertSame("stringified", (String)(MY_CONSTANT_5));
        self::assertSame("stringified", (String)MY_CONSTANT_5);

        //----------------------------------------------------------------------------------

        $invoke = (MY_CONSTANT_5)(1, 2, 3, "foo", MY_CONSTANT_5);
        self::assertSame("__invoke: (1, 2, 3, foo, stringified)", $invoke);

        //----------------------------------------------------------------------------------

        $call = (MY_CONSTANT_5)->CALL_METHOD(1, 2, 3, "foo", MY_CONSTANT_5);
        self::assertSame("__call: CALL_METHOD(1, 2, 3, foo, stringified)", $call);

        //----------------------------------------------------------------------------------

        $callStatic = (MY_CONSTANT_5)::CALL_STATIC_METHOD(1, 2, 3, "foo", MY_CONSTANT_5);
        self::assertSame("__callStatic: CALL_STATIC_METHOD(1, 2, 3, foo, stringified)", $callStatic);

        //----------------------------------------------------------------------------------

        $reference = new MagicMethods();
        foreach($reference as $key => $current){}
        foreach(MY_CONSTANT_5 as $key => $current){}
        self::assertSame($reference->iteration, (MY_CONSTANT_5)->iteration);

    }

    function test_object_magic_methods2(){
        $object = new MagicMethods2();
        define("MY_CONSTANT_6", $object);
        if(FALSE){ \define("MY_CONSTANT_6", $object); }

        //----------------------------------------------------------------------------------

        $reconstitute = [];
        foreach(MY_CONSTANT_6 as $key => $current){ $reconstitute[$key] = $current; }
        self::assertSame((MY_CONSTANT_6)->iteratorData, $reconstitute);

        //----------------------------------------------------------------------------------

        $serialized = serialize(MY_CONSTANT_6);
        self::assertNotNull($serialized);
        self::assertNotSame("", $serialized);
        $objectCopy = unserialize($serialized);
        self::assertEquals(MY_CONSTANT_6, $objectCopy);
    }

    function test_object_magic_methods3(){
        $object = new MagicMethods3();
        define("MY_CONSTANT_7", $object);
        if(FALSE){ \define("MY_CONSTANT_7", $object); }

        //----------------------------------------------------------------------------------

        $reconstitute = [];
        foreach(MY_CONSTANT_6 as $key => $current){ $reconstitute[$key] = $current; }
        self::assertSame((MY_CONSTANT_7)->iteratorData, $reconstitute);
    }
}
