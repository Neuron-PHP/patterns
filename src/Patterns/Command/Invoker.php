<?php

namespace Neuron\Patterns\Command;

class Invoker
{
	/**
	 * @var CommandContext
	 */
	private CommandContext $context;

	/**
	 * @param CommandContext|null $context
	 */
	public function __construct(CommandContext $context = null)
	{
		$this->context = $context ?: new CommandContext();
	}

	/**
	 * @return bool
	 * @throws NullActionParameterException
	 */
	public function process(): bool
	{
		$action = $this->context->getParam('action');

		if(!$action)
		{
			throw new NullActionParameterException("No 'action' parameter found in the context.");
		}

		$factory = new CommandFactory( CommandCache::getInstance());
		$command = $factory->getCommand($action);

		return $command->execute($this->context);
	}
}
