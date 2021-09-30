<?php

namespace Neuron\Patterns\Command;

interface ICommandCache
{
	/**
	 * @return ICommandCache
	 */
	public static function getInstance(): ICommandCache;

	/**
	 * @param string $action
	 * @param string $command
	 */
	public function set(string $action, string $command): ICommandCache;

	/**
	 * @param string $action
	 * @return ICommand
	 */
	public function get(string $action): ICommand;
}
