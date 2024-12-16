<?php
namespace Neuron\Patterns;

use Neuron\Patterns\Singleton;

/**
 * Singleton based registry object.
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
}
