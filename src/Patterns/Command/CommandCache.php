<?php

namespace Neuron\Patterns\Command;

class CommandCache implements ICommandCache
{
	/**
	 * @var ICommandCache|null
	 */
	private static ?ICommandCache $_Instance = null;

	/**
	 * @var string[]
	 */
	private static array $_Cache = [];

	/**
	 * Making the constructor private to not allow to get the class instance using the "new" keyword
	 */
	private function __construct()
	{
	}

	/**
	 * @return ICommandCache
	 */
	public static function getInstance(): ICommandCache
	{
		if (is_null(self::$_Instance))
		{
			self::$_Instance = new self();
		}

		return self::$_Instance;
	}

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
}
