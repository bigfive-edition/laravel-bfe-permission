<?php

namespace BigFiveEdition\Permission\Http\Controllers\Test;

use BigFiveEdition\Permission\Http\Controllers\BfePermissionBaseController;
use BigFiveEdition\Permission\Http\Requests\Test\BfePermission_Test_CreateOneRequest;
use BigFiveEdition\Permission\Http\Requests\Test\BfePermission_Test_DeleteOneRequest;
use BigFiveEdition\Permission\Http\Requests\Test\BfePermission_Test_GetListRequest;
use BigFiveEdition\Permission\Http\Requests\Test\BfePermission_Test_GetOneRequest;
use BigFiveEdition\Permission\Http\Requests\Test\BfePermission_Test_UpdateOneRequest;
use BigFiveEdition\Permission\Http\Resources\Test\BfePermission_Test_Resource;
use BigFiveEdition\Permission\Http\Resources\Test\BfePermission_Test_ResourceCollection;
use Exception;
use Illuminate\Http\JsonResponse;

/**
 * @OpenApi\PathItem()
 * Class TestController
 * @package BigFiveEdition\Permission\Http\Controllers
 */
class TestController extends BfePermissionBaseController
{

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Listing Test Resources
	 *
	 * Display a paginated/list of the Test Resource.
	 *
	 * @param BfePermission_Test_GetListRequest $request :: Illuminate request object.
	 * @return JsonResponse|mixed
	 *
	 * ---------------- API Documentation ----------------
	 * @throws Exception|Exception
	 * @Operation(id="List Test Resource", tags={"Test Mgt"})
	 * @Parameters(factory="BfePermission_Test_GetListParameters")
	 * @File(name="openapi.json")
	 * ---------------- API Documentation END ------------
	 */
	public function index(BfePermission_Test_GetListRequest $request)
	{
		$request->merge([
			'orderBy' => $request->input('orderBy', 'created_at'),
			'sortBy' => $request->input('sortBy', 'desc'),
			'per_page' => $request->input('per_page', 15),
		]);
		//$requestUser = $request->user();
		$with = [];
		$withCounts = [];

		//Build API Response
		$data = BfePermission_Test_ResourceCollection::make(['value 1', 'value 2']);
		$message = 'message';
		$notification = [];
		return $this->successApiResponse($data, $message, $notification);
	}


	/**
	 * Single Test Resource
	 *
	 * Get a single Test Resource by id
	 *
	 * @param BfePermission_Test_GetOneRequest $request
	 * @return JsonResponse|mixed
	 *
	 * ---------------- API Documentation ----------------
	 * @throws Exception|Exception
	 * @Operation(id="Get Single Test Resource", tags={"Test Mgt"})
	 * @Parameters(factory="BfePermission_Test_GetOneParameters")
	 * @File(name="openapi.json")
	 * ---------------- API Documentation END ------------
	 */
	public function show(BfePermission_Test_GetOneRequest $request)
	{
		//$requestUser = $request->user();
		$with = [];
		$withCounts = [];

		//Build API Response
		$data = BfePermission_Test_Resource::make([$request->id() => 'value 1']);
		$message = 'message';
		$notification = [];
		return $this->successApiResponse($data, $message, $notification);
	}

	/**
	 * Create Test Resource
	 *
	 * Create new Test Resource
	 *
	 * @param BfePermission_Test_CreateOneRequest $request
	 * @return JsonResponse|mixed
	 *
	 * ---------------- API Documentation ----------------
	 * @throws Exception|Exception
	 * @Operation(id="Create Test Resource", tags={"Test Mgt"})
	 * @Parameters(factory="BfePermission_Test_CreateOneParameters")
	 * @File(name="openapi.json")
	 * ---------------- API Documentation END ------------
	 */
	public function store(BfePermission_Test_CreateOneRequest $request)
	{
		//$requestUser = $request->user();
		$with = [];
		$withCounts = [];

		//Build API Response
		$data = BfePermission_Test_Resource::make($request->all());
		$message = 'message';
		$notification = [];
		return $this->successApiResponse($data, $message, $notification);
	}

	/**
	 * Update Test Resource
	 *
	 * Update a Test Resource details
	 *
	 * @param BfePermission_Test_UpdateOneRequest $request
	 * @return JsonResponse|mixed
	 *
	 * ---------------- API Documentation ----------------
	 * @throws Exception|Exception
	 * @Operation(id="Update Test Resource", tags={"Test Mgt"})
	 * @Parameters(factory="BfePermission_Test_UpdateOneParameters")
	 * @File(name="openapi.json")
	 * ---------------- API Documentation END ------------
	 */
	public function update(BfePermission_Test_UpdateOneRequest $request)
	{
		//$requestUser = $request->user();
		$with = [];
		$withCounts = [];

		//Build API Response
		$data = BfePermission_Test_Resource::make([$request->id() => $request->all()]);
		$message = 'message';
		$notification = [];
		return $this->successApiResponse($data, $message, $notification);
	}

	/**
	 * Delete Test Resource
	 *
	 * Delete a Test Resource instance
	 * @param BfePermission_Test_DeleteOneRequest $request
	 * @return JsonResponse|mixed
	 *
	 * ---------------- API Documentation ----------------
	 * @throws Exception|Exception
	 * @Operation(id="Delete Test Resource", tags={"Test Mgt"})
	 * @Parameters(factory="BfePermission_Test_DeleteOneParameters")
	 * @File(name="openapi.json")
	 * ---------------- API Documentation END ------------
	 */
	public function destroy(BfePermission_Test_DeleteOneRequest $request)
	{
		//$requestUser = $request->user();
		$with = [];
		$withCounts = [];

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
