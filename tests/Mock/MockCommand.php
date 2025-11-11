<?php

namespace Tests\Mock;

use Neuron\Patterns\Command\ICommand;

class MockCommand implements ICommand
{
	/**
	 * @param array|null $params
	 * @return bool
	 */
	public function execute(?array $params = null): bool
	{
		$type = $params['type'] ?? null;

		return $type === 'mock';
	}
}
