<?php

namespace Rawebone\Percy;

class MySqlAdapter implements DataAdapter
{
	/**
	 * @var \PDO
	 */
	protected $connection;

	/**
	 * @param \PDO $connection
	 */
	function setConnection(\PDO $connection)
	{
		$this->connection = $connection;
	}

	/**
	 * Begins a transaction with the server.
	 *
	 * @return void
	 */
	function startTransaction()
	{
		$this->connection->beginTransaction();
	}

	/**
	 * Ends a transaction with the server.
	 *
	 * @param bool $commit
	 * @return void
	 */
	function endTransaction($commit = false)
	{
		if ($commit) {
			$this->connection->commit();

		} else {
			$this->connection->rollBack();
		}
	}

	/**
	 * Runs a select on the database and returns the result as
	 * instance(s) of the given model name. Any additional parameters
	 * are bound to the prepared statement.
	 *
	 * @param string $model
	 * @param string $query
	 * @return Model[]
	 */
	function select($model, $query)
	{
		// TODO: Implement select() method.
	}

	/**
	 * Runs an insert on the database.
	 *
	 * @param string $table
	 * @param object $model
	 * @return void
	 */
	function insert($table, $model)
	{
		// TODO: Implement insert() method.
	}

	/**
	 * Runs an update on the database.
	 *
	 * @param string $table
	 * @param object $object
	 * @param string $where
	 * @return void
	 */
	function update($table, $object, $where)
	{
		// TODO: Implement update() method.
	}

	/**
	 * Returns the format string for datetime().
	 *
	 * @return string
	 */
	function datetime()
	{
		return "Y-m-d H:i:s";
	}

	/**
	 * Returns the ID of the insert.
	 *
	 * @return mixed
	 */
	function lastInsertID()
	{
		// TODO: Implement lastInsertID() method.
	}
}
