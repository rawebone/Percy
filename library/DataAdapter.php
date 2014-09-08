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
	 * @param Model $model
     * @throws Exceptions\WriteException
     * @throws Exceptions\ValidationException
	 * @return void
	 */
	function insert($table, Model $model);

	/**
	 * Runs an update on the database. Any additional parameters
     * are bound to the prepared statement.
	 *
	 * @param string $table
	 * @param Model $model
	 * @param string $where
     * @throws Exceptions\WriteException
     * @throws Exceptions\ValidationException
	 * @return void
	 */
	function update($table, Model $model, $where);

    /**
     * Runs a deletion on the database. Any additional parameters
     * are bound to the prepared statement.
     *
     * @param string $table
     * @param string $where
     * @throws Exceptions\WriteException
     * @return void
     */
    function delete($table, $where);

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
