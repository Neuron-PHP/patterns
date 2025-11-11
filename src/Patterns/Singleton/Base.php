<?php

namespace Neuron\Patterns\Singleton;

/**
 * Abstract base class for implementing the Singleton design pattern.
 * 
 * This class provides the foundational behavior for singleton objects in the
 * Neuron framework, ensuring only one instance of a class exists throughout
 * the application lifecycle. It handles instance creation, storage, and
 * serialization management.
 * 
 * Key features:
 * - Lazy initialization with automatic instance creation
 * - Abstract storage mechanism allowing different backends
 * - Serialization support for persistent singleton state
 * - Thread-safe instance retrieval in single-threaded environments
 * - Invalidation mechanism for controlled cleanup
 * 
 * Implementation requirements:
 * Concrete subclasses must implement:
 * - instance(): Define storage mechanism (memory, file, cache, etc.)
 * - serialize(): Handle object serialization for persistence
 * - invalidate(): Implement cleanup and invalidation logic
 * 
 * Thread safety considerations:
 * This implementation is safe for single-threaded PHP environments.
 * For multi-threaded scenarios (e.g., Swoole, ReactPHP), consider
 * implementing thread-safe storage mechanisms in subclasses.
 * 
 * @package Neuron\Patterns\Singleton
 * 
 * @example
 * ```php
 * class DatabaseConnection extends Base
 * {
 *     private static $instance = null;
 *     
 *     public static function instance(): mixed
 *     {
 *         return self::$instance;
 *     }
 *     
 *     public function serialize(): void
 *     {
 *         self::$instance = $this;
 *     }
 *     
 *     public function invalidate(): void
 *     {
 *         self::$instance = null;
 *     }
 * }
 * 
 * // Usage
 * $db = DatabaseConnection::getInstance();
 * ```
 */

abstract class Base implements ISingleton
{
	/**
	 * @return ISingleton|null
	 */

	public static function getInstance(): ?ISingleton
	{
		if( !static::instance() )
		{
			$class = get_called_class();

			$object = new $class;
			$object->serialize();
			return $object;
		}

		return static::instance();
	}

	public static abstract function instance() : mixed;
	public abstract function serialize() : void;
	public static abstract function invalidate() : void;
}
