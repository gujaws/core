<?php
/**
 * @author Joas Schilling <nickvergessen@owncloud.com>
 *
 * @copyright Copyright (c) 2015, ownCloud, Inc.
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 */

namespace OC\DB;

use Doctrine\DBAL\Query\Expression\ExpressionBuilder as DoctrineExpressionBuilder;
use OCP\DB\IExpressionBuilder;
use OCP\IDBConnection;

class ExpressionBuilder implements IExpressionBuilder {
	/** @var \Doctrine\DBAL\Query\Expression\ExpressionBuilder */
	private $expressionBuilder;

	/**
	 * Initializes a new <tt>ExpressionBuilder</tt>.
	 *
	 * @param \OCP\IDBConnection $connection
	 */
	public function __construct(IDBConnection $connection) {
		$this->expressionBuilder = new DoctrineExpressionBuilder($connection);
	}

	/**
	 * Creates a conjunction of the given boolean expressions.
	 *
	 * Example:
	 *
	 *     [php]
	 *     // (u.type = ?) AND (u.role = ?)
	 *     $expr->andX('u.type = ?', 'u.role = ?'));
	 *
	 * @param mixed $x Optional clause. Defaults = null, but requires
	 *                 at least one defined when converting to string.
	 *
	 * @return \OCP\DB\ICompositeExpression
	 */
	public function andX($x = null) {
		$compositeExpression = call_user_func_array([$this->expressionBuilder, 'andX'], func_get_args());
		return new CompositeExpression($compositeExpression);
	}

	/**
	 * Creates a disjunction of the given boolean expressions.
	 *
	 * Example:
	 *
	 *     [php]
	 *     // (u.type = ?) OR (u.role = ?)
	 *     $qb->where($qb->expr()->orX('u.type = ?', 'u.role = ?'));
	 *
	 * @param mixed $x Optional clause. Defaults = null, but requires
	 *                 at least one defined when converting to string.
	 *
	 * @return \OCP\DB\ICompositeExpression
	 */
	public function orX($x = null) {
		$compositeExpression = call_user_func_array([$this->expressionBuilder, 'orX'], func_get_args());
		return new CompositeExpression($compositeExpression);
	}

	/**
	 * Creates a comparison expression.
	 *
	 * @param mixed $x The left expression.
	 * @param string $operator One of the ExpressionBuilder::* constants.
	 * @param mixed $y The right expression.
	 *
	 * @return string
	 */
	public function comparison($x, $operator, $y) {
		return $this->expressionBuilder->comparison($x, $operator, $y);
	}

	/**
	 * Creates an equality comparison expression with the given arguments.
	 *
	 * First argument is considered the left expression and the second is the right expression.
	 * When converted to string, it will generated a <left expr> = <right expr>. Example:
	 *
	 *     [php]
	 *     // u.id = ?
	 *     $expr->eq('u.id', '?');
	 *
	 * @param mixed $x The left expression.
	 * @param mixed $y The right expression.
	 *
	 * @return string
	 */
	public function eq($x, $y) {
		return $this->expressionBuilder->eq($x, $y);
	}

	/**
	 * Creates a non equality comparison expression with the given arguments.
	 * First argument is considered the left expression and the second is the right expression.
	 * When converted to string, it will generated a <left expr> <> <right expr>. Example:
	 *
	 *     [php]
	 *     // u.id <> 1
	 *     $q->where($q->expr()->neq('u.id', '1'));
	 *
	 * @param mixed $x The left expression.
	 * @param mixed $y The right expression.
	 *
	 * @return string
	 */
	public function neq($x, $y) {
		return $this->expressionBuilder->neq($x, $y);
	}

	/**
	 * Creates a lower-than comparison expression with the given arguments.
	 * First argument is considered the left expression and the second is the right expression.
	 * When converted to string, it will generated a <left expr> < <right expr>. Example:
	 *
	 *     [php]
	 *     // u.id < ?
	 *     $q->where($q->expr()->lt('u.id', '?'));
	 *
	 * @param mixed $x The left expression.
	 * @param mixed $y The right expression.
	 *
	 * @return string
	 */
	public function lt($x, $y) {
		return $this->expressionBuilder->lt($x, $y);
	}

	/**
	 * Creates a lower-than-equal comparison expression with the given arguments.
	 * First argument is considered the left expression and the second is the right expression.
	 * When converted to string, it will generated a <left expr> <= <right expr>. Example:
	 *
	 *     [php]
	 *     // u.id <= ?
	 *     $q->where($q->expr()->lte('u.id', '?'));
	 *
	 * @param mixed $x The left expression.
	 * @param mixed $y The right expression.
	 *
	 * @return string
	 */
	public function lte($x, $y) {
		return $this->expressionBuilder->lte($x, $y);
	}

	/**
	 * Creates a greater-than comparison expression with the given arguments.
	 * First argument is considered the left expression and the second is the right expression.
	 * When converted to string, it will generated a <left expr> > <right expr>. Example:
	 *
	 *     [php]
	 *     // u.id > ?
	 *     $q->where($q->expr()->gt('u.id', '?'));
	 *
	 * @param mixed $x The left expression.
	 * @param mixed $y The right expression.
	 *
	 * @return string
	 */
	public function gt($x, $y) {
		return $this->expressionBuilder->gt($x, $y);
	}

	/**
	 * Creates a greater-than-equal comparison expression with the given arguments.
	 * First argument is considered the left expression and the second is the right expression.
	 * When converted to string, it will generated a <left expr> >= <right expr>. Example:
	 *
	 *     [php]
	 *     // u.id >= ?
	 *     $q->where($q->expr()->gte('u.id', '?'));
	 *
	 * @param mixed $x The left expression.
	 * @param mixed $y The right expression.
	 *
	 * @return string
	 */
	public function gte($x, $y) {
		return $this->expressionBuilder->gte($x, $y);
	}

	/**
	 * Creates an IS NULL expression with the given arguments.
	 *
	 * @param string $x The field in string format to be restricted by IS NULL.
	 *
	 * @return string
	 */
	public function isNull($x) {
		return $this->expressionBuilder->isNull($x);
	}

	/**
	 * Creates an IS NOT NULL expression with the given arguments.
	 *
	 * @param string $x The field in string format to be restricted by IS NOT NULL.
	 *
	 * @return string
	 */
	public function isNotNull($x) {
		return $this->expressionBuilder->isNotNull($x);
	}

	/**
	 * Creates a LIKE() comparison expression with the given arguments.
	 *
	 * @param string $x Field in string format to be inspected by LIKE() comparison.
	 * @param mixed $y Argument to be used in LIKE() comparison.
	 *
	 * @return string
	 */
	public function like($x, $y) {
		return $this->expressionBuilder->like($x, $y);
	}

	/**
	 * Creates a NOT LIKE() comparison expression with the given arguments.
	 *
	 * @param string $x Field in string format to be inspected by NOT LIKE() comparison.
	 * @param mixed $y Argument to be used in NOT LIKE() comparison.
	 *
	 * @return string
	 */
	public function notLike($x, $y) {
		return $this->expressionBuilder->notLike($x, $y);
	}

	/**
	 * Creates a IN () comparison expression with the given arguments.
	 *
	 * @param string $x The field in string format to be inspected by IN() comparison.
	 * @param string|array $y The placeholder or the array of values to be used by IN() comparison.
	 *
	 * @return string
	 */
	public function in($x, $y) {
		return $this->expressionBuilder->in($x, $y);
	}

	/**
	 * Creates a NOT IN () comparison expression with the given arguments.
	 *
	 * @param string $x The field in string format to be inspected by NOT IN() comparison.
	 * @param string|array $y The placeholder or the array of values to be used by NOT IN() comparison.
	 *
	 * @return string
	 */
	public function notIn($x, $y) {
		return $this->expressionBuilder->notIn($x, $y);
	}

	/**
	 * Quotes a given input parameter.
	 *
	 * @param mixed $input The parameter to be quoted.
	 * @param string|null $type The type of the parameter.
	 *
	 * @return string
	 */
	public function literal($input, $type = null) {
		return $this->expressionBuilder->literal($input, $type);
	}
}
