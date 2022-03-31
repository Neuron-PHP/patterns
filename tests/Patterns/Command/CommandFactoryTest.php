<?php

namespace Tests\Patterns\Command;

use Neuron\Patterns\Command\CommandNotFoundException;
use Tests\Mock\MockCommand;
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
		$Factory = new CommandFactory(CommandCache::getInstance());

		$Command = $Factory->getCommand('mock');

		$this->assertTrue($Command->execute(['type' => 'mock']));

		$this->expectException(CommandNotFoundException::class);
		CommandCache::getInstance()->get('mock2');
	}
}
