<?php

namespace BigFiveEdition\Permission\Repositories;

use BigFiveEdition\Permission\Models\AbilityModel;
use BigFiveEdition\Permission\Repositories\Base\BaseRepository;
use BigFiveEdition\Permission\Repositories\Base\PaginatedRequestCriteria;
use BigFiveEdition\Permission\Repositories\Base\RequestCriteria;

class TeamModelRepository extends BaseRepository
{
    protected $fieldSearchable = [
	    'team_id',
	    'model_type',
	    'model_id',
	    'attribute',
    ];

    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
        $this->pushCriteria(app(PaginatedRequestCriteria::class));
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return AbilityModel::class;
    }
}
