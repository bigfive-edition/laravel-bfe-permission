<?php

namespace BigFiveEdition\Permission\Http\Controllers\RoleModel;

use BigFiveEdition\Permission\Exceptions\BfeUnauthorizedException;
use BigFiveEdition\Permission\Http\Controllers\BfePermissionBaseController;
use BigFiveEdition\Permission\Http\Requests\RoleModel\BfePermission_RoleModel_CreateOneRequest;
use BigFiveEdition\Permission\Http\Requests\RoleModel\BfePermission_RoleModel_DeleteOneRequest;
use BigFiveEdition\Permission\Http\Requests\RoleModel\BfePermission_RoleModel_GetListRequest;
use BigFiveEdition\Permission\Http\Requests\RoleModel\BfePermission_RoleModel_GetOneRequest;
use BigFiveEdition\Permission\Http\Requests\RoleModel\BfePermission_RoleModel_UpdateOneRequest;
use BigFiveEdition\Permission\Http\Resources\RoleModel\BfePermission_RoleModel_Resource;
use BigFiveEdition\Permission\Http\Resources\RoleModel\BfePermission_RoleModel_ResourceCollection;
use BigFiveEdition\Permission\Models\RoleModel;
use BigFiveEdition\Permission\Repositories\RoleModelRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

/**
 * @OpenApi\PathItem()
 * Class RoleModelController
 * @package BigFiveEdition\Permission\Http\Controllers
 */
class RoleModelController extends BfePermissionBaseController
{

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Listing RoleModel Resources
	 *
	 * Display a paginated/list of the RoleModel Resource.
	 *
	 * @param BfePermission_RoleModel_GetListRequest $request :: Illuminate request object.
	 * @return JsonResponse|mixed
	 *
	 * ---------------- API Documentation ----------------
	 * @throws Exception|Exception
	 * @Operation(id="List RoleModel Resource", tags={"RoleModel Mgt"})
	 * @Parameters(factory="BfePermission_RoleModel_GetListParameters")
	 * @File(name="openapi.json")
	 * ---------------- API Documentation END ------------
	 */
	public function index(BfePermission_RoleModel_GetListRequest $request)
	{
		if (!Gate::allows('bfe-permission-has-abilities',"read_all_rolemodel|read_all_owned_rolemodel")) {
			throw BfeUnauthorizedException::forAbilities("read_all_rolemodel|read_all_owned_rolemodel");
		}
		$request->merge([
			'orderBy' => $request->input('orderBy', 'created_at'),
			'sortBy' => $request->input('sortBy', 'desc'),
			'per_page' => $request->input('per_page', 15),
		]);

		//$requestUser = $request->user();
		$with = [];
		$withCounts = [];

		$repository = app(RoleModelRepository::class);
		$entities = $repository
			->with($with)
			->withCount($withCounts);
		if ($request->modelType() && $request->modelId()) {
			$entities = $entities->scopeQuery(function ($query) use ($request) {
				return $query
					->where('model_type', $request->modelType())
					->where('model_id', $request->modelId());
			});
		}
		if ($request->roleId()) {
			$entities = $entities->scopeQuery(function ($query) use ($request) {
				return $query
					->where('role_id', $request->roleId());
			});
		}
		$entities = $entities->paginate($request->get('per_page'));

		//Build API Response
		$data = BfePermission_RoleModel_ResourceCollection::make($entities);
		$message = 'message';
		$notification = [];
		return $this->successApiResponse($data, $message, $notification);
	}


	/**
	 * Single RoleModel Resource
	 *
	 * Get a single RoleModel Resource by id
	 *
	 * @param BfePermission_RoleModel_GetOneRequest $request
	 * @return JsonResponse|mixed
	 *
	 * ---------------- API Documentation ----------------
	 * @throws Exception|Exception
	 * @Operation(id="Get Single RoleModel Resource", tags={"RoleModel Mgt"})
	 * @Parameters(factory="BfePermission_RoleModel_GetOneParameters")
	 * @File(name="openapi.json")
	 * ---------------- API Documentation END ------------
	 */
	public function show(BfePermission_RoleModel_GetOneRequest $request)
	{
		if (!Gate::allows('bfe-permission-has-abilities',"read_rolemodel|read_owned_rolemodel")) {
			throw BfeUnauthorizedException::forAbilities("read_rolemodel|read_owned_rolemodel");
		}

		//$requestUser = $request->user();
		$with = [];
		$withCounts = [];

		$repository = app(RoleModelRepository::class);
		$entity = $repository
			->with($with)
			->withCount($withCounts);
		if ($request->modelType() && $request->modelId()) {
			$entity = $entity->scopeQuery(function ($query) use ($request) {
				return $query
					->where('model_type', $request->modelType())
					->where('model_id', $request->modelId());
			});
		}
		if ($request->roleId()) {
			$entity = $entity->scopeQuery(function ($query) use ($request) {
				return $query
					->where('role_id', $request->roleId());
			});
		}
		$entity = $entity->find($request->id());

		//Build API Response
		$data = BfePermission_RoleModel_Resource::make($entity);
		$message = 'message';
		$notification = [];
		return $this->successApiResponse($data, $message, $notification);
	}

	/**
	 * Create RoleModel Resource
	 *
	 * Create new RoleModel Resource
	 *
	 * @param BfePermission_RoleModel_CreateOneRequest $request
	 * @return JsonResponse|mixed
	 *
	 * ---------------- API Documentation ----------------
	 * @throws Exception|Exception
	 * @Operation(id="Create RoleModel Resource", tags={"RoleModel Mgt"})
	 * @Parameters(factory="BfePermission_RoleModel_CreateOneParameters")
	 * @File(name="openapi.json")
	 * ---------------- API Documentation END ------------
	 */
	public function store(BfePermission_RoleModel_CreateOneRequest $request)
	{
		if (!Gate::allows('bfe-permission-has-abilities',"create_rolemodel")) {
			throw BfeUnauthorizedException::forAbilities("create_rolemodel");
		}

		//$requestUser = $request->user();
		$with = [];
		$withCounts = [];
		$attributes = $request->only([
			'model_type',
			'model_id',
		]);
		$attributes = array_filter($attributes, function ($value) {
			return !is_null($value);
		});
		$attributes['role_id'] = $request->roleId();

		$repository = app(RoleModelRepository::class);
		$entity = $repository->create($attributes);
		$entity = $repository
			->with($with)
			->withCount($withCounts)
			->find($entity->id);

		//Build API Response
		$data = BfePermission_RoleModel_Resource::make($entity);
		$message = 'message';
		$notification = [];
		return $this->successApiResponse($data, $message, $notification);
	}

	/**
	 * Update RoleModel Resource
	 *
	 * Update a RoleModel Resource details
	 *
	 * @param BfePermission_RoleModel_UpdateOneRequest $request
	 * @return JsonResponse|mixed
	 *
	 * ---------------- API Documentation ----------------
	 * @throws Exception|Exception
	 * @Operation(id="Update RoleModel Resource", tags={"RoleModel Mgt"})
	 * @Parameters(factory="BfePermission_RoleModel_UpdateOneParameters")
	 * @File(name="openapi.json")
	 * ---------------- API Documentation END ------------
	 */
	public function update(BfePermission_RoleModel_UpdateOneRequest $request)
	{
		if (!Gate::allows('bfe-permission-has-abilities',"update_rolemodel|update_owned_rolemodel")) {
			throw BfeUnauthorizedException::forAbilities("update_rolemodel|update_owned_rolemodel");
		}

		//$requestUser = $request->user();
		$with = [];
		$withCounts = [];
		$attributes = $request->only([
			'model_type',
			'model_id',
		]);
		$attributes = array_filter($attributes, function ($value) {
			return !is_null($value);
		});
		$attributes['role_id'] = $request->roleId();

		$repository = app(RoleModelRepository::class);
		$entity = $repository->update($attributes, $request->id());

		//Build API Response
		$data = BfePermission_RoleModel_Resource::make($entity);
		$message = 'message';
		$notification = [];
		return $this->successApiResponse($data, $message, $notification);
	}

	/**
	 * Delete RoleModel Resource
	 *
	 * Delete a RoleModel Resource instance
	 * @param BfePermission_RoleModel_DeleteOneRequest $request
	 * @return JsonResponse|mixed
	 *
	 * ---------------- API Documentation ----------------
	 * @throws Exception|Exception
	 * @Operation(id="Delete RoleModel Resource", tags={"RoleModel Mgt"})
	 * @Parameters(factory="BfePermission_RoleModel_DeleteOneParameters")
	 * @File(name="openapi.json")
	 * ---------------- API Documentation END ------------
	 */
	public function destroy(BfePermission_RoleModel_DeleteOneRequest $request)
	{
		if (!Gate::allows('bfe-permission-has-abilities',"delete_rolemodel|delete_owned_rolemodel")) {
			throw BfeUnauthorizedException::forAbilities("delete_rolemodel|delete_owned_rolemodel");
		}

		//$requestUser = $request->user();
		$with = [];
		$withCounts = [];

		$repository = app(RoleModelRepository::class);
		$entity = $repository
			->with($with)
			->withCount($withCounts);
		if ($request->modelType() && $request->modelId()) {
			$entity = $entity->scopeQuery(function ($query) use ($request) {
				return $query
					->where('model_type', $request->modelType())
					->where('model_id', $request->modelId());
			});
		}
		if ($request->roleId()) {
			$entity = $entity->scopeQuery(function ($query) use ($request) {
				return $query
					->where('role_id', $request->roleId());
			});
		}
		$entity = $entity->find($request->id());
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
