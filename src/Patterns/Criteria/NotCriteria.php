<?php

namespace Neuron\Patterns\Criteria;

use Neuron\Data\ArrayHelper;

class NotCriteria implements ICriteria
{
	private $_criteria;

	/**
	 * NotCriteria constructor.
	 * @param ICriteria $criteria
	 */

	public function __construct( ICriteria $criteria )
	{
		$this->_criteria = $criteria;
	}

	/**
	 * @param array $entities
	 * @return array
	 */

	public function meetCriteria( array $entities )
	{
		$notCriteriaItems = $this->_criteria->meetCriteria( $entities );

		$notEntities = $entities;

		foreach( $notCriteriaItems as $item )
		{
			ArrayHelper::remove( $notEntities, $item );
		}

		return $notEntities;
	}
}
