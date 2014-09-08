<?php

namespace Rawebone\Percy;

use PDOStatement;

/**
 * ExceptionFactory provides a wrapper over PDO error mechanics to
 * enable simple messages to be passed back to the user. These return
 * exceptions, rather than implicitly throwing them.
 */
class ExceptionFactory
{
    public function readError($query, array $params, PDOStatement $stmt)
    {
        static $format = "Failed to read from the database: %s (SQLSTATE %s).\r\n\r\nQuery: %s %s";

        if (($msg = $this->handleError($format, $stmt, $query, $params))) {
            return new Exceptions\ReadException($msg);
        }

        return null;
    }

    public function writeError($query, array $params, PDOStatement $stmt)
    {
        static $format = "Failed to write to the database: %s (SQLSTATE %s).\r\n\r\nQuery: %s %s";

        if (($msg = $this->handleError($format, $stmt, $query, $params))) {
            return new Exceptions\WriteException($msg);
        }

        return null;
    }

    public function execError($query, array $params, PDOStatement $stmt)
    {
        static $format = "Statement execution failed on the database: %s (SQLSTATE %s).\r\n\r\nQuery: %s %s";

        if (($msg = $this->handleError($format, $stmt, $query, $params))) {
            return new Exceptions\ExecutionException($msg);
        }

        return null;
    }

    protected function handleError($format, PDOStatement $stmt, $query, array $params)
    {
        if (($code = $stmt->errorCode()) === "00000") { // No actual error
            return null;
        }

        $msg = sprintf(
            $format,
            $this->normaliseErrorInfo($stmt->errorInfo()),
            $code,
            $query,
            $this->normaliseDatabaseParameters($params)
        );

        return $msg;
    }

    protected function normaliseDatabaseParameters(array $params)
    {
        if (count($params) === 0) {
            return "[]";
        }

        $str = "[ ";
        foreach ($params as $key => $value) {
            $str .= "$key: $value, ";
        }
        return substr($str, 0, -2) . " ]";
    }

    protected function normaliseErrorInfo($info)
    {
        if (!is_array($info) || count($info) < 3) {
            return "Unknown Error";
        }

        return "Error {$info[1]} - {$info[2]}";
    }
}
