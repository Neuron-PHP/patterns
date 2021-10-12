<?php

namespace Neuron\Patterns\Command;

use Neuron\Patterns\Singleton\Memory as Singleton;

class CommandCache extends Singleton
{
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

		return self::instance();
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
}
