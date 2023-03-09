<?php

namespace BigFiveEdition\Permission\Http\Controllers;

use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Validator;

class BfePermissionBaseController extends Controller
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	protected $validator; # The validator instance. Will be initialized upon validation.

	public function __construct()
	{
//		parent::__construct();
	}

	protected function validateRequest($request, $rules = [])
	{
		$this->validator = Validator::make($request->all(), $rules);
		if ($this->validator->fails()) {
			throw new Exception(
				'Failed to pass validation.',
				400,
				null,
				$this->validator->errors()
			);
		}
	}

	protected function successApiResponse($data, $message = null, $notification = null)
	{
		return $this->ApiResponse('success', 200, 200, $data, null, $message, $notification);
	}

	/**
	 * @param $type
	 * @param $status
	 * @param $code
	 * @param $data
	 * @param $errors
	 * @param $message
	 * @param $notification
	 * @return Application|ResponseFactory|Response
	 */
	protected function ApiResponse($type, $status, $code, $data = null, $errors = null, $message = null, $notification = null)
	{
		$format = [
			'type' => $type,
			'status' => $status,
			'code' => $code,
			'data' => $data,
			'errors' => $errors,
			'message' => $message,
			'notification' => $notification
		];
		$format = array_filter($format, function ($value) {
			return !is_null($value);
		});
		return response($format, $status)->header('Content-Type', 'application/json');
	}

	protected function errorApiResponse($data, $message = null, $notification = null)
	{
		return $this->ApiResponse('error', 200, 200, $data, null, $message, $notification);
	}

	protected function successNoContent()
	{
		return $this->ApiResponse('success', 204, 204, null, null, null, null);
	}
}
