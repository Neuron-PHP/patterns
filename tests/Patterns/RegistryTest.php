<?php

namespace Tests\Patterns;

use Neuron\Patterns\Registry;

class RegistryTest extends \PHPUnit\Framework\TestCase
{
	public function testPass()
	{
		$Reg1 = Registry::getInstance();

		$Reg1->set( 'test', '1234' );

		$Reg2 = Registry::getInstance();

		$this->assertEquals( $Reg2->get( 'test' ), '1234' );
	}

	public function testFail()
	{
		$Reg1 = Registry::getInstance();

		$Reg1->set( 'test', '1234' );

		$Reg2 = Registry::getInstance();

		$this->assertNotEquals( $Reg2->get( 'test' ), '1111' );
	}

	public function testReset()
	{
		$Reg1 = Registry::getInstance();

		$Reg1->set( 'test', '1234' );

		$Reg2 = Registry::getInstance();

		$this->assertEquals( $Reg2->get( 'test' ), '1234' );

		$Reg1->reset();

		$this->assertNull( $Reg2->get( 'test' ) );
	}

	public function testMagicSet()
	{
		$Reg1 = Registry::getInstance();
		$Reg1->reset();

		// Test magic __set
		$Reg1->testKey = 'magic value';

		// Verify using regular get method
		$this->assertEquals( 'magic value', $Reg1->get( 'testKey' ) );

		// Verify using getInstance
		$Reg2 = Registry::getInstance();
		$this->assertEquals( 'magic value', $Reg2->get( 'testKey' ) );
	}

	public function testMagicGet()
	{
		$Reg1 = Registry::getInstance();
		$Reg1->reset();

		// Set using regular method
		$Reg1->set( 'testKey', 'test value' );

		// Test magic __get
		$this->assertEquals( 'test value', $Reg1->testKey );

		// Verify null is returned for non-existent keys
		$this->assertNull( $Reg1->nonExistentKey );
	}

	public function testMagicIsset()
	{
		$Reg1 = Registry::getInstance();
		$Reg1->reset();

		// Test isset on non-existent key
		$this->assertFalse( isset( $Reg1->testKey ) );

		// Set a value
		$Reg1->testKey = 'some value';

		// Test isset on existing key
		$this->assertTrue( isset( $Reg1->testKey ) );

		// Reset and verify isset returns false
		$Reg1->reset();
		$this->assertFalse( isset( $Reg1->testKey ) );
	}

	public function testMagicMethodsIntegration()
	{
		$Reg1 = Registry::getInstance();
		$Reg1->reset();

		// Mix magic methods and regular methods
		$Reg1->magicKey = 'magic';
		$Reg1->set( 'regularKey', 'regular' );

		// Verify both can be retrieved with either syntax
		$this->assertEquals( 'magic', $Reg1->magicKey );
		$this->assertEquals( 'magic', $Reg1->get( 'magicKey' ) );
		$this->assertEquals( 'regular', $Reg1->regularKey );
		$this->assertEquals( 'regular', $Reg1->get( 'regularKey' ) );

		// Verify isset works for both
		$this->assertTrue( isset( $Reg1->magicKey ) );
		$this->assertTrue( isset( $Reg1->regularKey ) );
	}
}
