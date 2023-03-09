<?php

namespace BigFiveEdition\Permission\Http\Controllers\Ability;

use BigFiveEdition\Permission\Http\Controllers\BfePermissionBaseController;
use BigFiveEdition\Permission\Http\Requests\Ability\BfePermission_Ability_CreateOneRequest;
use BigFiveEdition\Permission\Http\Requests\Ability\BfePermission_Ability_DeleteOneRequest;
use BigFiveEdition\Permission\Http\Requests\Ability\BfePermission_Ability_GetListRequest;
use BigFiveEdition\Permission\Http\Requests\Ability\BfePermission_Ability_GetOneRequest;
use BigFiveEdition\Permission\Http\Requests\Ability\BfePermission_Ability_UpdateOneRequest;
use BigFiveEdition\Permission\Http\Resources\Ability\BfePermission_Ability_Resource;
use BigFiveEdition\Permission\Http\Resources\Ability\BfePermission_Ability_ResourceCollection;
use Exception;
use Illuminate\Http\JsonResponse;

/**
 * @OpenApi\PathItem()
 * Class AbilityController
 * @package BigFiveEdition\Permission\Http\Controllers
 */
class AbilityController extends BfePermissionBaseController
{

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Listing Ability Resources
	 *
	 * Display a paginated/list of the Ability Resource.
	 *
	 * @param BfePermission_Ability_GetListRequest $request :: Illuminate request object.
	 * @return JsonResponse|mixed
	 *
	 * ---------------- API Documentation ----------------
	 * @throws Exception|Exception
	 * @Operation(id="List Ability Resource", tags={"Ability Mgt"})
	 * @Parameters(factory="BfePermission_Ability_GetListParameters")
	 * @File(name="openapi.json")
	 * ---------------- API Documentation END ------------
	 */
	public function index(BfePermission_Ability_GetListRequest $request)
	{
		//$requestUser = $request->user();

		//Build API Response
		$data = BfePermission_Ability_ResourceCollection::make(['value 1', 'value 2']);
		$message = 'message';
		$notification = [];
		return $this->successApiResponse($data, $message, $notification);
	}


	/**
	 * Single Ability Resource
	 *
	 * Get a single Ability Resource by id
	 *
	 * @param BfePermission_Ability_GetOneRequest $request
	 * @return JsonResponse|mixed
	 *
	 * ---------------- API Documentation ----------------
	 * @throws Exception|Exception
	 * @Operation(id="Get Single Ability Resource", tags={"Ability Mgt"})
	 * @Parameters(factory="BfePermission_Ability_GetOneParameters")
	 * @File(name="openapi.json")
	 * ---------------- API Documentation END ------------
	 */
	public function show(BfePermission_Ability_GetOneRequest $request)
	{
		//$requestUser = $request->user();

		//Build API Response
		$data = BfePermission_Ability_Resource::make([$request->id() => 'value 1']);
		$message = 'message';
		$notification = [];
		return $this->successApiResponse($data, $message, $notification);
	}

	/**
	 * Create Ability Resource
	 *
	 * Create new Ability Resource
	 *
	 * @param BfePermission_Ability_CreateOneRequest $request
	 * @return JsonResponse|mixed
	 *
	 * ---------------- API Documentation ----------------
	 * @throws Exception|Exception
	 * @Operation(id="Create Ability Resource", tags={"Ability Mgt"})
	 * @Parameters(factory="BfePermission_Ability_CreateOneParameters")
	 * @File(name="openapi.json")
	 * ---------------- API Documentation END ------------
	 */
	public function store(BfePermission_Ability_CreateOneRequest $request)
	{
		//$requestUser = $request->user();

		//Build API Response
		$data = BfePermission_Ability_Resource::make($request->all());
		$message = 'message';
		$notification = [];
		return $this->successApiResponse($data, $message, $notification);
	}

	/**
	 * Update Ability Resource
	 *
	 * Update a Ability Resource details
	 *
	 * @param BfePermission_Ability_UpdateOneRequest $request
	 * @return JsonResponse|mixed
	 *
	 * ---------------- API Documentation ----------------
	 * @throws Exception|Exception
	 * @Operation(id="Update Ability Resource", tags={"Ability Mgt"})
	 * @Parameters(factory="BfePermission_Ability_UpdateOneParameters")
	 * @File(name="openapi.json")
	 * ---------------- API Documentation END ------------
	 */
	public function update(BfePermission_Ability_UpdateOneRequest $request)
	{
		//$requestUser = $request->user();

		//Build API Response
		$data = BfePermission_Ability_Resource::make([$request->id() => $request->all()]);
		$message = 'message';
		$notification = [];
		return $this->successApiResponse($data, $message, $notification);
	}

	/**
	 * Delete Ability Resource
	 *
	 * Delete a Ability Resource instance
	 * @param BfePermission_Ability_DeleteOneRequest $request
	 * @return JsonResponse|mixed
	 *
	 * ---------------- API Documentation ----------------
	 * @throws Exception|Exception
	 * @Operation(id="Delete Ability Resource", tags={"Ability Mgt"})
	 * @Parameters(factory="BfePermission_Ability_DeleteOneParameters")
	 * @File(name="openapi.json")
	 * ---------------- API Documentation END ------------
	 */
	public function destroy(BfePermission_Ability_DeleteOneRequest $request)
	{
		//$requestUser = $request->user();

		//Build API Response
		$data = [
			'deleted' => $request->id() != null,
			'data' => $request->id()
		];
		$message = 'message';
		$notification = [];
		return $this->successApiResponse($data, $message, $notification);
	}
}
