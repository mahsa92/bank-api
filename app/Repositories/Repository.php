<?php

namespace App\Repositories;

use App\Exceptions\RepositoryException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;

abstract class Repository
{
    protected array $fillable = [];

    public function __construct(private Container $container)
    {}
    /**
     * Specify Model class name
     */
    abstract public function model(): string;

    public function all(array $columns = ['*'])
    {
        return $this->query()->get($columns);
    }

    public function create(array $data, array $fillable = []): Model
    {
        $object = $this->fill($data, $this->makeModel(), $fillable);
        $object->save();

        return $object;
    }
    public function save(Model $object): bool
    {
        return $object->save();
    }

    /**
     * @throws BindingResolutionException
     * @throws RepositoryException
     */
    public function query(): Builder
    {
        return $this->makeModel()->newQuery();
    }

    /**
     * Make model
     *
     * @throws RepositoryException
     * @throws BindingResolutionException
     */
    public function makeModel(): Model
    {
        $model = $this->container->make($this->model());

        if (!$model instanceof Model) {
            throw new RepositoryException(
                "Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model",
            );
        }

        return $model;
    }
    /**
     * This method will fill the given $object by the given $array.
     * If the $fillable parameter is not available it will use the fillable
     * array of the class.
     */
    public function fill(array $data, Model $object, array $fillable = []): Model
    {
        if (empty($fillable)) {
            $fillable = $this->fillable;
        }

        if (!empty($fillable)) {
            // just fill when fillable array is not empty
            $object->fillable($fillable)->fill($data);
        }

        return $object;
    }
}
