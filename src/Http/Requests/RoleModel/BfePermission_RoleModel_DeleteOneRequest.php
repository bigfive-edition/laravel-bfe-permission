<?php

namespace BigFiveEdition\Permission\Http\Requests\RoleModel;

use BigFiveEdition\Permission\Http\Requests\BaseFormRequest;

class BfePermission_RoleModel_DeleteOneRequest extends BaseFormRequest
{
	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		$pRules = parent::rules();
		$rules = [
		];
		return array_merge($pRules, $rules);
	}

	public function messages()
	{
		$pMessages = parent::messages();
		$messages = [
		];
		return array_merge($pMessages, $messages);
	}

	public function roleId(): string
	{
		return $this->route('role_id');
	}

	public function id(): string
	{
		return $this->route('model');
	}

	protected function prepareForValidation()
	{
//		parent::prepareForValidation();
	}
}
