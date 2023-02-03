<?php


namespace BigFiveEdition\Permission\Http\Resources;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BaseJsonResourceCollection extends JsonResource
{
	public $collects = BaseJsonResource::class;

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

