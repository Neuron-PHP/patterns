<?php

namespace Tests\Patterns\Singleton;

use PHPUnit\Framework\TestCase;
use \Neuron\Patterns\Singleton\Memory;

class SingletonTest extends Memory
{
	public $Test;
}

class MemoryTest extends TestCase
{
	protected function setUp(): void
	{
	}

	public function testPass()
	{
		$Test = new SingletonTest();

		$Test->Test = 1;
		$Test->serialize();

		$Test2 = SingletonTest::instance();

		$this->assertEquals(
			$Test->Test,
			$Test2->Test
		);
	}

	protected function tearDown(): void
	{
	}
}
