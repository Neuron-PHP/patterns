<?php

namespace Neuron\Patterns\Criteria;

/**
 * Criteria pattern interface for entity filtering and selection.
 * 
 * The ICriteria interface defines the contract for implementing the Criteria
 * pattern, which provides a flexible way to define and combine filtering
 * conditions for collections of entities. This pattern enables dynamic query
 * construction and complex filtering logic through composable criteria objects.
 * 
 * Key benefits of the Criteria pattern:
 * - Encapsulates filtering logic in reusable objects
 * - Supports dynamic query construction at runtime
 * - Enables complex criteria composition (AND, OR, NOT operations)
 * - Provides type-safe filtering without string-based queries
 * - Allows for criteria caching and optimization
 * - Supports both in-memory and database filtering strategies
 * 
 * Common criteria implementations:
 * - KeyValue: Filter by exact key-value matches
 * - AndCriteria: Logical AND combination of multiple criteria
 * - OrCriteria: Logical OR combination of multiple criteria
 * - NotCriteria: Logical negation of another criteria
 * - Range criteria: Filter by numeric or date ranges
 * - Pattern criteria: Filter by regular expression patterns
 * 
 * @package Neuron\Patterns\Criteria
 * 
 * @example
 * ```php
 * // Simple criteria implementation
 * class ActiveUserCriteria implements ICriteria
 * {
 *     public function meetCriteria(array $entities): array
 *     {
 *         return array_filter($entities, function($user) {
 *             return $user->isActive();
 *         });
 *     }
 * }
 * 
 * // Complex criteria composition
 * $activeCriteria = new KeyValue('status', 'active');
 * $adminCriteria = new KeyValue('role', 'admin');
 * $activeAdmins = new AndCriteria($activeCriteria, $adminCriteria);
 * 
 * $filteredUsers = $activeAdmins->meetCriteria($allUsers);
 * ```
 */
interface ICriteria
{
	/**
	 * @param array $entities
	 * @return array
	 */

	public function meetCriteria( array $entities );
}
