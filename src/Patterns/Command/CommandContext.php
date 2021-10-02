<?php

namespace Neuron\Patterns\Command;

class CommandContext
{
	/**
	 * @var string[]
	 */
	private array $_Params = [];

	/**
	 * @var string
	 */
	private string $_Error = '';

	/**
	 * @param string $Key
	 * @param string|null $Value
	 * @return $this
	 */
	public function setParam(string $Key, ?string $Value): CommandContext
	{
		$this->_Params[$Key] = $Value;
		return $this;
	}

	/**
	 * @param string $Key
	 * @return string|null
	 */
	public function getParam(string $Key): ?string
	{
		return $this->_Params[$Key] ?? null;
	}

	/**
	 * @return string
	 */
	public function getError(): string
	{
		return $this->_Error;
	}

	/**
	 * @param string $Error
	 * @return CommandContext
	 */
	public function setError(string $Error): CommandContext
	{
		$this->_Error = $Error;
		return $this;
	}
}
