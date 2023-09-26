<?php

namespace BigFiveEdition\Permission\Http\Requests\RoleModel;

use BigFiveEdition\Permission\Http\Requests\BaseFormRequest;

class BfePermission_RoleModel_CreateOneRequest extends BaseFormRequest
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

	public function roleId(): ?string
	{
		return $this->route('role_id') ?? $this->input('role_id');
	}

	public function modelType(): ?string
	{
		return $this->route('model_type') ?? $this->input('model_type');
	}

	public function modelId(): ?string
	{
		return $this->route('model_id') ?? $this->input('model_id');
	}

	protected function prepareForValidation()
	{
//		parent::prepareForValidation();
	}
}
