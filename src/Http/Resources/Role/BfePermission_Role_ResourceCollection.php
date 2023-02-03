<?php

namespace BigFiveEdition\Permission\Http\Resources\Role;

use BigFiveEdition\Permission\Http\Resources\BaseJsonResourceCollection;
use Illuminate\Http\Request;

class BfePermission_Role_ResourceCollection extends BaseJsonResourceCollection
{
	public $collects = BfePermission_Role_Resource::class;

	public $additional = [
	];

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
	/*public function toArray($request)
	{
		$collection = $this->collection;
		$pResponse = array_merge(parent::toArray($request), $this->additional  ?? []);
		$response = [
		];
		$response = array_merge($pResponse, $response);

		return $response;
	}*/
}
