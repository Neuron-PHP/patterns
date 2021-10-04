<?php

namespace Tests\Mock;

use Neuron\Patterns\Command\ICommand;

class MockCommand implements ICommand
{
	/**
	 * @param array|null $Params
	 * @return bool
	 */
	public function execute(?array $Params = null): bool
	{
		$Type = $Params['type'] ?? null;

		return $Type === 'mock';
	}
}
