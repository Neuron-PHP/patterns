<?php

namespace Tests\Patterns\Command;

use Neuron\Core\Exceptions\CommandNotFound;
use Neuron\Patterns\Command\Cache;
use Neuron\Patterns\Command\Factory;
use PHPUnit\Framework\TestCase;
use Tests\Mock\MockCommand;

class CommandFactoryTest extends TestCase
{
	protected function setUp(): void
	{
		parent::setUp();

		Cache::getInstance()
			->set('mock', MockCommand::class);
	}

	public function testGetCommand()
	{
		$Factory = new Factory( Cache::getInstance());

		$Command = $Factory->get( 'mock');

		$this->assertTrue($Command->execute(['type' => 'mock']));

		$this->expectException( CommandNotFound::class);
		Cache::getInstance()->get( 'mock2');
	}
}
