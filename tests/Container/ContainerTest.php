<?php

namespace Tests\Patterns\Container;

use PHPUnit\Framework\TestCase;
use Neuron\Patterns\Container\Container;
use Neuron\Patterns\Container\NotFoundException;
use Neuron\Patterns\Container\ContainerException;

/**
 * Test PSR-11 container implementation
 */
class ContainerTest extends TestCase
{
	private Container $container;

	protected function setUp(): void
	{
		$this->container = new Container();
	}

	public function testBindAndGet(): void
	{
		$this->container->bind(TestInterface::class, TestConcrete::class);

		$instance = $this->container->get(TestInterface::class);

		$this->assertInstanceOf(TestConcrete::class, $instance);
	}

	public function testAutoWiring(): void
	{
		$this->container->bind(TestInterface::class, TestConcrete::class);

		$instance = $this->container->make(TestClassWithDependency::class);

		$this->assertInstanceOf(TestClassWithDependency::class, $instance);
		$this->assertInstanceOf(TestConcrete::class, $instance->dependency);
	}

	public function testSingleton(): void
	{
		$this->container->singleton(TestConcrete::class, function($c) {
			return new TestConcrete();
		});

		$instance1 = $this->container->get(TestConcrete::class);
		$instance2 = $this->container->get(TestConcrete::class);

		$this->assertSame($instance1, $instance2);
	}

	public function testInstance(): void
	{
		$instance = new TestConcrete();
		$this->container->instance(TestInterface::class, $instance);

		$retrieved = $this->container->get(TestInterface::class);

		$this->assertSame($instance, $retrieved);
	}

	public function testHas(): void
	{
		$this->container->bind(TestInterface::class, TestConcrete::class);

		$this->assertTrue($this->container->has(TestInterface::class));
		$this->assertFalse($this->container->has('NonExistent'));
	}

	public function testNotFoundExceptionThrown(): void
	{
		$this->expectException(NotFoundException::class);
		$this->container->get('NonExistentClass');
	}

	public function testCannotInstantiateInterface(): void
	{
		$this->expectException(ContainerException::class);
		$this->container->make(TestInterface::class);
	}

	public function testMakeWithParameters(): void
	{
		$instance = $this->container->make(TestClassWithPrimitive::class, [
			'name' => 'Test Name'
		]);

		$this->assertEquals('Test Name', $instance->name);
	}

	public function testClear(): void
	{
		$this->container->bind(TestInterface::class, TestConcrete::class);
		$this->assertTrue($this->container->has(TestInterface::class));

		$this->container->clear();

		// Should not have binding anymore (but class still exists)
		$this->assertTrue($this->container->has(TestInterface::class)); // Interface exists
		$this->assertFalse($this->container->isBound(TestInterface::class)); // But not bound
	}

	public function testIsBound(): void
	{
		$this->assertFalse($this->container->isBound(TestInterface::class));

		$this->container->bind(TestInterface::class, TestConcrete::class);

		$this->assertTrue($this->container->isBound(TestInterface::class));
	}

	public function testGetBindings(): void
	{
		$this->container->bind(TestInterface::class, TestConcrete::class);

		$bindings = $this->container->getBindings();

		$this->assertArrayHasKey(TestInterface::class, $bindings);
		$this->assertEquals(TestConcrete::class, $bindings[TestInterface::class]);
	}

	public function testResolvesNestedDependencies(): void
	{
		$this->container->bind(TestInterface::class, TestConcrete::class);

		$instance = $this->container->make(TestClassWithNestedDependency::class);

		$this->assertInstanceOf(TestClassWithNestedDependency::class, $instance);
		$this->assertInstanceOf(TestClassWithDependency::class, $instance->nested);
		$this->assertInstanceOf(TestConcrete::class, $instance->nested->dependency);
	}

	public function testHandlesNullableParameters(): void
	{
		$instance = $this->container->make(TestClassWithNullable::class);

		$this->assertInstanceOf(TestClassWithNullable::class, $instance);
		$this->assertNull($instance->optional);
	}

	public function testHandlesDefaultValues(): void
	{
		$instance = $this->container->make(TestClassWithDefault::class);

		$this->assertInstanceOf(TestClassWithDefault::class, $instance);
		$this->assertEquals('default', $instance->value);
	}
}

// Test fixtures

interface TestInterface {}

class TestConcrete implements TestInterface {}

class TestClassWithDependency
{
	public TestInterface $dependency;

	public function __construct(TestInterface $dependency)
	{
		$this->dependency = $dependency;
	}
}

class TestClassWithNestedDependency
{
	public TestClassWithDependency $nested;

	public function __construct(TestClassWithDependency $nested)
	{
		$this->nested = $nested;
	}
}

class TestClassWithPrimitive
{
	public string $name;

	public function __construct(string $name)
	{
		$this->name = $name;
	}
}

class TestClassWithNullable
{
	public ?TestInterface $optional;

	public function __construct(?TestInterface $optional = null)
	{
		$this->optional = $optional;
	}
}

class TestClassWithDefault
{
	public string $value;

	public function __construct(string $value = 'default')
	{
		$this->value = $value;
	}
}
