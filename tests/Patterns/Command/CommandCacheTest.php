<?php

namespace Tests\Patterns\Command;

use Neuron\Patterns\Command\CommandCache;
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
		$Instance = CommandCache::getInstance();
		$Instance2 = CommandCache::getInstance();

		$this->assertSame($Instance, $Instance2);
		$this->assertTrue($Instance instanceof CommandCache);
	}

	public function testGet()
	{
		$this->assertTrue(
			condition: CommandCache::getInstance()->get('mock')->execute([
				'type' => 'mock'
			])
		);

		$this->expectException(CommandNotFoundException::class);
		CommandCache::getInstance()->get('mock2');
	}
}
