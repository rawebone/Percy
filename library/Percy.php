<?php

namespace Rawebone\Percy;

class Percy
{
	/**
	 * @var DataAdapter
	 */
	protected static $adapter;

	public static function setAdapter(DataAdapter $adapter)
	{
		self::$adapter = $adapter;
	}

	public static function now()
	{
		return date(self::$adapter->datetime());
	}
}
