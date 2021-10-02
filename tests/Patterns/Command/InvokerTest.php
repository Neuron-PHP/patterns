<?php

namespace Tests\Patterns\Command;

use Neuron\Patterns\Command\CommandContext;
use Neuron\Patterns\Command\CommandNotFoundException;
use Neuron\Patterns\Command\Invoker;
use Neuron\Patterns\Command\NullActionParameterException;
use PHPUnit\Framework\TestCase;

class InvokerTest extends TestCase
{
	public function testProcessSuccess()
	{
		$Context = new CommandContext();
		$Context->setParam('action', 'mock');

		$Invoker = new Invoker($Context);

		$this->assertTrue($Invoker->process());
	}

	public function testNullActionParameterException()
	{
		$Context = new CommandContext();
		$Context->setParam('action', null);

		$Invoker = new Invoker($Context);

		$this->expectException(NullActionParameterException::class);
		$Invoker->process();
	}

	public function testCommandNotFoundException()
	{
		$Context = new CommandContext();
		$Context->setParam('action', 'mock2');

		$Invoker = new Invoker($Context);

		$this->expectException(CommandNotFoundException::class);
		$Invoker->process();
	}
}
