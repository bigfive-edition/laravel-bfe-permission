<?php

namespace BigFiveEdition\Permission\Http\Controllers\Role;

use BigFiveEdition\Permission\Exceptions\BfeUnauthorizedException;
use BigFiveEdition\Permission\Http\Controllers\BfePermissionBaseController;
use BigFiveEdition\Permission\Http\Requests\Role\BfePermission_Role_CreateOneRequest;
use BigFiveEdition\Permission\Http\Requests\Role\BfePermission_Role_DeleteOneRequest;
use BigFiveEdition\Permission\Http\Requests\Role\BfePermission_Role_GetListRequest;
use BigFiveEdition\Permission\Http\Requests\Role\BfePermission_Role_GetOneRequest;
use BigFiveEdition\Permission\Http\Requests\Role\BfePermission_Role_UpdateOneRequest;
use BigFiveEdition\Permission\Http\Resources\Role\BfePermission_Role_Resource;
use BigFiveEdition\Permission\Http\Resources\Role\BfePermission_Role_ResourceCollection;
use BigFiveEdition\Permission\Models\Role;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

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
		if (!Gate::allows('bfe-permission-has-abilities',"read_all_role|read_all_owned_role")) {
			throw BfeUnauthorizedException::forAbilities("read_all_role|read_all_owned_role");
		}

		//$requestUser = $request->user();
		$with = array_merge([
		], $request->with());
		$withCounts = array_merge([
		], $request->withCount());

		$entities = Role::query()
			->with($with)
			->withCount($withCounts)
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
		if (!Gate::allows('bfe-permission-has-abilities',"read_role|read_owned_role")) {
			throw BfeUnauthorizedException::forAbilities("read_role|read_owned_role");
		}

		//$requestUser = $request->user();
		$with = array_merge([
		], $request->with());
		$withCounts = array_merge([
		], $request->withCount());
		$entity = Role::query()
			->with($with)
			->withCount($withCounts)
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
		if (!Gate::allows('bfe-permission-has-abilities',"create_role")) {
			throw BfeUnauthorizedException::forAbilities("create_role");
		}

		//$requestUser = $request->user();
		$with = array_merge([
		], $request->with());
		$withCounts = array_merge([
		], $request->withCount());
		$attributes = $request->only([
			'slug',
			'name',
		]);
		$attributes = array_filter($attributes, function ($value) {
			return !is_null($value);
		});

		$entity = Role::create($attributes);
		$entity = Role::query()
			->with($with)
			->withCount($withCounts)
			->findOrFail($entity->id);

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
		if (!Gate::allows('bfe-permission-has-abilities',"update_role|update_owned_role")) {
			throw BfeUnauthorizedException::forAbilities("update_role|update_owned_role");
		}

		//$requestUser = $request->user();
		$with = array_merge([
		], $request->with());
		$withCounts = array_merge([
		], $request->withCount());
		$attributes = $request->only([
			'slug',
			'name',
		]);
		$attributes = array_filter($attributes, function ($value) {
			return !is_null($value);
		});

		$entity = Role::query()
			->with($with)
			->withCount($withCounts)
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
		if (!Gate::allows('bfe-permission-has-abilities',"delete_role|delete_owned_role")) {
			throw BfeUnauthorizedException::forAbilities("delete_role|delete_owned_role");
		}

		//$requestUser = $request->user();
		$with = array_merge([
		], $request->with());
		$withCounts = array_merge([
		], $request->withCount());
		$entity = Role::query()
			->with($with)
			->withCount($withCounts)
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
