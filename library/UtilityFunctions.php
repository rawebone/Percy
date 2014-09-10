<?php

namespace Rawebone\Percy;

function first(array $set)
{
	return count($set) > 0 ? $set[0] : null;
}

function last(array $set)
{
	$count = count($set);

	return $count > 0 ? $set[$count - 1] : null;
}

function all(array $set)
{
	return $set;
}

function snake($value)
{
	$replace = '$1_$2';
	return (ctype_lower($value) ? $value : strtolower(preg_replace('/(.)([A-Z])/', $replace, $value)));
}
