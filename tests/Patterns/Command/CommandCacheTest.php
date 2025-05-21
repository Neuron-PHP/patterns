<?php

namespace Tests\Patterns\Command;

use Neuron\Core\Exceptions\CommandNotFound;
use Neuron\Patterns\Command\Cache;
use PHPUnit\Framework\TestCase;
use Tests\Mock\MockCommand;

class CommandCacheTest extends TestCase
{
	protected function setUp(): void
	{
		parent::setUp();

		Cache::getInstance()
			->set('mock', MockCommand::class);
	}

	public function testGetInstance()
	{
		$Instance = Cache::getInstance();
		$Instance2 = Cache::getInstance();

		$this->assertSame($Instance, $Instance2);
		$this->assertTrue($Instance instanceof Cache);
	}

	public function testGet()
	{
		$this->assertTrue(
			condition: Cache::getInstance()->get( 'mock')->execute( [
				'type' => 'mock'
			])
		);

		$this->expectException( CommandNotFound::class);
		Cache::getInstance()->get( 'mock2');
	}
}
