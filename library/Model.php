<?php

namespace Rawebone\Percy;

use Rawebone\Percy\Exceptions\ModelException;

class Model
{
	private $fresh = true;
	private $shadow;

	public function __construct($fresh = true)
	{
		$this->makeShadow();
        $this->fresh  = $fresh;
	}

	public function save()
	{
		($this->fresh ? $this->insert() : $this->update());
	}

	public function delete()
	{
		Percy::delete($this->_table(), $this->_pk(), $this->{$this->_pk()});
	}

	public function insert()
	{
		$now = Percy::now();
		$this->{$this->_created()} = $now;
		$this->{$this->_updated()} = $now;

		Percy::insert($this->_table(), $this);

        $this->makeShadow();
	}

	public function update()
	{
		$this->{$this->_updated()} = Percy::now();

		Percy::update($this->_table(), $this, "{$this->_pk()} = ?", $this->{$this->_pk()});

        $this->makeShadow();
	}

	public function changes()
	{
		return $this->shadow->computeChanges($this);
	}

	/**
	 * Returns the name of the primary key field, by default "id". Override
	 * this if your table uses a different convention,
	 *
	 * @return string
	 */
	public function _pk()
	{
		return "id";
	}

	/**
	 * Returns the name of the field which stores the creation time of the
	 * record, by default "created_at" following the Eloquent convention.
	 * Override this if your table uses a different convention.
	 *
	 * @return string
	 */
	public function _created()
	{
		return "created_at";
	}

	/**
	 * Returns the name of the field which stores the updated time of the
	 * record, by default "updated_at" following the Eloquent convention.
	 * Override this if your table uses a different convention.
	 *
	 * @return string
	 */
	public function _updated()
	{
		return "updated_at";
	}

	/**
	 * Returns the name of the table which the model refers. By default,
	 * this will be Object -> objects, MyObject -> my_objects. Override
	 * this if your table name does not meet the convention.
	 *
	 * @return string
	 */
	public function _table()
	{
        return Percy::snake(get_class($this)) . "s";
	}

    protected function makeShadow()
    {
        $this->shadow = new Shadow($this);
    }

	/**
	 * Returns instances of the current model from all of the data in the table.
	 *
	 * @return static[]
	 */
	public static function all()
	{
		$model = new static();

		return Percy::all(get_called_class(), $model->_table());
	}

	/**
	 * Returns an instance of the current model from the record with the provided ID.
	 *
	 * @param mixed $id
	 * @return static
	 */
	public static function find($id)
	{
		$model = new static();

		return Percy::findById(get_called_class(), $model->_table(), $model->_pk(), $id);
	}

    public static function findWhere($clause)
    {
        $model = new static();

        $params = array_merge(array(
            get_called_class(),
            $model->_table(),
        ), func_get_args());

        return call_user_func_array(array("Percy", "findByWhere"), $params);
    }

    public static function findFirstWhere($clause)
    {
        return first(call_user_func_array("static", "findWhere"), func_get_args());
    }

    public static function findLastWhere($clause)
    {
        return last(call_user_func_array("static", "findWhere"), func_get_args());
    }

    public static function __callStatic($name, $args)
    {
        if (preg_match("/^findBy([[:alnum:]]+)$/", $name, $match)) {
            $model = new static();

            return Percy::findByField(get_called_class(), $model->_table(), snake($match[1]), $args[0]);
        }

        if (preg_match("/^findFirstBy([[:alnum:]]+)$/", $name, $match)) {
            $model = new static();

            return first(Percy::findByField(get_called_class(), $model->_table(), snake($match[1]), $args[0]));
        }

        if (preg_match("/^findLastBy([[:alnum:]]+)$/", $name, $match)) {
            $model = new static();

            return last(Percy::findByField(get_called_class(), $model->_table(), snake($match[1]), $args[0]));
        }

        throw new ModelException("Bad method call to $name");
    }
}
