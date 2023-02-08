<?php

namespace BigFiveEdition\Permission\Http\Controllers\Role;

use BigFiveEdition\Permission\Http\Controllers\BfePermissionBaseController;
use BigFiveEdition\Permission\Http\Requests\Role\BfePermission_Role_CreateOneRequest;
use BigFiveEdition\Permission\Http\Requests\Role\BfePermission_Role_DeleteOneRequest;
use BigFiveEdition\Permission\Http\Requests\Role\BfePermission_Role_GetListRequest;
use BigFiveEdition\Permission\Http\Requests\Role\BfePermission_Role_GetOneRequest;
use BigFiveEdition\Permission\Http\Requests\Role\BfePermission_Role_UpdateOneRequest;
use BigFiveEdition\Permission\Http\Resources\Role\BfePermission_Role_Resource;
use BigFiveEdition\Permission\Http\Resources\Role\BfePermission_Role_ResourceCollection;
use BigFiveEdition\Permission\Models\Role;
use BigFiveEdition\Permission\Models\Team;
use Exception;
use Illuminate\Http\JsonResponse;

/**
 * @OpenApi\PathItem()
 * Class RoleController
 * @package BigFiveEdition\Permission\Http\Controllers
 */
class RoleController extends BfePermissionBaseController
{

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Listing Role Resources
	 *
	 * Display a paginated/list of the Role Resource.
	 *
	 * @param BfePermission_Role_GetListRequest $request :: Illuminate request object.
	 * @return JsonResponse|mixed
	 *
	 * ---------------- API Documentation ----------------
	 * @throws Exception|Exception
	 * @Operation(id="List Role Resource", tags={"Role Mgt"})
	 * @Parameters(factory="BfePermission_Role_GetListParameters")
	 * @File(name="openapi.json")
	 * ---------------- API Documentation END ------------
	 */
	public function index(BfePermission_Role_GetListRequest $request)
	{
		//$requestUser = $request->user();

		$entities = Role::query()
			->paginate($request->get('per_page'));

		//Build API Response
		$data = BfePermission_Role_ResourceCollection::make($entities);
		$message = 'message';
		$notification = [];
		return $this->successApiResponse($data, $message, $notification);
	}


	/**
	 * Single Role Resource
	 *
	 * Get a single Role Resource by id
	 *
	 * @param BfePermission_Role_GetOneRequest $request
	 * @return JsonResponse|mixed
	 *
	 * ---------------- API Documentation ----------------
	 * @throws Exception|Exception
	 * @Operation(id="Get Single Role Resource", tags={"Role Mgt"})
	 * @Parameters(factory="BfePermission_Role_GetOneParameters")
	 * @File(name="openapi.json")
	 * ---------------- API Documentation END ------------
	 */
	public function show(BfePermission_Role_GetOneRequest $request)
	{
		//$requestUser = $request->user();
		$entity =  Role::query()
			->findOrFail($request->id());

		//Build API Response
		$data = BfePermission_Role_Resource::make($entity);
		$message = 'message';
		$notification = [];
		return $this->successApiResponse($data, $message, $notification);
	}

	/**
	 * Create Role Resource
	 *
	 * Create new Role Resource
	 *
	 * @param BfePermission_Role_CreateOneRequest $request
	 * @return JsonResponse|mixed
	 *
	 * ---------------- API Documentation ----------------
	 * @throws Exception|Exception
	 * @Operation(id="Create Role Resource", tags={"Role Mgt"})
	 * @Parameters(factory="BfePermission_Role_CreateOneParameters")
	 * @File(name="openapi.json")
	 * ---------------- API Documentation END ------------
	 */
	public function store(BfePermission_Role_CreateOneRequest $request)
	{
		//$requestUser = $request->user();
		$attributes = $request->only([
			'slug',
			'name',
		]);
		$attributes = array_filter($attributes, function ($value) {
			return !is_null($value);
		});

		$entity = Role::create($attributes);

		//Build API Response
		$data = BfePermission_Role_Resource::make($entity);
		$message = 'message';
		$notification = [];
		return $this->successApiResponse($data, $message, $notification);
	}

	/**
	 * Update Role Resource
	 *
	 * Update a Role Resource details
	 *
	 * @param BfePermission_Role_UpdateOneRequest $request
	 * @return JsonResponse|mixed
	 *
	 * ---------------- API Documentation ----------------
	 * @throws Exception|Exception
	 * @Operation(id="Update Role Resource", tags={"Role Mgt"})
	 * @Parameters(factory="BfePermission_Role_UpdateOneParameters")
	 * @File(name="openapi.json")
	 * ---------------- API Documentation END ------------
	 */
	public function update(BfePermission_Role_UpdateOneRequest $request)
	{
		//$requestUser = $request->user();
		$attributes = $request->only([
			'slug',
			'name',
		]);
		$attributes = array_filter($attributes, function ($value) {
			return !is_null($value);
		});

		$entity = Role::query()
			->findOrFail($request->id());
		$entity->fill($attributes);
		$entity->save();

		//Build API Response
		$data = BfePermission_Role_Resource::make($entity);
		$message = 'message';
		$notification = [];
		return $this->successApiResponse($data, $message, $notification);
	}

	/**
	 * Delete Role Resource
	 *
	 * Delete a Role Resource instance
	 * @param BfePermission_Role_DeleteOneRequest $request
	 * @return JsonResponse|mixed
	 *
	 * ---------------- API Documentation ----------------
	 * @throws Exception|Exception
	 * @Operation(id="Delete Role Resource", tags={"Role Mgt"})
	 * @Parameters(factory="BfePermission_Role_DeleteOneParameters")
	 * @File(name="openapi.json")
	 * ---------------- API Documentation END ------------
	 */
	public function destroy(BfePermission_Role_DeleteOneRequest $request)
	{
		//$requestUser = $request->user();
		$entity = Role::query()
		->findOrFail($request->id());
		$deleted = $entity->delete();

		//Build API Response
		$data = [
			'deleted' => $deleted,
		];
		$message = 'message';
		$notification = [];
		return $this->successApiResponse($data, $message, $notification);
	}
}
