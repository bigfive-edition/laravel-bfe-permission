<?php

namespace BigFiveEdition\Permission\Repositories;

use BigFiveEdition\Permission\Models\Ability;
use BigFiveEdition\Permission\Models\Team;
use BigFiveEdition\Permission\Repositories\Base\BaseRepository;
use BigFiveEdition\Permission\Repositories\Base\PaginatedRequestCriteria;
use BigFiveEdition\Permission\Repositories\Base\RequestCriteria;

class TeamRepository extends BaseRepository
{
    protected $fieldSearchable = [
	    'slug',
	    'name',
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
        return Team::class;
    }
}
