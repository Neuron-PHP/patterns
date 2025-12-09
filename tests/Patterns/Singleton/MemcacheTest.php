<?php

namespace Tests\Patterns\Singleton;

use PHPUnit\Framework\TestCase;
use Neuron\Patterns\Singleton\Memcache;

/**
 * Test concrete class extending Memcache for testing.
 */
class MemcacheSingletonTest extends Memcache
{
	public $testValue;
}

/**
 * Comprehensive tests for Memcache Singleton.
 *
 * Tests singleton pattern with Memcached backend using mocked Memcached.
 */
class MemcacheTest extends TestCase
{
	private $memcachedMock;

	protected function setUp(): void
	{
		parent::setUp();

		// Create stub for Memcached if it doesn't exist
		if (!class_exists('\Memcached')) {
			// Create a stub class for testing
			eval('
				class Memcached {
					public function addServer($host, $port) {}
					public function get($key) {}
					public function set($key, $value) {}
				}
			');
		}

		// Create mock Memcached
		$this->memcachedMock = $this->createMock(\Memcached::class);
	}

	public function testInstanceReturnsStoredObject(): void
	{
		// Create test object
		$instance = new MemcacheSingletonTest();
		$instance->testValue = 'test123';

		// Mock memcached to return our instance
		$this->memcachedMock
			->expects($this->once())
			->method('get')
			->with(MemcacheSingletonTest::class)
			->willReturn($instance);

		// Inject mock using reflection
		$reflection = new \ReflectionClass(Memcache::class);
		$property = $reflection->getProperty('_memcache');
		$property->setAccessible(true);
		$property->setValue(null, $this->memcachedMock);

		$retrieved = MemcacheSingletonTest::instance();

		$this->assertSame($instance, $retrieved);
		$this->assertEquals('test123', $retrieved->testValue);
	}

	public function testSerializeSetsObjectInMemcache(): void
	{
		$instance = new MemcacheSingletonTest();
		$instance->testValue = 'serialize_test';

		// Mock memcached to expect set() call
		$this->memcachedMock
			->expects($this->once())
			->method('set')
			->with(
				MemcacheSingletonTest::class,
				$instance
			);

		// Inject mock using reflection
		$reflection = new \ReflectionClass(Memcache::class);
		$property = $reflection->getProperty('_memcache');
		$property->setAccessible(true);
		$property->setValue(null, $this->memcachedMock);

		$instance->serialize();
	}

	public function testInvalidateSetsToFalse(): void
	{
		// Mock memcached to expect set with false
		$this->memcachedMock
			->expects($this->once())
			->method('set')
			->with(
				MemcacheSingletonTest::class,
				false
			);

		// Inject mock using reflection
		$reflection = new \ReflectionClass(Memcache::class);
		$property = $reflection->getProperty('_memcache');
		$property->setAccessible(true);
		$property->setValue(null, $this->memcachedMock);

		MemcacheSingletonTest::invalidate();
	}

	public function testGetMemcacheCreatesConnectionOnFirstCall(): void
	{
		// Reset static memcache
		$reflection = new \ReflectionClass(Memcache::class);
		$property = $reflection->getProperty('_memcache');
		$property->setAccessible(true);
		$property->setValue(null, null);

		// Get the protected method
		$method = $reflection->getMethod('getMemcache');
		$method->setAccessible(true);

		// First call should create new Memcached
		$memcache1 = $method->invoke(null);
		$this->assertInstanceOf(\Memcached::class, $memcache1);

		// Second call should return same instance
		$memcache2 = $method->invoke(null);
		$this->assertSame($memcache1, $memcache2);
	}
}
