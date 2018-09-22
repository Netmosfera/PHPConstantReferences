<?php

namespace Netmosfera\ConstantReferences;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

/**
 * Defines a constant reference.
 *
 * Example:
 *
 * ```php
 * <?php
 *
 * use function Netmosfera\ConstantReferences\define;
 *
 * define("Netmosfera\\MyNamespace\\MY_CONSTANT", function(){ echo 123; });
 * ```
 *
 * @param           String                                  $name                           `String`
 * The constant's name.
 *
 * @param           Mixed                                   $value                          `Mixed`
 * The constant's value.
 *
 * @return          Mixed                                                                   `Mixed`
 * Void.
 */
function define(String $name, $value){}
