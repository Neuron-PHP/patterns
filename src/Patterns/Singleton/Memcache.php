<?php

namespace Neuron\Patterns\Singleton;

/**
 * Singleton that stores to memcached.
 */
class Memcache extends Base
{
	private static $_memcache;

	protected static function getMemcache()
	{
		if( self::$_memcache )
		{
			return self::$_memcache;
		}

		$memcache = new \Memcached();
		$memcache->addServer( '127.0.0.1', 11211 );
		self::$_memcache = $memcache;

		return $memcache;
	}

	public static function instance()
	{
		$memcache = self::getMemcache();
		return $memcache->get( get_called_class() );
	}

	public function serialize()
	{
		$memcache = self::getMemcache();
		$memcache->set( get_called_class(), $this );
	}

	public static function invalidate()
	{
		$memcache = self::getMemcache();
		$memcache->set( get_called_class(), false );
	}
}
