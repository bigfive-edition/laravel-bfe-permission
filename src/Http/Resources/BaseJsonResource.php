<?php


namespace BigFiveEdition\Permission\Http\Resources;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BaseJsonResource extends JsonResource
{
//	public static $wrap = null;

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
	public function toArray($request)
	{
		$resource = $this->resource;
		$pResponse = array_merge(parent::toArray($request), $this->additional ?? []);
		$response = [
		];
		$response = array_merge($pResponse, $response);

		unset($response['created_at']);
		unset($response['updated_at']);
		unset($response['deleted_at']);

		return $response;
	}
}

