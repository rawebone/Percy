<?php

namespace Rawebone\Percy;

use PDO;

class Percy
{
	/**
	 * @var DataAdapter
	 */
	protected static $adapter;

    /**
     * Connects Percy to the database.
     *
     * @param string $dsn
     * @param string $user
     * @param string $pass
     * @param DataAdapter $adapter
     */
    public static function connect($dsn, $user, $pass, DataAdapter $adapter = null)
    {
        /** @var DataAdapter $adapter */
        $adapter = ($adapter === null ? new MySqlAdapter() : $adapter);

        $connection = new PDO($dsn, $user, $pass, array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,
            PDO::ATTR_CASE => PDO::CASE_LOWER
        ));

        $adapter->setConnection($connection);

        self::$adapter = $adapter;
    }

    /**
     * Returns the a string with the formatted current datetime.
     *
     * @return string
     */
    public static function now()
	{
		return date(self::$adapter->datetime());
	}

    public static function insert($table, Model $model)
    {
        self::$adapter->insert($table, $model);
    }

    public static function update($table, Model $model, $where)
    {
        call_user_func_array(array(self::$adapter, "update"), func_get_args());
    }

    public static function delete($table, $pkField, $pk)
    {
        self::$adapter->delete($table, "$pkField = ?", $pk);
    }

    public static function all($model, $table)
    {
        return self::$adapter->select($model, "SELECT * FROM $table");
    }

    public static function findById($model, $table, $pkField, $pk)
    {
        return first(self::$adapter->select($model, "SELECT * FROM $table WHERE $pkField = ?", $pk));
    }

    public static function findByField($model, $table, $field, $value)
    {
        return self::$adapter->select($model, "SELECT * FROM $table WHERE $field = ?", $value);
    }

    public static function findByWhere($model, $table, $where)
    {
        $query  = "SELECT * FROM $table WHERE $where";
        $params = array_merge(array($model, $query), array_slice(func_get_args(), 3));

        return call_user_func_array(array(self::$adapter, "select"), $params);
    }
}
