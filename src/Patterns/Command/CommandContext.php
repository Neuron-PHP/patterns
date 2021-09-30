<?php

namespace Neuron\Patterns\Command;

class CommandContext
{
	/**
	 * @var string[]
	 */
	private array $params = [];

	/**
	 * @var string
	 */
	private string $error = '';

	/**
	 * @param string $key
	 * @param string|null $value
	 * @return $this
	 */
	public function setParam(string $key, ?string $value): CommandContext
	{
		$this->params[$key] = $value;
		return $this;
	}

	/**
	 * @param string $key
	 * @return string|null
	 */
	public function getParam(string $key): ?string
	{
		return $this->params[$key] ?? null;
	}

	/**
	 * @return string
	 */
	public function getError(): string
	{
		return $this->error;
	}

	/**
	 * @param string $error
	 * @return CommandContext
	 */
	public function setError(string $error): CommandContext
	{
		$this->error = $error;
		return $this;
	}
}
