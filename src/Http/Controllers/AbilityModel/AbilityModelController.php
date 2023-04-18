<?php

namespace BigFiveEdition\Permission\Http\Controllers\AbilityModel;

use BigFiveEdition\Permission\Http\Controllers\BfePermissionBaseController;
use BigFiveEdition\Permission\Http\Requests\AbilityModel\BfePermission_AbilityModel_CreateOneRequest;
use BigFiveEdition\Permission\Http\Requests\AbilityModel\BfePermission_AbilityModel_DeleteOneRequest;
use BigFiveEdition\Permission\Http\Requests\AbilityModel\BfePermission_AbilityModel_GetListRequest;
use BigFiveEdition\Permission\Http\Requests\AbilityModel\BfePermission_AbilityModel_GetOneRequest;
use BigFiveEdition\Permission\Http\Requests\AbilityModel\BfePermission_AbilityModel_UpdateOneRequest;
use BigFiveEdition\Permission\Http\Resources\AbilityModel\BfePermission_AbilityModel_Resource;
use BigFiveEdition\Permission\Http\Resources\AbilityModel\BfePermission_AbilityModel_ResourceCollection;
use BigFiveEdition\Permission\Models\AbilityModel;
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
			abort(403);
		}

		//$requestUser = $request->user();
		$with = array_merge([
		], $request->with());
		$withCounts = array_merge([
		], $request->withCount());
		$entities = AbilityModel::query()
			->with($with)
			->withCount($withCounts)
			->where('ability_id', $request->abilityId())
			->paginate($request->get('per_page'));

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
			abort(403);
		}

		//$requestUser = $request->user();
		$with = array_merge([
		], $request->with());
		$withCounts = array_merge([
		], $request->withCount());
		$entity = AbilityModel::query()
			->with($with)
			->withCount($withCounts)
			->where('ability_id', $request->abilityId())
			->findOrFail($request->id());

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
			abort(403);
		}

		//$requestUser = $request->user();
		$with = array_merge([
		], $request->with());
		$withCounts = array_merge([
		], $request->withCount());
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

		$entity = AbilityModel::create($attributes);
		$entity = AbilityModel::query()
			->with($with)
			->withCount($withCounts)
			->findOrFail($entity->id);

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
			abort(403);
		}

		//$requestUser = $request->user();
		$with = array_merge([
		], $request->with());
		$withCounts = array_merge([
		], $request->withCount());
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

		$entity = AbilityModel::query()
			->with($with)
			->withCount($withCounts)
			->where('ability_id', $request->abilityId())
			->findOrFail($request->id());
		$entity->fill($attributes);
		$entity->save();

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
			abort(403);
		}

		//$requestUser = $request->user();
		$with = array_merge([
		], $request->with());
		$withCounts = array_merge([
		], $request->withCount());
		$entity = AbilityModel::query()
			->with($with)
			->withCount($withCounts)
			->where('ability_id', $request->abilityId())
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
