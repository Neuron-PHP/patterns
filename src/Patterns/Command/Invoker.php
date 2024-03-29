<?php

namespace Neuron\Patterns\Command;

/**
 * Gets and invokes a specified command.
 */
class Invoker
{
	/**
	 * @param string $Action
	 * @param array|null $Params
	 * @return mixed
	 * @throws CommandNotFoundException
	 * @throws EmptyActionParameterException
	 */
	public function process(string $Action, ?array $Params = null): mixed
	{
		if(!$Action)
		{
			throw new EmptyActionParameterException(
				"Please pass 'Action:' parameter as first argument to Invoker::process() method"
			);
		}

		$Factory = new CommandFactory(CommandCache::getInstance());
		$Command = $Factory->getCommand($Action);

		return $Command->execute($Params);
	}
}
