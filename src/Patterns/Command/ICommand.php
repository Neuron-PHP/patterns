<?php

namespace Neuron\Patterns\Command;

/**
 * Command pattern.
 */
interface ICommand
{
	/**
	 * @param array|null $Params
	 * @return mixed
	 */
	public function execute(?array $Params = null): mixed;
}
