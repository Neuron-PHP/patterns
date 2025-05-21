<?php

namespace Neuron\Patterns\Command;

use Neuron\Core\Exceptions\CommandNotFound;
use Neuron\Core\Exceptions\EmptyActionParameter;

/**
 * Gets and invokes a specified command.
 */

class Invoker
{
	/**
	 * @param string $Action
	 * @param array|null $Params
	 * @return mixed
	 * @throws CommandNotFound
	 * @throws EmptyActionParameter
	 */

	public function process( string $Action, ?array $Params = null ): mixed
	{
		if( !$Action )
		{
			throw new EmptyActionParameter(
				"Please pass 'Action:' parameter as first argument to Invoker::process() method"
			);
		}

		$Factory = new Factory( Cache::getInstance() );
		$Command = $Factory->get( $Action);

		return $Command->execute($Params);
	}
}
