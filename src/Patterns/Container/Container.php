<?php

namespace Neuron\Patterns\Container;

use ReflectionClass;
use ReflectionException;
use ReflectionParameter;

/**
 * Dependency injection container with auto-wiring
 *
 * Implements PSR-11 compatible container interface with automatic
 * dependency resolution using reflection.
 *
 * @package Neuron\Patterns\Container
 */
class Container implements IContainer
{
	/**
	 * Registered type bindings (interface => concrete class)
	 */
	private array $_bindings = [];

	/**
	 * Registered singleton factories
	 */
	private array $_singletons = [];

	/**
	 * Resolved singleton instances
	 */
	private array $_instances = [];

	/**
	 * {@inheritdoc}
	 */
	public function get( string $id )
	{
		if( !$this->has( $id ) )
		{
			throw new NotFoundException( "Entry '{$id}' not found in container" );
		}

		// Return existing singleton instance
		if( isset( $this->_instances[$id] ) )
		{
			return $this->_instances[$id];
		}

		// Create and cache singleton instance
		if( isset( $this->_singletons[$id] ) )
		{
			$instance = ($this->_singletons[$id])( $this );
			$this->_instances[$id] = $instance;
			return $instance;
		}

		// Resolve binding
		if( isset( $this->_bindings[$id] ) )
		{
			return $this->make( $this->_bindings[$id] );
		}

		// Auto-wire the class
		return $this->make( $id );
	}

	/**
	 * {@inheritdoc}
	 */
	public function has( string $id ): bool
	{
		return isset( $this->_singletons[$id] )
			|| isset( $this->_instances[$id] )
			|| isset( $this->_bindings[$id] )
			|| class_exists( $id )
			|| interface_exists( $id );
	}

	/**
	 * {@inheritdoc}
	 */
	public function bind( string $abstract, string $concrete ): void
	{
		$this->_bindings[$abstract] = $concrete;
	}

	/**
	 * {@inheritdoc}
	 */
	public function singleton( string $abstract, callable $factory ): void
	{
		$this->_singletons[$abstract] = $factory;
	}

	/**
	 * {@inheritdoc}
	 */
	public function instance( string $abstract, object $instance ): void
	{
		$this->_instances[$abstract] = $instance;
	}

	/**
	 * {@inheritdoc}
	 */
	public function make( string $class, array $parameters = [] )
	{
		try
		{
			$reflector = new ReflectionClass( $class );
		}
		catch( ReflectionException $e )
		{
			throw new ContainerException( "Class {$class} does not exist", 0, $e );
		}

		// Check if class is instantiable
		if( !$reflector->isInstantiable() )
		{
			throw new ContainerException(
				"Class {$class} is not instantiable (may be abstract or interface)"
			);
		}

		$constructor = $reflector->getConstructor();

		// No constructor - just instantiate
		if( is_null( $constructor ) )
		{
			return new $class;
		}

		// Resolve constructor dependencies
		$dependencies = $this->resolveDependencies(
			$constructor->getParameters(),
			$parameters
		);

		return $reflector->newInstanceArgs( $dependencies );
	}

	/**
	 * Resolve method/constructor dependencies
	 *
	 * @param ReflectionParameter[] $parameters Constructor/method parameters
	 * @param array $primitives User-provided primitive values (name => value)
	 * @return array Resolved dependencies
	 * @throws ContainerException If dependency cannot be resolved
	 */
	private function resolveDependencies( array $parameters, array $primitives = [] ): array
	{
		$dependencies = [];

		foreach( $parameters as $parameter )
		{
			$paramName = $parameter->getName();

			// Use provided primitive if exists
			if( isset( $primitives[$paramName] ) )
			{
				$dependencies[] = $primitives[$paramName];
				continue;
			}

			// Get type hint
			$type = $parameter->getType();

			if( $type && !$type->isBuiltin() )
			{
				// Type is a class/interface - recursively resolve from container
				$typeName = $type->getName();

				// Check if we can resolve this type
				if( $this->isBound( $typeName ) || class_exists( $typeName ) )
				{
					try
					{
						$dependencies[] = $this->get( $typeName );
					}
					catch( NotFoundException | ContainerException $e )
					{
						// If type allows null and we can't resolve, use null
						if( $type->allowsNull() )
						{
							$dependencies[] = null;
						}
						else
						{
							throw new ContainerException(
								"Cannot resolve dependency [{$typeName}] for parameter [{$paramName}] in class",
								0,
								$e
							);
						}
					}
				}
				elseif( $type->allowsNull() )
				{
					// Type not bound and nullable - use null
					$dependencies[] = null;
				}
				else
				{
					throw new ContainerException(
						"Cannot resolve dependency [{$typeName}] for parameter [{$paramName}] - no binding exists and type is not nullable"
					);
				}
			}
			elseif( $parameter->isDefaultValueAvailable() )
			{
				// Use default value for primitives
				$dependencies[] = $parameter->getDefaultValue();
			}
			elseif( $type && $type->allowsNull() )
			{
				// Type allows null
				$dependencies[] = null;
			}
			else
			{
				throw new ContainerException(
					"Cannot resolve primitive parameter [{$paramName}] - no default value provided"
				);
			}
		}

		return $dependencies;
	}

	/**
	 * Clear all bindings and instances (useful for testing)
	 *
	 * @return void
	 */
	public function clear(): void
	{
		$this->_bindings = [];
		$this->_singletons = [];
		$this->_instances = [];
	}

	/**
	 * Get all registered bindings
	 *
	 * @return array
	 */
	public function getBindings(): array
	{
		return $this->_bindings;
	}

	/**
	 * Check if a binding exists
	 *
	 * @param string $abstract
	 * @return bool
	 */
	public function isBound( string $abstract ): bool
	{
		return isset( $this->_bindings[$abstract] )
			|| isset( $this->_singletons[$abstract] )
			|| isset( $this->_instances[$abstract] );
	}
}
