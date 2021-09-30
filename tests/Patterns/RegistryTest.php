<?php

namespace Tests\Patterns;

use Neuron\Patterns\Registry;
use PHPUnit\Framework\TestCase;

class RegistryTest extends TestCase
{
	protected function setUp(): void
	{
	}

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

	protected function tearDown(): void
	{
	}
}
