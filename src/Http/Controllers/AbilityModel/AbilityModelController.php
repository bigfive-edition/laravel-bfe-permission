<?php

namespace BigFiveEdition\Permission\Http\Controllers\AbilityModel;

use BigFiveEdition\Permission\Exceptions\BfeUnauthorizedException;
use BigFiveEdition\Permission\Http\Controllers\BfePermissionBaseController;
use BigFiveEdition\Permission\Http\Requests\AbilityModel\BfePermission_AbilityModel_CreateOneRequest;
use BigFiveEdition\Permission\Http\Requests\AbilityModel\BfePermission_AbilityModel_DeleteOneRequest;
use BigFiveEdition\Permission\Http\Requests\AbilityModel\BfePermission_AbilityModel_GetListRequest;
use BigFiveEdition\Permission\Http\Requests\AbilityModel\BfePermission_AbilityModel_GetOneRequest;
use BigFiveEdition\Permission\Http\Requests\AbilityModel\BfePermission_AbilityModel_UpdateOneRequest;
use BigFiveEdition\Permission\Http\Resources\AbilityModel\BfePermission_AbilityModel_Resource;
use BigFiveEdition\Permission\Http\Resources\AbilityModel\BfePermission_AbilityModel_ResourceCollection;
use BigFiveEdition\Permission\Models\AbilityModel;
use BigFiveEdition\Permission\Repositories\AbilityModelRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

/**
 * @OpenApi\PathItem()
 * Class AbilityModelController
 * @package BigFiveEdition\Permission\Http\Controllers
 */
class AbilityModelController extends BfePermissionBaseController
{

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Listing AbilityModel Resources
	 *
	 * Display a paginated/list of the AbilityModel Resource.
	 *
	 * @param BfePermission_AbilityModel_GetListRequest $request :: Illuminate request object.
	 * @return JsonResponse|mixed
	 *
	 * ---------------- API Documentation ----------------
	 * @throws Exception|Exception
	 * @Operation(id="List AbilityModel Resource", tags={"AbilityModel Mgt"})
	 * @Parameters(factory="BfePermission_AbilityModel_GetListParameters")
	 * @File(name="openapi.json")
	 * ---------------- API Documentation END ------------
	 */
	public function index(BfePermission_AbilityModel_GetListRequest $request)
	{
		if (!Gate::allows('bfe-permission-has-abilities',"read_all_abilitymodel|read_all_owned_abilitymodel")) {
			throw BfeUnauthorizedException::forAbilities("read_all_abilitymodel|read_all_owned_abilitymodel");
		}
		$request->merge([
			'orderBy' => $request->input('orderBy', 'created_at'),
			'sortBy' => $request->input('sortBy', 'desc'),
			'per_page' => $request->input('per_page', 15),
		]);

		//$requestUser = $request->user();
		$with = [];
		$withCounts = [];

		$repository = app(AbilityModelRepository::class);
		$entities = $repository
			->with($with)
			->withCount($withCounts);
		if ($request->modelType() && $request->modelId()) {
			$entities = $entities->scopeQuery(function ($query) use ($request) {
				return $query
					->where(['model_type', $request->modelType()])
					->where(['model_id', $request->modelId()]);
			});
		}
		if ($request->abilityId()) {
			$entities = $entities->scopeQuery(function ($query) use ($request) {
				return $query
					->where(['ability_id', $request->abilityId()]);
			});
		}
		$entities = $entities->paginate($request->get('per_page'));

		//Build API Response
		$data = BfePermission_AbilityModel_ResourceCollection::make($entities);
		$message = 'message';
		$notification = [];
		return $this->successApiResponse($data, $message, $notification);
	}


	/**
	 * Single AbilityModel Resource
	 *
	 * Get a single AbilityModel Resource by id
	 *
	 * @param BfePermission_AbilityModel_GetOneRequest $request
	 * @return JsonResponse|mixed
	 *
	 * ---------------- API Documentation ----------------
	 * @throws Exception|Exception
	 * @Operation(id="Get Single AbilityModel Resource", tags={"AbilityModel Mgt"})
	 * @Parameters(factory="BfePermission_AbilityModel_GetOneParameters")
	 * @File(name="openapi.json")
	 * ---------------- API Documentation END ------------
	 */
	public function show(BfePermission_AbilityModel_GetOneRequest $request)
	{
		if (!Gate::allows('bfe-permission-has-abilities',"read_abilitymodel|read_owned_abilitymodel")) {
			throw BfeUnauthorizedException::forAbilities("read_abilitymodel|read_owned_abilitymodel");
		}

		//$requestUser = $request->user();
		$with = [];
		$withCounts = [];

		$repository = app(AbilityModelRepository::class);
		$entity = $repository
			->with($with)
			->withCount($withCounts);
		if ($request->modelType() && $request->modelId()) {
			$entity = $entity->scopeQuery(function ($query) use ($request) {
				return $query
					->where(['model_type', $request->modelType()])
					->where(['model_id', $request->modelId()]);
			});
		}
		if ($request->abilityId()) {
			$entity = $entity->scopeQuery(function ($query) use ($request) {
				return $query
					->where(['ability_id', $request->abilityId()]);
			});
		}
		$entity = $entity->find($request->id());

		//Build API Response
		$data = BfePermission_AbilityModel_Resource::make($entity);
		$message = 'message';
		$notification = [];
		return $this->successApiResponse($data, $message, $notification);
	}

	/**
	 * Create AbilityModel Resource
	 *
	 * Create new AbilityModel Resource
	 *
	 * @param BfePermission_AbilityModel_CreateOneRequest $request
	 * @return JsonResponse|mixed
	 *
	 * ---------------- API Documentation ----------------
	 * @throws Exception|Exception
	 * @Operation(id="Create AbilityModel Resource", tags={"AbilityModel Mgt"})
	 * @Parameters(factory="BfePermission_AbilityModel_CreateOneParameters")
	 * @File(name="openapi.json")
	 * ---------------- API Documentation END ------------
	 */
	public function store(BfePermission_AbilityModel_CreateOneRequest $request)
	{
		if (!Gate::allows('bfe-permission-has-abilities',"create_abilitymodel")) {
			throw BfeUnauthorizedException::forAbilities("create_abilitymodel");
		}

		//$requestUser = $request->user();
		$with = [];
		$withCounts = [];
		$attributes = $request->only([
			'model_type',
			'model_id',
			'resource_type',
			'resource_id',
			'allowed',
		]);
		$attributes = array_filter($attributes, function ($value) {
			return !is_null($value);
		});
		$attributes['ability_id'] = $request->abilityId();

		$repository = app(AbilityModelRepository::class);
		$entity = $repository->create($attributes);
		$entity = $repository
			->with($with)
			->withCount($withCounts)
			->find($entity->id);

		//Build API Response
		$data = BfePermission_AbilityModel_Resource::make($entity);
		$message = 'message';
		$notification = [];
		return $this->successApiResponse($data, $message, $notification);
	}

	/**
	 * Update AbilityModel Resource
	 *
	 * Update a AbilityModel Resource details
	 *
	 * @param BfePermission_AbilityModel_UpdateOneRequest $request
	 * @return JsonResponse|mixed
	 *
	 * ---------------- API Documentation ----------------
	 * @throws Exception|Exception
	 * @Operation(id="Update AbilityModel Resource", tags={"AbilityModel Mgt"})
	 * @Parameters(factory="BfePermission_AbilityModel_UpdateOneParameters")
	 * @File(name="openapi.json")
	 * ---------------- API Documentation END ------------
	 */
	public function update(BfePermission_AbilityModel_UpdateOneRequest $request)
	{
		if (!Gate::allows('bfe-permission-has-abilities',"update_abilitymodel|update_owned_abilitymodel")) {
			throw BfeUnauthorizedException::forAbilities("update_abilitymodel|update_owned_abilitymodel");
		}

		//$requestUser = $request->user();
		$with = [];
		$withCounts = [];
		$attributes = $request->only([
			'model_type',
			'model_id',
			'resource_type',
			'resource_id',
			'allowed',
		]);
		$attributes = array_filter($attributes, function ($value) {
			return !is_null($value);
		});
		$attributes['ability_id'] = $request->abilityId();

		$repository = app(AbilityModelRepository::class);
		$entity = $repository->update($attributes, $request->id());

		//Build API Response
		$data = BfePermission_AbilityModel_Resource::make($entity);
		$message = 'message';
		$notification = [];
		return $this->successApiResponse($data, $message, $notification);
	}

	/**
	 * Delete AbilityModel Resource
	 *
	 * Delete a AbilityModel Resource instance
	 * @param BfePermission_AbilityModel_DeleteOneRequest $request
	 * @return JsonResponse|mixed
	 *
	 * ---------------- API Documentation ----------------
	 * @throws Exception|Exception
	 * @Operation(id="Delete AbilityModel Resource", tags={"AbilityModel Mgt"})
	 * @Parameters(factory="BfePermission_AbilityModel_DeleteOneParameters")
	 * @File(name="openapi.json")
	 * ---------------- API Documentation END ------------
	 */
	public function destroy(BfePermission_AbilityModel_DeleteOneRequest $request)
	{
		if (!Gate::allows('bfe-permission-has-abilities',"delete_abilitymodel|delete_owned_abilitymodel")) {
			throw BfeUnauthorizedException::forAbilities("delete_abilitymodel|delete_owned_abilitymodel");
		}

		//$requestUser = $request->user();
		$with = [];
		$withCounts = [];

		$repository = app(AbilityModelRepository::class);
		$entity = $repository
			->with($with)
			->withCount($withCounts);
		if ($request->modelType() && $request->modelId()) {
			$entity = $entity->scopeQuery(function ($query) use ($request) {
				return $query
					->where(['model_type', $request->modelType()])
					->where(['model_id', $request->modelId()]);
			});
		}
		if ($request->abilityId()) {
			$entity = $entity->scopeQuery(function ($query) use ($request) {
				return $query
					->where(['ability_id', $request->abilityId()]);
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
