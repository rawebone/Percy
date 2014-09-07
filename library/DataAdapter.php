<?php

namespace Rawebone\Percy;

interface DataAdapter
{
	/**
	 * @param \PDO $connection
	 */
	function setConnection(\PDO $connection);

	/**
	 * Begins a transaction with the server.
	 *
	 * @return void
	 */
	function startTransaction();

	/**
	 * Ends a transaction with the server.
	 *
	 * @param bool $commit
	 * @return void
	 */
	function endTransaction($commit = false);

	/**
	 * Runs a select on the database and returns the result as
	 * instance(s) of the given model name. Any additional parameters
	 * are bound to the prepared statement.
	 *
	 * @param string $model
	 * @param string $query
	 * @return Model[]
	 */
	function select($model, $query);

	/**
	 * Runs an insert on the database.
	 *
	 * @param string $table
	 * @param object $model
	 * @return void
	 */
	function insert($table, $model);

	/**
	 * Runs an update on the database.
	 *
	 * @param string $table
	 * @param object $object
	 * @param string $where
	 * @return void
	 */
	function update($table, $object, $where);

	/**
	 * Returns the format string for datetime().
	 *
	 * @return string
	 */
	function datetime();

	/**
	 * Returns the ID of the insert.
	 *
	 * @return mixed
	 */
	function lastInsertID();
}
