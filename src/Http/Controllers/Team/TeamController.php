<?php

namespace BigFiveEdition\Permission\Http\Controllers\Team;

use BigFiveEdition\Permission\Exceptions\BfeUnauthorizedException;
use BigFiveEdition\Permission\Http\Controllers\BfePermissionBaseController;
use BigFiveEdition\Permission\Http\Requests\Team\BfePermission_Team_CreateOneRequest;
use BigFiveEdition\Permission\Http\Requests\Team\BfePermission_Team_DeleteOneRequest;
use BigFiveEdition\Permission\Http\Requests\Team\BfePermission_Team_GetListRequest;
use BigFiveEdition\Permission\Http\Requests\Team\BfePermission_Team_GetOneRequest;
use BigFiveEdition\Permission\Http\Requests\Team\BfePermission_Team_UpdateOneRequest;
use BigFiveEdition\Permission\Http\Resources\Team\BfePermission_Team_Resource;
use BigFiveEdition\Permission\Http\Resources\Team\BfePermission_Team_ResourceCollection;
use BigFiveEdition\Permission\Models\Team;
use BigFiveEdition\Permission\Repositories\TeamRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

/**
 * @OpenApi\PathItem()
 * Class TeamController
 * @package BigFiveEdition\Permission\Http\Controllers
 */
class TeamController extends BfePermissionBaseController
{

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Listing Team Resources
	 *
	 * Display a paginated/list of the Team Resource.
	 *
	 * @param BfePermission_Team_GetListRequest $request :: Illuminate request object.
	 * @return JsonResponse|mixed
	 *
	 * ---------------- API Documentation ----------------
	 * @throws Exception|Exception
	 * @Operation(id="List Team Resource", tags={"Team Mgt"})
	 * @Parameters(factory="BfePermission_Team_GetListParameters")
	 * @File(name="openapi.json")
	 * ---------------- API Documentation END ------------
	 */
	public function index(BfePermission_Team_GetListRequest $request)
	{
		if (!Gate::allows('bfe-permission-has-abilities',"read_all_team|read_all_owned_team")) {
			throw BfeUnauthorizedException::forAbilities("read_all_team|read_all_owned_team");
		}
		$request->merge([
			'orderBy' => $request->input('orderBy', 'created_at'),
			'sortBy' => $request->input('sortBy', 'desc'),
			'per_page' => $request->input('per_page', 15),
		]);

		//$requestUser = $request->user();
		$with = [];
		$withCounts = [];

		$repository = app(TeamRepository::class);
		$entities = $repository
			->with($with)
			->withCount($withCounts)
			->paginate($request->get('per_page'));

		//Build API Response
		$data = BfePermission_Team_ResourceCollection::make($entities);
		$message = 'message';
		$notification = [];
		return $this->successApiResponse($data, $message, $notification);
	}


	/**
	 * Single Team Resource
	 *
	 * Get a single Team Resource by id
	 *
	 * @param BfePermission_Team_GetOneRequest $request
	 * @return JsonResponse|mixed
	 *
	 * ---------------- API Documentation ----------------
	 * @throws Exception|Exception
	 * @Operation(id="Get Single Team Resource", tags={"Team Mgt"})
	 * @Parameters(factory="BfePermission_Team_GetOneParameters")
	 * @File(name="openapi.json")
	 * ---------------- API Documentation END ------------
	 */
	public function show(BfePermission_Team_GetOneRequest $request)
	{
		if (!Gate::allows('bfe-permission-has-abilities',"read_team|read_owned_team")) {
			throw BfeUnauthorizedException::forAbilities("read_team|read_owned_team");
		}

		//$requestUser = $request->user();
		$with = [];
		$withCounts = [];

		$repository = app(TeamRepository::class);
		$entity = $repository
			->with($with)
			->withCount($withCounts)
			->find($request->id());

		//Build API Response
		$data = BfePermission_Team_Resource::make($entity);
		$message = 'message';
		$notification = [];
		return $this->successApiResponse($data, $message, $notification);
	}

	/**
	 * Create Team Resource
	 *
	 * Create new Team Resource
	 *
	 * @param BfePermission_Team_CreateOneRequest $request
	 * @return JsonResponse|mixed
	 *
	 * ---------------- API Documentation ----------------
	 * @throws Exception|Exception
	 * @Operation(id="Create Team Resource", tags={"Team Mgt"})
	 * @Parameters(factory="BfePermission_Team_CreateOneParameters")
	 * @File(name="openapi.json")
	 * ---------------- API Documentation END ------------
	 */
	public function store(BfePermission_Team_CreateOneRequest $request)
	{
		if (!Gate::allows('bfe-permission-has-abilities',"create_team")) {
			throw BfeUnauthorizedException::forAbilities("create_team");
		}

		//$requestUser = $request->user();
		$with = [];
		$withCounts = [];
		$attributes = $request->only([
			'slug',
			'name',
			'translations',
		]);
		$attributes = array_merge($attributes, $request->get('translations'));
		$attributes = array_filter($attributes, function ($value) {
			return !is_null($value);
		});

		$repository = app(TeamRepository::class);
		$entity = $repository->create($attributes);
		$entity = $repository
			->with($with)
			->withCount($withCounts)
			->find($entity->id);

		//Build API Response
		$data = BfePermission_Team_Resource::make($entity);
		$message = 'message';
		$notification = [];
		return $this->successApiResponse($data, $message, $notification);
	}

	/**
	 * Update Team Resource
	 *
	 * Update a Team Resource details
	 *
	 * @param BfePermission_Team_UpdateOneRequest $request
	 * @return JsonResponse|mixed
	 *
	 * ---------------- API Documentation ----------------
	 * @throws Exception|Exception
	 * @Operation(id="Update Team Resource", tags={"Team Mgt"})
	 * @Parameters(factory="BfePermission_Team_UpdateOneParameters")
	 * @File(name="openapi.json")
	 * ---------------- API Documentation END ------------
	 */
	public function update(BfePermission_Team_UpdateOneRequest $request)
	{
		if (!Gate::allows('bfe-permission-has-abilities',"update_team|update_owned_team")) {
			throw BfeUnauthorizedException::forAbilities("update_team|update_owned_team");
		}

		//$requestUser = $request->user();
		$with = [];
		$withCounts = [];
		$attributes = $request->only([
			'slug',
			'name',
			'translations',
		]);
		$attributes = array_merge($attributes, $request->get('translations'));
		$attributes = array_filter($attributes, function ($value) {
			return !is_null($value);
		});

		$repository = app(TeamRepository::class);
		$entity = $repository->update($attributes, $request->id());

		//Build API Response
		$data = BfePermission_Team_Resource::make($entity);
		$message = 'message';
		$notification = [];
		return $this->successApiResponse($data, $message, $notification);
	}

	/**
	 * Delete Team Resource
	 *
	 * Delete a Team Resource instance
	 * @param BfePermission_Team_DeleteOneRequest $request
	 * @return JsonResponse|mixed
	 *
	 * ---------------- API Documentation ----------------
	 * @throws Exception|Exception
	 * @Operation(id="Delete Team Resource", tags={"Team Mgt"})
	 * @Parameters(factory="BfePermission_Team_DeleteOneParameters")
	 * @File(name="openapi.json")
	 * ---------------- API Documentation END ------------
	 */
	public function destroy(BfePermission_Team_DeleteOneRequest $request)
	{
		if (!Gate::allows('bfe-permission-has-abilities',"delete_team|delete_owned_team")) {
			throw BfeUnauthorizedException::forAbilities("delete_team|delete_owned_team");
		}

		//$requestUser = $request->user();
		$with = [];
		$withCounts = [];

		$repository = app(TeamRepository::class);
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
