<?php

namespace Neuron\Patterns\Criteria;

/**
 * Abstract base class for implementing the Criteria pattern.
 * 
 * This class provides the foundation for creating composable criteria objects
 * that can be combined using logical operators (AND, OR, NOT). The Criteria
 * pattern allows for flexible, reusable, and chainable query conditions.
 * 
 * Concrete implementations should define the meetCriteria() method to specify
 * the actual filtering logic. This base class provides the logical composition
 * methods for building complex criteria chains.
 * 
 * The pattern enables:
 * - Composable query logic through method chaining
 * - Reusable criteria components across different contexts
 * - Clear separation of individual criteria concerns
 * - Dynamic query building at runtime
 * 
 * @package Neuron\Patterns\Criteria
 * 
 * @example
 * ```php
 * class AgeRangeCriteria extends Base
 * {
 *     private $minAge, $maxAge;
 *     
 *     public function __construct(int $min, int $max)
 *     {
 *         $this->minAge = $min;
 *         $this->maxAge = $max;
 *     }
 *     
 *     public function meetCriteria(array $items): array
 *     {
 *         return array_filter($items, function($item) {
 *             return $item->age >= $this->minAge && $item->age <= $this->maxAge;
 *         });
 *     }
 * }
 * 
 * // Usage: $adults = (new AgeRangeCriteria(18, 65))->_and(new ActiveCriteria())->meetCriteria($users);
 * ```
 */
abstract class Base implements ICriteria
{
	protected $_Criteria;

	/**
	 * Creates an AND combination of this criteria with another criteria.
	 * 
	 * @param ICriteria $OtherCriteria The criteria to combine with using AND logic
	 * @return AndCriteria A new criteria that represents this AND other
	 */
	public function _and( ICriteria $OtherCriteria )
	{
		return new AndCriteria( $this, $OtherCriteria );
	}

	/**
	 * Creates an OR combination of this criteria with another criteria.
	 * 
	 * @param ICriteria $OtherCriteria The criteria to combine with using OR logic
	 * @return OrCriteria A new criteria that represents this OR other
	 */
	public function _or( ICriteria $OtherCriteria )
	{
		return new OrCriteria( $this, $OtherCriteria );
	}

	/**
	 * Creates a NOT (negation) of this criteria.
	 * 
	 * @return NotCriteria A new criteria that represents the negation of this criteria
	 */
	public function _not()
	{
		return new NotCriteria( $this );
	}
}
