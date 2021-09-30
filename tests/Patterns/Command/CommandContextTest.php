<?php

namespace Tests\Patterns\Command;

use Neuron\Patterns\Command\CommandContext;
use PHPUnit\Framework\TestCase;

class CommandContextTest extends TestCase
{
	public function testGetParam()
	{
		$context = new CommandContext();

		$context->setParam('action', 'login');

		$this->assertEquals(expected: 'login', actual: $context->getParam('action'));

		$context->setParam('action', null);

		$this->assertNull($context->getParam('action'));
	}

	public function testGetError()
	{
		$context = new CommandContext();

		$context->setError('Something went wrong.');

		$this->assertEquals(expected: 'Something went wrong.', actual: $context->getError());
	}
}
