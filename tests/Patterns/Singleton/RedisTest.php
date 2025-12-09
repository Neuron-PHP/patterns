<?php

namespace Tests\Patterns\Singleton;

use PHPUnit\Framework\TestCase;
use Neuron\Patterns\Singleton\Redis;

/**
 * Test concrete class extending Redis for testing.
 */
class RedisSingletonTest extends Redis
{
	public $testValue;
}

/**
 * Comprehensive tests for Redis Singleton.
 *
 * Tests singleton pattern with Redis backend using mocked Redis.
 */
class RedisTest extends TestCase
{
	private $redisMock;

	protected function setUp(): void
	{
		parent::setUp();

		// Create stub for Redis if it doesn't exist
		if (!class_exists('\Redis')) {
			// Create a stub class for testing
			eval('
				class Redis {
					public function connect($host, $port) {}
					public function get($key) {}
					public function set($key, $value) {}
					public function del($key) {}
				}
			');
		}

		// Create mock Redis
		$this->redisMock = $this->createMock(\Redis::class);
	}

	public function testInstanceReturnsDeserializedObject(): void
	{
		// Create test object
		$instance = new RedisSingletonTest();
		$instance->testValue = 'redis_test_123';
		$serialized = serialize($instance);

		// Mock Redis to return serialized data
		$this->redisMock
			->expects($this->once())
			->method('get')
			->with(RedisSingletonTest::class)
			->willReturn($serialized);

		// Inject mock using reflection
		$reflection = new \ReflectionClass(Redis::class);
		$property = $reflection->getProperty('_redis');
		$property->setAccessible(true);
		$property->setValue(null, $this->redisMock);

		$retrieved = RedisSingletonTest::instance();

		$this->assertInstanceOf(RedisSingletonTest::class, $retrieved);
		$this->assertEquals('redis_test_123', $retrieved->testValue);
	}

	public function testInstanceReturnsNullWhenNoData(): void
	{
		// Mock Redis to return false (no data)
		$this->redisMock
			->expects($this->once())
			->method('get')
			->with(RedisSingletonTest::class)
			->willReturn(false);

		// Inject mock using reflection
		$reflection = new \ReflectionClass(Redis::class);
		$property = $reflection->getProperty('_redis');
		$property->setAccessible(true);
		$property->setValue(null, $this->redisMock);

		$retrieved = RedisSingletonTest::instance();

		$this->assertNull($retrieved);
	}

	public function testSerializeStoresSerializedObject(): void
	{
		$instance = new RedisSingletonTest();
		$instance->testValue = 'serialize_redis';

		// Mock Redis to expect set() with serialized object
		$this->redisMock
			->expects($this->once())
			->method('set')
			->with(
				RedisSingletonTest::class,
				$this->callback(function($value) use ($instance) {
					$unserialized = unserialize($value);
					return $unserialized->testValue === $instance->testValue;
				})
			);

		// Inject mock using reflection
		$reflection = new \ReflectionClass(Redis::class);
		$property = $reflection->getProperty('_redis');
		$property->setAccessible(true);
		$property->setValue(null, $this->redisMock);

		$instance->serialize();
	}

	public function testInvalidateDeletesKey(): void
	{
		// Mock Redis to expect del() call
		$this->redisMock
			->expects($this->once())
			->method('del')
			->with(RedisSingletonTest::class);

		// Inject mock using reflection
		$reflection = new \ReflectionClass(Redis::class);
		$property = $reflection->getProperty('_redis');
		$property->setAccessible(true);
		$property->setValue(null, $this->redisMock);

		RedisSingletonTest::invalidate();
	}

	public function testGetRedisCreatesConnectionOnFirstCall(): void
	{
		// Skip if we can't connect to Redis server
		if (class_exists('\Redis', false)) {
			try {
				$testRedis = new \Redis();
				@$testRedis->connect('127.0.0.1', 6379, 0.1);
				if (!$testRedis->ping()) {
					$this->markTestSkipped('Redis server not available');
				}
				$testRedis->close();
			} catch (\Throwable $e) {
				$this->markTestSkipped('Redis server not available: ' . $e->getMessage());
			}
		}

		// Reset static redis
		$reflection = new \ReflectionClass(Redis::class);
		$property = $reflection->getProperty('_redis');
		$property->setAccessible(true);
		$property->setValue(null, null);

		// Get the protected method
		$method = $reflection->getMethod('getRedis');
		$method->setAccessible(true);

		// First call should create new Redis
		$redis1 = $method->invoke(null);
		$this->assertInstanceOf(\Redis::class, $redis1);

		// Second call should return same instance
		$redis2 = $method->invoke(null);
		$this->assertSame($redis1, $redis2);
	}
}
