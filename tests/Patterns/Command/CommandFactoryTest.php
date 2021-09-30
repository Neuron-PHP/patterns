<?php

namespace Tests\Patterns\Command;

use Neuron\Patterns\Command\CommandNotFoundException;
use Tests\Mock\MockCommand;
use Neuron\Patterns\Command\CommandContext;
use Neuron\Patterns\Command\CommandFactory;
use PHPUnit\Framework\TestCase;
use Neuron\Patterns\Command\CommandCache;

class CommandFactoryTest extends TestCase
{
	protected function setUp(): void
	{
		parent::setUp();

		CommandCache::getInstance()
			->set('mock', MockCommand::class);
	}

	public function testGetCommand()
	{
		$factory = new CommandFactory(cache: CommandCache::getInstance());
		$context = new CommandContext();

		$command = $factory->getCommand('mock');

		$this->assertTrue($command->execute($context->setParam('action', 'mock')));

		$this->expectException(CommandNotFoundException::class);
		CommandCache::getInstance()->get('mock2');
	}
}
