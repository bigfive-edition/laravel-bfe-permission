<?php

namespace BigFiveEdition\Permission\Repositories\Base;

use Carbon\Carbon;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Collection;
use Prettus\Repository\Eloquent\BaseRepository as PBaseRepository;
use Prettus\Repository\Events\RepositoryEntityCreated;
use Prettus\Repository\Events\RepositoryEntityCreating;
use Prettus\Repository\Events\RepositoryEntityUpdated;
use Prettus\Repository\Events\RepositoryEntityUpdating;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Repository\Traits\ComparesVersionsTrait;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use Serializable;

/**
 * Class BaseRepository
 *
 * @package Prettus\Repository\Eloquent
 * @author  Anderson Andrade <contato@andersonandra.de>
 */
abstract class BaseRepository extends PBaseRepository implements Serializable
{
	use ComparesVersionsTrait;

	/**
	 * @var array
	 */
	protected $DateFields = [];

	/**
	 * Retrieve all data of repository, paginated
	 *
	 * @param null|int $limit
	 * @param array $columns
	 * @param string $method
	 *
	 * @return mixed
	 */
	public function paginate($limit = null, $columns = ['*'], $method = "paginate")
	{
		$this->applyCriteria();
		$this->applyScope();

		$limit = is_null($limit) ? config('repository.pagination.limit', 15) : $limit;

		if ($limit === 'all') {
			return $this->all($columns, true);
		}

		$results = $this->model->{$method}($limit, $columns);
		$results->appends(app('request')->query());
		$this->resetModel();

		return $this->parserResult($results);
	}

	/**
	 * Save a new entity in repository
	 *
	 * @param array $attributes
	 *
	 * @return mixed
	 * @throws ValidatorException
	 *
	 */
	public function create(array $attributes)
	{
		if (isset($this->DateFields)) {
			foreach ($this->DateFields as $DateField) {
				if (isset($attributes[$DateField])) {
					$attributes[$DateField] = Carbon::parse($attributes[$DateField]);
				}
			}
		}

		if (!is_null($this->validator)) {
			// we should pass data that has been casts by the model
			// to make sure data type are same because validator may need to use
			// this data to compare with data that fetch from database.
			if ($this->versionCompare($this->app->version(), "5.2.*", ">")) {
				$attributes = $this->model->newInstance()->forceFill($attributes)->makeVisible($this->model->getHidden())->toArray();
			} else {
				$model = $this->model->newInstance()->forceFill($attributes);
				$model->makeVisible($this->model->getHidden());
				$attributes = $model->toArray();
			}

			$this->validator->with($attributes)->passesOrFail(ValidatorInterface::RULE_CREATE);
		}

		event(new RepositoryEntityCreating($this, $attributes));

		$model = $this->model->newInstance($attributes);
		$model->save();

		/**
		 * Filling the Model non translated attributes
		 *
		 */
		if (isset($attributes['translations'])) {
			$model->fill($attributes['translations']);
		}
		$model->save();

		$this->resetModel();

		event(new RepositoryEntityCreated($this, $model));

		return $this->parserResult($model);
	}

	/**
	 * Update a entity in repository by id
	 *
	 * @param array $attributes
	 * @param       $id
	 *
	 * @return mixed
	 * @throws ValidatorException
	 *
	 */
	public function update(array $attributes, $id)
	{
		$this->applyScope();

		if (isset($this->DateFields)) {
			foreach ($this->DateFields as $DateField) {
				if (isset($attributes[$DateField])) {
					$attributes[$DateField] = Carbon::parse($attributes[$DateField])->toDateString();
				}
			}
		}

		if (!is_null($this->validator)) {
			// we should pass data that has been casts by the model
			// to make sure data type are same because validator may need to use
			// this data to compare with data that fetch from database.
			$model = $this->model->newInstance();
			$model->setRawAttributes([]);
			$model->setAppends([]);
			if ($this->versionCompare($this->app->version(), "5.2.*", ">")) {
				$attributes = $model->forceFill($attributes)->makeVisible($this->model->getHidden())->toArray();
			} else {
				$model->forceFill($attributes);
				$model->makeVisible($this->model->getHidden());
				$attributes = $model->toArray();
			}

			$this->validator->with($attributes)->setId($id)->passesOrFail(ValidatorInterface::RULE_UPDATE);
		}

		$temporarySkipPresenter = $this->skipPresenter;

		$this->skipPresenter(true);

		$model = $this->model->findOrFail($id);

		event(new RepositoryEntityUpdating($this, $model));

		$model->fill($attributes);
		$model->save();

		/**
		 * Filling the Model translated attributes
		 *
		 */
		if (isset($attributes['translations'])) {
			$model->fill($attributes['translations']);
			$model->save();
		}

		$this->skipPresenter($temporarySkipPresenter);
		$this->resetModel();

		event(new RepositoryEntityUpdated($this, $model));

		return $this->parserResult($model);
	}

	protected function whereIn(string $column, array $array)
	{
		$this->findWhereIn($column, $array);
	}

	protected function whereNotIn(string $column, array $array)
	{
		$this->findWhereNotIn($column, $array);
	}

	/**
	 * @param FilteredRequestInterface $request
	 * @return Collection|AbstractPaginator
	 * @throws RepositoryException
	 */
	public function findAll(FilteredRequestInterface $request)
	{
		foreach ($request->filters() as $criteria) {
			$this->pushCriteria($criteria);
		}

		return $this->paginate();
	}

	public function cursor()
	{
		$this->applyCriteria();
		$this->applyScope();

		$results = $this->model->cursor();

		$this->resetModel();
		$this->resetScope();

		return $this->parserResult($results);
	}

	/** @inheritDoc */
	public function serialize()
	{
		return json_encode([]);
	}

	/** @inheritDoc */
	public function unserialize($serialized)
	{
	}

}
