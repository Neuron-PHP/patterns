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
		$context = new CommandContext();
		$context->setParam('action', 'mock');

		$invoker = new Invoker($context);

		$this->assertTrue($invoker->process());
	}

	public function testNullActionParameterException()
	{
		$context = new CommandContext();
		$context->setParam('action', null);

		$invoker = new Invoker($context);

		$this->expectException(NullActionParameterException::class);
		$invoker->process();
	}

	public function testCommandNotFoundException()
	{
		$context = new CommandContext();
		$context->setParam('action', 'mock2');

		$invoker = new Invoker($context);

		$this->expectException(CommandNotFoundException::class);
		$invoker->process();
	}
}
