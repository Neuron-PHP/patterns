<?php
/**
 * Created by PhpStorm.
 * User: lee
 * Date: 8/4/16
 * Time: 11:53 AM
 */

namespace Neuron\Patterns\Criteria;

use Neuron\Data\ArrayHelper;

class OrCriteria extends LogicBase implements ICriteria
{
	/**
	 * @param array $entities
	 * @return array
	 */

	public function meetCriteria( array $entities )
	{
		$firstCriteriaItems = $this->_criteria->meetCriteria( $entities );
		$otherCriteriaItems = $this->_otherCriteria->meetCriteria( $entities );

		foreach( $otherCriteriaItems as $item )
		{
			if( !ArrayHelper::contains( $firstCriteriaItems, $item ) )
			{
				$firstCriteriaItems[] = $item;
			}
		}

		return $firstCriteriaItems;
	}
}
