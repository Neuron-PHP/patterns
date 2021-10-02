<?php

namespace Neuron\Patterns\Command;

class Invoker
{
	/**
	 * @var CommandContext
	 */
	private CommandContext $Context;

	/**
	 * @param CommandContext|null $Context
	 */
	public function __construct(CommandContext $Context = null)
	{
		$this->Context = $Context ?: new CommandContext();
	}

	/**
	 * @return bool
	 * @throws NullActionParameterException
	 */
	public function process(): bool
	{
		$Action = $this->Context->getParam('action');

		if(!$Action)
		{
			throw new NullActionParameterException("No 'action' parameter found in the context.");
		}

		$Factory = new CommandFactory( CommandCache::getInstance());
		$Command = $Factory->getCommand($Action);

		return $Command->execute($this->Context);
	}
}
