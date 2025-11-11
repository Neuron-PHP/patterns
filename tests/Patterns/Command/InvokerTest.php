<?php

namespace Tests\Patterns\Command;

use Neuron\Core\Exceptions\CommandNotFound;
use Neuron\Core\Exceptions\EmptyActionParameter;
use Neuron\Patterns\Command\Invoker;
use PHPUnit\Framework\TestCase;

class InvokerTest extends TestCase
{
	public function testProcessSuccess()
	{
		$invoker = new Invoker();

		$this->assertTrue(
			$invoker->process(
				action: 'mock',
				params: ['type' => 'mock']
			)
		);
	}

	public function testNullActionParameterException()
	{
		$invoker = new Invoker();

		$this->expectException( EmptyActionParameter::class);
		$invoker->process(action: '');
	}

	public function testCommandNotFoundException()
	{
		$invoker = new Invoker();

		$this->expectException( CommandNotFound::class);
		$invoker->process(action: 'mock2');
	}
}
