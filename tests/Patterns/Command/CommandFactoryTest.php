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
		$Factory = new CommandFactory(cache: CommandCache::getInstance());
		$Context = new CommandContext();

		$Command = $Factory->getCommand('mock');

		$this->assertTrue($Command->execute($Context->setParam('action', 'mock')));

		$this->expectException(CommandNotFoundException::class);
		CommandCache::getInstance()->get('mock2');
	}
}
