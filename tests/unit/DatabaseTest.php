<?php

use FormValidator\DatabaseWrapper\Database;
use FormValidator\ErrorHandler\ErrorHandler;
use FormValidator\Validator\Validator;



class DatabaseTest extends PHPUnit_Framework_TestCase
{
    private $object;

    public function setUp()
    {
        $this->object = new Database;
    }

    public function testSetTable() {
        $this->object->setTable('mytable');

        $this->assertEquals($this->getPrivateProperty('FormValidator\DatabaseWrapper\Database', 'table')->getValue($this->object), 'mytable');
    }







    public function getPrivateMethod($className, $methodName)
    {
        $reflector = new ReflectionClass($className);
        $method = $reflector->getMethod($methodName);
        $method->setAccessible(true);

        return $method;
    }

    public function getPrivateProperty($className, $propertyName)
    {
        $reflector = new ReflectionClass($className);
        $property = $reflector->getProperty($propertyName);
        $property->setAccessible(true);

        return $property;
    }



}