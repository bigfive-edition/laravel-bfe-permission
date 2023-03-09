<?php

namespace BigFiveEdition\Permission\Http\Controllers\Team;

use BigFiveEdition\Permission\Http\Controllers\BfePermissionBaseController;
use BigFiveEdition\Permission\Http\Requests\Team\BfePermission_Team_CreateOneRequest;
use BigFiveEdition\Permission\Http\Requests\Team\BfePermission_Team_DeleteOneRequest;
use BigFiveEdition\Permission\Http\Requests\Team\BfePermission_Team_GetListRequest;
use BigFiveEdition\Permission\Http\Requests\Team\BfePermission_Team_GetOneRequest;
use BigFiveEdition\Permission\Http\Requests\Team\BfePermission_Team_UpdateOneRequest;
use BigFiveEdition\Permission\Http\Resources\Team\BfePermission_Team_Resource;
use BigFiveEdition\Permission\Http\Resources\Team\BfePermission_Team_ResourceCollection;
use BigFiveEdition\Permission\Models\Team;
use Exception;
use Illuminate\Http\JsonResponse;

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
		//$requestUser = $request->user();
		$with = array_merge([
		], $request->with());
		$withCounts = array_merge([
		], $request->withCount());

		$entities = Team::query()
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
		//$requestUser = $request->user();
		$with = array_merge([
		], $request->with());
		$withCounts = array_merge([
		], $request->withCount());
		$entity = Team::query()
			->with($with)
			->withCount($withCounts)
			->findOrFail($request->id());

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

		$entity = Team::create($attributes);
		$entity = Team::query()
			->with($with)
			->withCount($withCounts)
			->findOrFail($entity->id);

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

		$entity = Team::query()
			->with($with)
			->withCount($withCounts)
			->findOrFail($request->id());
		$entity->fill($attributes);
		$entity->save();

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
		//$requestUser = $request->user();
		$with = array_merge([
		], $request->with());
		$withCounts = array_merge([
		], $request->withCount());
		$entity = Team::query()
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
