<?php

namespace Neuron\Patterns\Command;

/**
 * Gets or creates a command object.
 */
class CommandFactory
{
	/**
	 * @var CommandCache
	 */
	private CommandCache $_Cache;

	/**
	 * @param CommandCache $Cache
	 */
	public function __construct(CommandCache $Cache)
	{
		$this->_Cache = $Cache;
	}

	/**
	 * @param string $Action
	 * @return ICommand
	 * @throws CommandNotFoundException
	 */
	public function getCommand(string $Action): ICommand
	{
		return $this->_Cache->get($Action);
	}
}
