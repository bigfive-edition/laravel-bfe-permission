<?php

namespace BigFiveEdition\Permission\Repositories\Base;

use Prettus\Repository\Contracts\CriteriaInterface;

interface FilteredRequestInterface
{
    /** @return CriteriaInterface[] */
    public function filters(): array;
}
