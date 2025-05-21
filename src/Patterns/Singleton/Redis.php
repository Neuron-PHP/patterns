<?php

namespace Neuron\Patterns\Singleton;

/**
 * Singleton that serializes to Redis.
 */
class Redis extends Base
{
	private static $_redis;

	protected static function getRedis(): \Redis
	{
		if( self::$_redis )
		{
			return self::$_redis;
		}

		// Create a new Redis client and configure the connection
		$redis = new \Redis();
		$redis->connect('127.0.0.1', 6379 ); // Default Redis host and port
		self::$_redis = $redis;

		return $redis;
	}

	public static function instance(): mixed
	{
		$redis = self::getRedis();
		$data = $redis->get( get_called_class() );

		// Unserialize the data if found in Redis
		return $data ? unserialize( $data ) : null;
	}

	public function serialize(): void
	{
		$redis = self::getRedis();
		$redis->set( get_called_class(), serialize( $this ) );
	}

	public static function invalidate(): void
	{
		$redis = self::getRedis();
		$redis->del( get_called_class() );
	}
}
