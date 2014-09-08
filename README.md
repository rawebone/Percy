# Percy - WIP

Percy (short for Persistence) is a tiny Active Record implementation designed
to provide a good enough solution to database access for general cases, and
with extensibility in mind for the future.

I've written it because I needed it, something small, concise, and clean for
my own projects. If this meets with your brief, zang! It works on the 80-20
rule with the goal of giving a good enough solution to the garden variety
data requirements. As such there are no joins, eager loading, real relationships
or complex magic handling.

There is the following:

```php
<?php

use Rawebone\Percy\Percy;
use Rawebone\Percy\Model;

Percy::connect("mysql:server=localhost;dbname=test;", "user", "password");

class Person extends Model
{
    public $id;
    public $created_at;
    public $updated_at;
    public $first;
    public $last;
}

class FavouriteColours extends Model
{
    public $id;
    public $created_at;
    public $updated_at;
    public $colour;
    public $person_id;

    public function person()
    {
        return Person::find($this->person_id);
    }
}

$favourites = FavouriteColours::all(); // SELECT * FROM favourite_colours
foreach ($favourites as $favourite) {
    $person = $favourite->person(); // SELECT * FROM persons WHERE id = ?

    echo $person->first, " likes ", $favourite->colour, PHP_EOL;
}

$pete = new Person();
$pete->first = "Pete";
$pete->last  = "Lazlo";
$pete->save(); // INSERT INTO ...
$pete->last  = "Lazelo";
$pete->save(); // UPDATE persons SET ...

var_dump($pete);

// class Person {
//   $id => "2"
//   $first => "Pete"
//   $last => "Lazelo"
//   $created_at => "2014-01-02 00:00:00"
//   $updated_at => "2014-01-02 00:00:01"
// }

$person = Person::findByFirst("Pete"); // SELECT * FROM persons WHERE first = ?
$person = Person::findWhere("last = ?", "Lazelo"); // SELECT * FROM persons WHERE last = ?

$pete->delete();

foreach ($pete->changes() as $property => $value) {
    echo "Property ", $property, " is now ", $value, PHP_EOL;
}

/**
 * Override defaults
 */
class MyTable extends Model
{
    public function _table()
    {
        return "my_other_table_name";
    }

    public function _pk()
    {
        return "my_id";
    }

    public function _created()
    {
        return "register";
    }

    public function _updated()
    {
        return "amend";
    }
}

$blah = MyTable::find(1); // SELECT * FROM my_other_table_name WHERE my_id = 1

use Rawebone\Percy\Validation;

class Validating extends Model implements Validation
{
    public $id;
    public $created_at;
    public $updated_at;
    public $name;

    public function validate()
    {
        if (empty($this->name)) {
            return "Name cannot be blank";
        }
    }
}

(new Validating())->save(); // Rawebone\Percy\Exceptions\ValidationException

```

## Roadmap

* Tidy up of API basics
* Full test suite
* Add a basic migration system

## License

[MIT License](LICENSE). Go wild.


