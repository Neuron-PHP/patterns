<?php

namespace Neuron\Patterns\Command;

use Neuron\Core\Exceptions\CommandNotFound;
use Neuron\Core\Exceptions\EmptyActionParameter;

/**
 * Command pattern invoker that processes and executes commands by name.
 * 
 * The Invoker class serves as the command pattern's invoker, responsible for
 * retrieving command objects by name and executing them with provided parameters.
 * It provides a centralized entry point for command execution while maintaining
 * loose coupling between command requestors and command implementations.
 * 
 * Key responsibilities:
 * - Command retrieval via Factory pattern integration
 * - Parameter validation and error handling
 * - Command execution coordination
 * - Exception handling for missing or invalid commands
 * - Integration with caching for performance optimization
 * 
 * The invoker uses the Factory pattern to dynamically create command instances
 * based on string identifiers, allowing for flexible command registration and
 * execution without tight coupling to specific command implementations.
 * 
 * @package Neuron\Patterns\Command
 * 
 * @see Factory Command factory for dynamic command creation
 * @see Cache Command caching for performance optimization
 * @see ICommand Command interface
 * 
 * @example
 * ```php
 * // Execute a command through the invoker
 * $invoker = new Invoker();
 * 
 * // Process email command
 * $result = $invoker->process('email', [
 *     'to' => 'user@example.com',
 *     'subject' => 'Welcome',
 *     'body' => 'Welcome to our application!'
 * ]);
 * 
 * // Process database cleanup command
 * $invoker->process('cleanup-temp-data', ['days' => 7]);
 * 
 * // Process file processing command
 * $invoker->process('process-upload', [
 *     'filename' => 'document.pdf',
 *     'user_id' => 123
 * ]);
 * ```
 */

class Invoker
{
	/**
	 * @param string $action
	 * @param array|null $params
	 * @return mixed
	 * @throws CommandNotFound
	 * @throws EmptyActionParameter
	 */

	public function process( string $action, ?array $params = null ): mixed
	{
		if( !$action )
		{
			throw new EmptyActionParameter(
				"Please pass 'Action:' parameter as first argument to Invoker::process() method"
			);
		}

		$factory = new Factory( Cache::getInstance() );
		$command = $factory->get( $action);

		return $command->execute($params);
	}
}
