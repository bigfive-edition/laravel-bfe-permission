<?php

namespace BigFiveEdition\Permission\Repositories\Base;

use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class PaginatedRequestCriteria implements CriteriaInterface
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply($model, RepositoryInterface $repository)
    {
        $perPage = 'all';
        if ($this->request->has('page')) {
            $perPage = $this->request->get(config('repository.criteria.params.perPage', 'per_page'));
        }

        config()->set('repository.pagination.limit', $perPage);

        $olderThan = $this->request->get(config('repository.criteria.params.olderThan', 'olderThan'), null);
        if (! is_null($olderThan)) {
            $model->where('created_at', '<', $olderThan);
        }

        $newerThan = $this->request->get(config('repository.criteria.params.newerThan', 'newerThan'), null);
        if (! is_null($newerThan)) {
            $model->where('created_at', '>', $newerThan);
        }

        return $model;
    }
}
