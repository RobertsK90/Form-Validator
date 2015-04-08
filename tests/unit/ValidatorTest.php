<?php

use FormValidator\DatabaseWrapper\Database;
use FormValidator\ErrorHandler\ErrorHandler;
use FormValidator\Validator\Validator;



class ValidatorTest extends PHPUnit_Framework_TestCase
{

	protected $object;

	public function setUp() 
	{
		$this->object = new Validator(new Database, new ErrorHandler);
	}

	public function testMaxLengthSuccess()
	{
		$method = $this->getPrivateMethod('FormValidator\Validator\Validator', 'maxlength');
		$result = $method->invokeArgs($this->object, array('','test', 5));

		$this->assertTrue($result);

	}

	public function testMaxLengthError() {
		$method = $this->getPrivateMethod('FormValidator\Validator\Validator', 'maxlength');
		$result = $method->invokeArgs($this->object, array('', 'test', 3));

		$this->assertFalse($result);
	}

	public function testRequiredSuccess()
	{
		$method = $this->getPrivateMethod('FormValidator\Validator\Validator', 'required');
		$result = $method->invokeArgs($this->object, array('', 'test', ''));

		$this->assertTrue($result);
	}

	public function testRequiredError()
	{
		$method = $this->getPrivateMethod('FormValidator\Validator\Validator', 'required');
		$result = $method->invokeArgs($this->object, array('', '', ''));

		$this->assertFalse($result);
	}

	public function testMinLengthSuccess()
	{
		$method = $this->getPrivateMethod('FormValidator\Validator\Validator', 'minlength');
		$result = $method->invokeArgs($this->object, array('','test', 4));

		$this->assertTrue($result);

	}

	public function testMinLengthError() {
		$method = $this->getPrivateMethod('FormValidator\Validator\Validator', 'minlength');
		$result = $method->invokeArgs($this->object, array('', 'test', 5));

		$this->assertFalse($result);
	}

	public function testEmailSuccess()
	{
		$method = $this->getPrivateMethod('FormValidator\Validator\Validator', 'email');
		$result = (bool)$method->invokeArgs($this->object, array('','test@test.test', ''));

		$this->assertTrue($result);

	}

	public function testEmailError() {
		$method = $this->getPrivateMethod('FormValidator\Validator\Validator', 'email');
		$result = $method->invokeArgs($this->object, array('', 'thisisnotanemail', ''));

		$this->assertFalse($result);
	}

	public function testAlphanumSuccess()
	{
		$method = $this->getPrivateMethod('FormValidator\Validator\Validator', 'alphanum');
		$result = $method->invokeArgs($this->object, array('','thisisalphanum123', ''));

		$this->assertTrue($result);

	}

	public function testAlphanumError() {
		$method = $this->getPrivateMethod('FormValidator\Validator\Validator', 'alphanum');
		$result = $method->invokeArgs($this->object, array('', 'this_is*not@lphanum', ''));

		$this->assertFalse($result);
	}



    public function testConfirmedSuccess() {
        $method = $this->getPrivateMethod('FormValidator\Validator\Validator', 'confirmed');
        $this->object->fields['email_confirmation'] = "generic@email.com";
        $result = $method->invokeArgs($this->object, array('email', 'generic@email.com', ''));

        $this->assertTrue($result);
    }

    public function testUnique() {
        $dbmock = \Mockery::mock('FormValidator\DatabaseWrapper\Database');
        $method = $this->getPrivateMethod('FormValidator\Validator\Validator', 'unique');
        $table = 'users';
        $data = ['username' => 'myusername'];
        $dbmock->shouldReceive('setTable')->with($table)->andReturn(Mockery::self())->getMock()->shouldReceive('exists')->with($data)->andReturn(false);

        $this->object = new Validator($dbmock, new ErrorHandler);

        $this->assertTrue($method->invokeArgs($this->object, array('username', 'myusername', 'users')));
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