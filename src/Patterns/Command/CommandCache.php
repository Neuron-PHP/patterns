<?php

namespace Neuron\Patterns\Command;

use Neuron\Patterns\Singleton\Base as Singleton;

class CommandCache extends Singleton
{
	/**
	 * @var CommandCache|null
	 */
	private static ?CommandCache $_Instance = null;

	/**
	 * @var string[]
	 */
	private static array $_Cache = [];

	/**
	 * @param string $Action
	 * @param string $Command
	 * @return CommandCache
	 */
	public function set(string $Action, string $Command): CommandCache
	{
		self::$_Cache[$Action] = $Command;

		return self::$_Instance;
	}

	/**
	 * @param string $Action
	 * @return ICommand
	 * @throws CommandNotFoundException
	 */
	public function get(string $Action): ICommand
	{
		if(!isset(self::$_Cache[$Action]))
		{
			throw new CommandNotFoundException("No command found for action '{$Action}' in the cache.");
		}

		return new self::$_Cache[$Action];
	}

	/**
	 * @return CommandCache|null
	 */
	static function instance(): ?CommandCache
	{
		return self::$_Instance;
	}

	/**
	 * @return void
	 */
	function serialize(): void
	{
		self::$_Instance = new self();
	}

	/**
	 * @return void
	 */
	static function invalidate(): void
	{
		self::$_Instance = null;
	}
}
