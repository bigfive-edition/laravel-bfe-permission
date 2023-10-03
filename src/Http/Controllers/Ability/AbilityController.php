<?php

namespace BigFiveEdition\Permission\Http\Controllers\Ability;

use BigFiveEdition\Permission\Exceptions\BfeUnauthorizedException;
use BigFiveEdition\Permission\Http\Controllers\BfePermissionBaseController;
use BigFiveEdition\Permission\Http\Requests\Ability\BfePermission_Ability_CreateOneRequest;
use BigFiveEdition\Permission\Http\Requests\Ability\BfePermission_Ability_DeleteOneRequest;
use BigFiveEdition\Permission\Http\Requests\Ability\BfePermission_Ability_GetListRequest;
use BigFiveEdition\Permission\Http\Requests\Ability\BfePermission_Ability_GetOneRequest;
use BigFiveEdition\Permission\Http\Requests\Ability\BfePermission_Ability_UpdateOneRequest;
use BigFiveEdition\Permission\Http\Resources\Ability\BfePermission_Ability_Resource;
use BigFiveEdition\Permission\Http\Resources\Ability\BfePermission_Ability_ResourceCollection;
use BigFiveEdition\Permission\Models\Ability;
use BigFiveEdition\Permission\Repositories\AbilityRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

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
		if (!Gate::allows('bfe-permission-has-abilities',"read_all_ability|read_all_owned_ability")) {
			throw BfeUnauthorizedException::forAbilities("read_all_ability|read_all_owned_ability");
		}
		$request->merge([
			'orderBy' => $request->input('orderBy', 'created_at'),
			'sortBy' => $request->input('sortBy', 'desc'),
			'per_page' => $request->input('per_page', 15),
		]);

		//$requestUser = $request->user();
		$with = [];
		$withCounts = [];

		$repository = app(AbilityRepository::class);
		$entities = $repository
			->with($with)
			->withCount($withCounts)
			->paginate($request->get('per_page'));

		//Build API Response
		$data = BfePermission_Ability_ResourceCollection::make($entities);
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
		if (!Gate::allows('bfe-permission-has-abilities',"read_ability|read_owned_ability")) {
			throw BfeUnauthorizedException::forAbilities("read_ability|read_owned_ability");
		}

		//$requestUser = $request->user();
		$with = [];
		$withCounts = [];

		$repository = app(AbilityRepository::class);
		$entity = $repository
			->with($with)
			->withCount($withCounts)
			->find($request->id());

		//Build API Response
		$data = BfePermission_Ability_Resource::make($entity);
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
		if (!Gate::allows('bfe-permission-has-abilities',"create_ability")) {
			throw BfeUnauthorizedException::forAbilities("create_ability");
		}

		//$requestUser = $request->user();
		$with = [];
		$withCounts = [];
		$attributes = $request->only([
			'slug',
			'name',
			'resource',
			'translations',
		]);
		$attributes = array_merge($attributes, $request->get('translations'));
		$attributes = array_filter($attributes, function ($value) {
			return !is_null($value);
		});

		$repository = app(AbilityRepository::class);
		$entity = $repository->create($attributes);
		$entity = $repository
			->with($with)
			->withCount($withCounts)
			->find($entity->id);

		//Build API Response
		$data = BfePermission_Ability_Resource::make($entity);
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
		if (!Gate::allows('bfe-permission-has-abilities',"update_ability|update_owned_ability")) {
			throw BfeUnauthorizedException::forAbilities("update_ability|update_owned_ability");
		}

		//$requestUser = $request->user();
		$with = [];
		$withCounts = [];
		$attributes = $request->only([
			'slug',
			'name',
			'resource',
			'translations',
		]);
		$attributes = array_merge($attributes, $request->get('translations'));
		$attributes = array_filter($attributes, function ($value) {
			return !is_null($value);
		});

		$repository = app(AbilityRepository::class);
		$entity = $repository->update($attributes, $request->id());

		//Build API Response
		$data = BfePermission_Ability_Resource::make($entity);
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
		if (!Gate::allows('bfe-permission-has-abilities',"delete_ability|delete_owned_ability")) {
			throw BfeUnauthorizedException::forAbilities("delete_ability|delete_owned_ability");
		}

		//$requestUser = $request->user();
		$with = [];
		$withCounts = [];

		$repository = app(AbilityRepository::class);
		$entity = $repository
			->with($with)
			->withCount($withCounts)
			->find($request->id());
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
