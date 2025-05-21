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
		$Invoker = new Invoker();

		$this->assertTrue(
			$Invoker->process(
				Action: 'mock',
				Params: ['type' => 'mock']
			)
		);
	}

	public function testNullActionParameterException()
	{
		$Invoker = new Invoker();

		$this->expectException( EmptyActionParameter::class);
		$Invoker->process(Action: '');
	}

	public function testCommandNotFoundException()
	{
		$Invoker = new Invoker();

		$this->expectException( CommandNotFound::class);
		$Invoker->process(Action: 'mock2');
	}
}
