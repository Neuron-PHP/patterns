<?php

namespace Neuron\Patterns\Container;

/**
 * PSR-11 compatible dependency injection container interface
 *
 * Provides dependency injection with auto-wiring capabilities.
 * Compatible with PSR-11 Container Interface standard.
 *
 * @package Neuron\Patterns\Container
 */
interface IContainer
{
	/**
	 * Finds an entry of the container by its identifier and returns it.
	 *
	 * @param string $id Identifier of the entry to look for (typically a class name or interface)
	 * @return mixed Entry.
	 * @throws NotFoundException  No entry was found for **this** identifier.
	 * @throws ContainerException Error while retrieving the entry.
	 */
	public function get( string $id );

	/**
	 * Returns true if the container can return an entry for the given identifier.
	 * Returns false otherwise.
	 *
	 * @param string $id Identifier of the entry to look for.
	 * @return bool
	 */
	public function has( string $id ): bool;

	/**
	 * Bind an abstract type to a concrete implementation
	 *
	 * @param string $abstract Interface or abstract class name
	 * @param string $concrete Concrete class name
	 * @return void
	 */
	public function bind( string $abstract, string $concrete ): void;

	/**
	 * Register a singleton (shared instance) in the container
	 *
	 * @param string $abstract Interface or class name
	 * @param callable $factory Factory function that creates the instance
	 * @return void
	 */
	public function singleton( string $abstract, callable $factory ): void;

	/**
	 * Resolve and instantiate a class with automatic dependency injection
	 *
	 * Uses reflection to analyze constructor parameters and automatically
	 * resolves dependencies from the container.
	 *
	 * @param string $class Fully qualified class name
	 * @param array $parameters Optional parameters to override auto-wiring
	 * @return object Instance of the requested class
	 * @throws ContainerException If the class cannot be instantiated
	 */
	public function make( string $class, array $parameters = [] );

	/**
	 * Register an existing instance as a singleton
	 *
	 * @param string $abstract Interface or class name
	 * @param object $instance The instance to register
	 * @return void
	 */
	public function instance( string $abstract, object $instance ): void;
}
