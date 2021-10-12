<?php

namespace Tests\Patterns\Command;

use Neuron\Patterns\Command\CommandCache;
use Neuron\Patterns\Command\CommandNotFoundException;
use Neuron\Patterns\Command\EmptyActionParameterException;
use Neuron\Patterns\Command\Invoker;
use PHPUnit\Framework\TestCase;
use Tests\Mock\MockCommand;

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

		$this->expectException(EmptyActionParameterException::class);
		$Invoker->process(Action: '');
	}

	public function testCommandNotFoundException()
	{
		$Invoker = new Invoker();

		$this->expectException(CommandNotFoundException::class);
		$Invoker->process(Action: 'mock2');
	}
}
