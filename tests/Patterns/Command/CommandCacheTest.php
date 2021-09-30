<?php

namespace Tests\Patterns\Command;

use Neuron\Patterns\Command\CommandCache;
use Neuron\Patterns\Command\CommandContext;
use Neuron\Patterns\Command\CommandNotFoundException;
use Tests\Mock\MockCommand;
use PHPUnit\Framework\TestCase;

class CommandCacheTest extends TestCase
{
	protected function setUp(): void
	{
		parent::setUp();

		CommandCache::getInstance()
			->set('mock', MockCommand::class);
	}

	public function testGetInstance()
	{
		$instance = CommandCache::getInstance();
		$instance2 = CommandCache::getInstance();

		$this->assertSame($instance, $instance2);
		$this->assertTrue($instance instanceof CommandCache);
	}

	public function testGet()
	{
		$context = new CommandContext();

		$this->assertTrue(
			condition: CommandCache::getInstance()->get('mock')->execute(
				context: $context->setParam('action', 'mock')
			)
		);

		$this->expectException(CommandNotFoundException::class);
		CommandCache::getInstance()->get('mock2');
	}
}
