<?php

namespace Neuron\Patterns\Command;

class CommandCache implements ICommandCache
{
	/**
	 * @var ICommandCache|null
	 */
	private static ?ICommandCache $instance = null;

	/**
	 * @var string[]
	 */
	private static array $cache = [];

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
		if (is_null(self::$instance))
		{
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * @param string $action
	 * @param string $command
	 * @return CommandCache
	 */
	public function set(string $action, string $command): CommandCache
	{
		self::$cache[$action] = $command;

		return self::$instance;
	}

	/**
	 * @param string $action
	 * @return ICommand
	 * @throws CommandNotFoundException
	 */
	public function get(string $action): ICommand
	{
		if(!isset(self::$cache[$action]))
		{
			throw new CommandNotFoundException("No command found for action '{$action}' in the cache.");
		}

		return new self::$cache[$action];
	}
}
