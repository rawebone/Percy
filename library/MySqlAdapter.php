<?php

namespace Rawebone\Percy;

use Rawebone\Percy\Exceptions\ValidationException;

class MySqlAdapter implements DataAdapter
{
	/**
	 * @var \PDO
	 */
	protected $connection;

    /**
     * @var ExceptionFactory
     */
    protected $errors;

    public function __construct()
    {
        $this->errors = new ExceptionFactory();
    }

	/**
	 * @param \PDO $connection
	 */
    public function setConnection(\PDO $connection)
	{
		$this->connection = $connection;
	}

	/**
	 * Begins a transaction with the server.
	 *
	 * @return void
	 */
    public function startTransaction()
	{
		$this->connection->beginTransaction();
	}

	/**
	 * Ends a transaction with the server.
	 *
	 * @param bool $commit
	 * @return void
	 */
    public function endTransaction($commit = false)
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
     * @throws Exceptions\ReadException
	 * @return Model[]
	 */
    public function select($model, $query)
	{
        $params = array_slice(func_get_args(), 2);

        $stmt   = $this->connection->prepare($query);
        $result = $stmt->execute($params);

        if ($result === false && ($ex = $this->errors->readError($query, $params, $stmt))) {
            $stmt->closeCursor();
            throw $ex;
        }

        $all = $stmt->fetchAll(\PDO::FETCH_CLASS, $model, array(false));
        $stmt->closeCursor();

        return $all;
	}

	/**
	 * Runs an insert on the database.
	 *
	 * @param string $table
	 * @param Model $model
     * @throws Exceptions\WriteException
     * @throws Exceptions\ValidationException
	 * @return void
	 */
    public function insert($table, Model $model)
	{
        $this->validate($model);

        $params = array();
        $query  = "INSERT INTO $table (";

        // Iterate over objects public properties, we trust the key names
        // as PHP is strict on the names allowed.
        foreach ($model as $key => $value) {
            if ($value === null) {
                continue;
            }

            $query .= "$key, ";
            $params[] = $value;
        }

        // Create placeholders for the prepared statement to use to interpolate data
        $placeholders = substr(($p = str_repeat("?, ", count($params))), 0, strlen($p) - 2);

        // Trim last comma and append placeholders
        $query = substr($query, 0, strlen($query) - 2) . ") VALUES($placeholders)";

        $stmt = $this->connection->prepare($query);
        $res = $stmt->execute($params);

        if ($res === false && ($ex = $this->errors->writeError($query, $params, $stmt))) {
            throw $ex;
        }

        $model->{$model->_pk()} = $this->lastInsertID();
        $stmt->closeCursor();
	}

	/**
	 * Runs an update on the database.
	 *
	 * @param string $table
	 * @param Model $model
	 * @param string $where
     * @throws Exceptions\WriteException
     * @throws Exceptions\ValidationException
	 * @return void
	 */
    public function update($table, Model $model, $where)
	{
        $this->validate($model);

        $params = array();
        $query = "UPDATE $table SET ";

        foreach ($model->changes() as $key => $value) {
            $query .= "$key = ?, ";
            $params[] = $value;
        }

        // Clear trailing comma/space
        $query = substr($query, 0, -2);

        if (!empty($where)) {
            $query .= " WHERE $where";
            $params = array_merge($params, array_slice(func_get_args(), 3));
        }

        $stmt = $this->connection->prepare($query);
        $res = $stmt->execute($params);

        if ($res === false && ($ex = $this->errors->writeError($query, $params, $stmt))) {
            throw $ex;
        }

        $stmt->closeCursor();
	}

    public function delete($table, $where)
    {
        $params = array_slice(func_get_args(), 2);
        $query  = "DELETE FROM $table";

        if (!empty($where)) {
            $query .= " WHERE $where";
        }

        $stmt = $this->connection->prepare($query);
        $res = $stmt->execute($params);

        if ($res === false && ($ex = $this->errors->writeError($query, $params, $stmt))) {
            throw $ex;
        }

        $stmt->closeCursor();
    }

	/**
	 * Returns the format string for datetime().
	 *
	 * @return string
	 */
    public function datetime()
	{
		return "Y-m-d H:i:s";
	}

	/**
	 * Returns the ID of the insert.
	 *
	 * @return mixed
	 */
    public function lastInsertID()
	{
		return $this->connection->lastInsertId();
	}

    protected function validate($model)
    {
        if ($model instanceof Validation && ($msg = $model->validate())) {
            throw new ValidationException($msg);
        }
    }
}
