<?php
namespace Neuron\Patterns;

use Neuron\Patterns\Singleton;

/**
 * Global object registry implementing the Registry design pattern.
 * 
 * This singleton registry provides centralized storage and retrieval of objects
 * throughout the application lifecycle. It serves as a service locator and
 * dependency injection container, allowing components to share state and
 * communicate across the entire framework.
 *
 * @package Neuron\Patterns
 * 
 * @example
 * ```php
 * $registry = Registry::instance();
 *
 * // Store configuration (method syntax)
 * $registry->set('database.config', $dbConfig);
 * $registry->set('app.settings', $appSettings);
 *
 * // Store configuration (property syntax using magic methods)
 * $registry->databaseConfig = $dbConfig;
 * $registry->appSettings = $appSettings;
 *
 * // Retrieve objects (method syntax)
 * $dbConfig = $registry->get('database.config');
 *
 * // Retrieve objects (property syntax using magic methods)
 * $dbConfig = $registry->databaseConfig;
 *
 * // Check existence
 * $exists = isset($registry->databaseConfig);
 *
 * // Cleanup
 * $registry->reset(); // Clear all objects
 * ```
 */
class Registry extends Singleton\Memory
{
	private array $_objects = [];

	public function __construct()
	{}

	/**
	 * @param $name
	 * @param $object
	 */

	public function set( $name, $object ) : void
	{
		$this->_objects[ $name ] = $object;
	}

	/**
	 * @param string $name
	 * @return mixed
	 */

	public function get( string $name ) : mixed
	{
		if( !array_key_exists( $name, $this->_objects ) )
		{
			return null;
		}

		return $this->_objects[ $name ];
	}

	/**
	 * @return void
	 */
	public function reset() : void
	{
		$this->_objects = [];
	}

	/**
	 * Magic method to get a registry value using property syntax.
	 *
	 * @param string $name The name of the registry key
	 * @return mixed The value stored in the registry, or null if not found
	 */
	public function __get( string $name ) : mixed
	{
		return $this->get( $name );
	}

	/**
	 * Magic method to set a registry value using property syntax.
	 *
	 * @param string $name The name of the registry key
	 * @param mixed $value The value to store in the registry
	 * @return void
	 */
	public function __set( string $name, mixed $value ) : void
	{
		$this->set( $name, $value );
	}

	/**
	 * Magic method to check if a registry key exists using isset().
	 *
	 * @param string $name The name of the registry key
	 * @return bool True if the key exists in the registry, false otherwise
	 */
	public function __isset( string $name ) : bool
	{
		return array_key_exists( $name, $this->_objects );
	}
}
