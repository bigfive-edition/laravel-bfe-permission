<?php

namespace BigFiveEdition\Permission\Http\Controllers\TeamModel;

use BigFiveEdition\Permission\Exceptions\BfeUnauthorizedException;
use BigFiveEdition\Permission\Http\Controllers\BfePermissionBaseController;
use BigFiveEdition\Permission\Http\Requests\TeamModel\BfePermission_TeamModel_CreateOneRequest;
use BigFiveEdition\Permission\Http\Requests\TeamModel\BfePermission_TeamModel_DeleteOneRequest;
use BigFiveEdition\Permission\Http\Requests\TeamModel\BfePermission_TeamModel_GetListRequest;
use BigFiveEdition\Permission\Http\Requests\TeamModel\BfePermission_TeamModel_GetOneRequest;
use BigFiveEdition\Permission\Http\Requests\TeamModel\BfePermission_TeamModel_UpdateOneRequest;
use BigFiveEdition\Permission\Http\Resources\TeamModel\BfePermission_TeamModel_Resource;
use BigFiveEdition\Permission\Http\Resources\TeamModel\BfePermission_TeamModel_ResourceCollection;
use BigFiveEdition\Permission\Models\TeamModel;
use BigFiveEdition\Permission\Repositories\TeamModelRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

/**
 * @OpenApi\PathItem()
 * Class TeamModelController
 * @package BigFiveEdition\Permission\Http\Controllers
 */
class TeamModelController extends BfePermissionBaseController
{

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Listing TeamModel Resources
	 *
	 * Display a paginated/list of the TeamModel Resource.
	 *
	 * @param BfePermission_TeamModel_GetListRequest $request :: Illuminate request object.
	 * @return JsonResponse|mixed
	 *
	 * ---------------- API Documentation ----------------
	 * @throws Exception|Exception
	 * @Operation(id="List TeamModel Resource", tags={"TeamModel Mgt"})
	 * @Parameters(factory="BfePermission_TeamModel_GetListParameters")
	 * @File(name="openapi.json")
	 * ---------------- API Documentation END ------------
	 */
	public function index(BfePermission_TeamModel_GetListRequest $request)
	{
		if (!Gate::allows('bfe-permission-has-abilities',"read_all_teammodel|read_all_owned_teammodel")) {
			throw BfeUnauthorizedException::forAbilities("read_all_teammodel|read_all_owned_teammodel");
		}
		$request->merge([
			'orderBy' => $request->input('orderBy', 'created_at'),
			'sortBy' => $request->input('sortBy', 'desc'),
			'per_page' => $request->input('per_page', 15),
		]);

		//$requestUser = $request->user();
		$with = [];
		$withCounts = [];

		$repository = app(TeamModelRepository::class);
		$entities = $repository
			->with($with)
			->withCount($withCounts);
		if ($request->modelType() && $request->modelId()) {
			$entities = $entities->findWhere('model_type', $request->modelType());
			$entities = $entities->findWhere('model_id', $request->modelId());
		}
		if ($request->teamId()) {
			$entities = $entities->findWhere('team_id', $request->teamId());
		}
		$entities = $entities->paginate($request->get('per_page'));

		//Build API Response
		$data = BfePermission_TeamModel_ResourceCollection::make($entities);
		$message = 'message';
		$notification = [];
		return $this->successApiResponse($data, $message, $notification);
	}


	/**
	 * Single TeamModel Resource
	 *
	 * Get a single TeamModel Resource by id
	 *
	 * @param BfePermission_TeamModel_GetOneRequest $request
	 * @return JsonResponse|mixed
	 *
	 * ---------------- API Documentation ----------------
	 * @throws Exception|Exception
	 * @Operation(id="Get Single TeamModel Resource", tags={"TeamModel Mgt"})
	 * @Parameters(factory="BfePermission_TeamModel_GetOneParameters")
	 * @File(name="openapi.json")
	 * ---------------- API Documentation END ------------
	 */
	public function show(BfePermission_TeamModel_GetOneRequest $request)
	{
		if (!Gate::allows('bfe-permission-has-abilities',"read_teammodel|read_owned_teammodel")) {
			throw BfeUnauthorizedException::forAbilities("read_teammodel|read_owned_teammodel");
		}

		//$requestUser = $request->user();
		$with = [];
		$withCounts = [];

		$repository = app(TeamModelRepository::class);
		$entity = $repository
			->with($with)
			->withCount($withCounts);
		if ($request->modelType() && $request->modelId()) {
			$entity = $entity->findWhere('model_type', $request->modelType());
			$entity = $entity->findWhere('model_id', $request->modelId());
		}
		if ($request->teamId()) {
			$entity = $entity->findWhere('team_id', $request->teamId());
		}
		$entity = $entity->find($request->id());

		//Build API Response
		$data = BfePermission_TeamModel_Resource::make($entity);
		$message = 'message';
		$notification = [];
		return $this->successApiResponse($data, $message, $notification);
	}

	/**
	 * Create TeamModel Resource
	 *
	 * Create new TeamModel Resource
	 *
	 * @param BfePermission_TeamModel_CreateOneRequest $request
	 * @return JsonResponse|mixed
	 *
	 * ---------------- API Documentation ----------------
	 * @throws Exception|Exception
	 * @Operation(id="Create TeamModel Resource", tags={"TeamModel Mgt"})
	 * @Parameters(factory="BfePermission_TeamModel_CreateOneParameters")
	 * @File(name="openapi.json")
	 * ---------------- API Documentation END ------------
	 */
	public function store(BfePermission_TeamModel_CreateOneRequest $request)
	{
		if (!Gate::allows('bfe-permission-has-abilities',"create_teammodel")) {
			throw BfeUnauthorizedException::forAbilities("create_teammodel");
		}

		//$requestUser = $request->user();
		$with = [];
		$withCounts = [];
		$attributes = $request->only([
			'model_type',
			'model_id',
			'attribute',
		]);
		$attributes = array_filter($attributes, function ($value) {
			return !is_null($value);
		});
		$attributes['team_id'] = $request->teamId();

		$repository = app(TeamModelRepository::class);
		$entity = $repository->create($attributes);
		$entity = $repository
			->with($with)
			->withCount($withCounts)
			->find($entity->id);

		//Build API Response
		$data = BfePermission_TeamModel_Resource::make($entity);
		$message = 'message';
		$notification = [];
		return $this->successApiResponse($data, $message, $notification);
	}

	/**
	 * Update TeamModel Resource
	 *
	 * Update a TeamModel Resource details
	 *
	 * @param BfePermission_TeamModel_UpdateOneRequest $request
	 * @return JsonResponse|mixed
	 *
	 * ---------------- API Documentation ----------------
	 * @throws Exception|Exception
	 * @Operation(id="Update TeamModel Resource", tags={"TeamModel Mgt"})
	 * @Parameters(factory="BfePermission_TeamModel_UpdateOneParameters")
	 * @File(name="openapi.json")
	 * ---------------- API Documentation END ------------
	 */
	public function update(BfePermission_TeamModel_UpdateOneRequest $request)
	{
		if (!Gate::allows('bfe-permission-has-abilities',"update_teammodel|update_owned_teammodel")) {
			throw BfeUnauthorizedException::forAbilities("update_teammodel|update_owned_teammodel");
		}

		//$requestUser = $request->user();
		$with = [];
		$withCounts = [];
		$attributes = $request->only([
			'model_type',
			'model_id',
			'attribute',
		]);
		$attributes = array_filter($attributes, function ($value) {
			return !is_null($value);
		});
		$attributes['team_id'] = $request->teamId();

		$repository = app(TeamModelRepository::class);
		$entity = $repository->update($attributes, $request->id());

		//Build API Response
		$data = BfePermission_TeamModel_Resource::make($entity);
		$message = 'message';
		$notification = [];
		return $this->successApiResponse($data, $message, $notification);
	}

	/**
	 * Delete TeamModel Resource
	 *
	 * Delete a TeamModel Resource instance
	 * @param BfePermission_TeamModel_DeleteOneRequest $request
	 * @return JsonResponse|mixed
	 *
	 * ---------------- API Documentation ----------------
	 * @throws Exception|Exception
	 * @Operation(id="Delete TeamModel Resource", tags={"TeamModel Mgt"})
	 * @Parameters(factory="BfePermission_TeamModel_DeleteOneParameters")
	 * @File(name="openapi.json")
	 * ---------------- API Documentation END ------------
	 */
	public function destroy(BfePermission_TeamModel_DeleteOneRequest $request)
	{
		if (!Gate::allows('bfe-permission-has-abilities',"delete_teammodel|delete_owned_teammodel")) {
			throw BfeUnauthorizedException::forAbilities("delete_teammodel|delete_owned_teammodel");
		}

		//$requestUser = $request->user();
		$with = [];
		$withCounts = [];

		$repository = app(TeamModelRepository::class);
		$entity = $repository
			->with($with)
			->withCount($withCounts);
		if ($request->modelType() && $request->modelId()) {
			$entity = $entity->findWhere('model_type', $request->modelType());
			$entity = $entity->findWhere('model_id', $request->modelId());
		}
		if ($request->teamId()) {
			$entity = $entity->findWhere('team_id', $request->teamId());
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
