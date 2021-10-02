<?php

namespace Tests\Patterns\Command;

use Neuron\Patterns\Command\CommandContext;
use PHPUnit\Framework\TestCase;

class CommandContextTest extends TestCase
{
	public function testGetParam()
	{
		$Context = new CommandContext();

		$Context->setParam('action', 'login');

		$this->assertEquals(expected: 'login', actual: $Context->getParam('action'));

		$Context->setParam('action', null);

		$this->assertNull($Context->getParam('action'));
	}

	public function testGetError()
	{
		$context = new CommandContext();

		$context->setError('Something went wrong.');

		$this->assertEquals(expected: 'Something went wrong.', actual: $context->getError());
	}
}
