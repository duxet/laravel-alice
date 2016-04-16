<?php namespace duxet\Alice\Persisters;

use Nelmio\Alice\PersisterInterface;

class Eloquent implements PersisterInterface
{
    /**
     * Loads a fixture file
     *
     * @param array [object] $objects instance to persist in the DB
     */
    public function persist(array $objects)
    {
        foreach ($objects as $object) {
            $object->save();
        }
    }

    /**
     * Finds an object by class and id
     *
     * @param  string $class
     * @param  int $id
     * @return mixed
     */
    public function find($class, $id)
    {
        return $class::find($id);
    }
}
