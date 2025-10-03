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
 * Key characteristics:
 * - Thread-safe singleton implementation
 * - Global state management for framework components
 * - Object lifecycle management and storage
 * - Service locator pattern for dependency resolution
 * - Memory-based storage with automatic cleanup
 * 
 * Usage patterns:
 * - Store framework configuration objects
 * - Cache expensive-to-create objects (database connections, etc.)
 * - Share state between disconnected components
 * - Implement service locator for dependency injection
 * 
 * Thread Safety:
 * The registry uses memory-based singleton storage, which is safe for
 * single-threaded PHP environments. For multi-threaded scenarios,
 * consider using alternative storage backends.
 * 
 * @package Neuron\Patterns
 * 
 * @example
 * ```php
 * $registry = Registry::instance();
 * 
 * // Store configuration
 * $registry->set('database.config', $dbConfig);
 * $registry->set('app.settings', $appSettings);
 * 
 * // Retrieve objects
 * $dbConfig = $registry->get('database.config');
 * $hasCache = $registry->has('cache.instance');
 * 
 * // Cleanup
 * $registry->remove('temp.data');
 * $registry->reset(); // Clear all objects
 * ```
 */
class Registry extends Singleton\Memory
{
	private array $_Objects = [];

	public function __construct()
	{}

	/**
	 * @param $Name
	 * @param $Object
	 */

	public function set( $Name, $Object ) : void
	{
		$this->_Objects[ $Name ] = $Object;
	}

	/**
	 * @param string $Name
	 * @return mixed
	 */

	public function get( string $Name ) : mixed
	{
		if( !array_key_exists( $Name, $this->_Objects ) )
		{
			return null;
		}

		return $this->_Objects[ $Name ];
	}

	/**
	 * @return void
	 */
	public function reset() : void
	{
		$this->_Objects = [];
	}
}
