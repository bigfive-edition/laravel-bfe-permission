<?php

namespace BigFiveEdition\Permission\Http\Resources\TeamModel;

use BigFiveEdition\Permission\Http\Resources\BaseJsonResource;
use Illuminate\Http\Request;

class BfePermission_TeamModel_Resource extends BaseJsonResource
{
	public function __construct($resource)
	{
		parent::__construct($resource);
	}

	/**
	 * Transform the resource into an array.
	 *
	 * @param Request $request
	 * @return mixed
	 */
	public function toArray($request)
	{
		$resource = $this->resource;
		$pResponse = array_merge(parent::toArray($request), $this->additional ?? []);
		$response = [
		];
		$response = array_merge($pResponse, $response);

		return $response;
	}
}
