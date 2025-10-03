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
}
