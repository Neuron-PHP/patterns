<?php

namespace Neuron\Patterns\Command;

interface ICommandCache
{
	/**
	 * @return ICommandCache
	 */
	public static function getInstance(): ICommandCache;

	/**
	 * @param string $Action
	 * @param string $Command
	 */
	public function set(string $Action, string $Command): ICommandCache;

	/**
	 * @param string $Action
	 * @return ICommand
	 */
	public function get(string $Action): ICommand;
}
