<?php

namespace BigFiveEdition\Permission\Http\Controllers\TeamModel;

use BigFiveEdition\Permission\Http\Controllers\BfePermissionBaseController;
use BigFiveEdition\Permission\Http\Requests\TeamModel\BfePermission_TeamModel_CreateOneRequest;
use BigFiveEdition\Permission\Http\Requests\TeamModel\BfePermission_TeamModel_DeleteOneRequest;
use BigFiveEdition\Permission\Http\Requests\TeamModel\BfePermission_TeamModel_GetListRequest;
use BigFiveEdition\Permission\Http\Requests\TeamModel\BfePermission_TeamModel_GetOneRequest;
use BigFiveEdition\Permission\Http\Requests\TeamModel\BfePermission_TeamModel_UpdateOneRequest;
use BigFiveEdition\Permission\Http\Resources\TeamModel\BfePermission_TeamModel_Resource;
use BigFiveEdition\Permission\Http\Resources\TeamModel\BfePermission_TeamModel_ResourceCollection;
use BigFiveEdition\Permission\Models\TeamModel;
use Exception;
use Illuminate\Http\JsonResponse;

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
		//$requestUser = $request->user();
		$with = array_merge([
		], $request->with());
		$withCounts = array_merge([
		], $request->withCount());

		$entities = TeamModel::query()
			->with($with)
			->withCount($withCounts)
			->where('team_id', $request->teamId())
			->paginate($request->get('per_page'));

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
		//$requestUser = $request->user();
		$with = array_merge([
		], $request->with());
		$withCounts = array_merge([
		], $request->withCount());
		$entity = TeamModel::query()
			->with($with)
			->withCount($withCounts)
			->where('team_id', $request->teamId())
			->findOrFail($request->id());

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
		//$requestUser = $request->user();
		$with = array_merge([
		], $request->with());
		$withCounts = array_merge([
		], $request->withCount());
		$attributes = $request->only([
			'model_type',
			'model_id',
			'attribute',
		]);
		$attributes = array_filter($attributes, function ($value) {
			return !is_null($value);
		});
		$attributes['team_id'] = $request->teamId();

		$entity = TeamModel::create($attributes);
		$entity = TeamModel::query()
			->with($with)
			->withCount($withCounts)
			->findOrFail($entity->id);

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
		//$requestUser = $request->user();
		$with = array_merge([
		], $request->with());
		$withCounts = array_merge([
		], $request->withCount());
		$attributes = $request->only([
			'model_type',
			'model_id',
			'attribute',
		]);
		$attributes = array_filter($attributes, function ($value) {
			return !is_null($value);
		});
		$attributes['team_id'] = $request->teamId();

		$entity = TeamModel::query()
			->with($with)
			->withCount($withCounts)
			->where('team_id', $request->teamId())
			->findOrFail($request->id());
		$entity->fill($attributes);
		$entity->save();

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
		//$requestUser = $request->user();
		$with = array_merge([
		], $request->with());
		$withCounts = array_merge([
		], $request->withCount());
		$entity = TeamModel::query()
			->with($with)
			->withCount($withCounts)
			->where('team_id', $request->teamId())
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
