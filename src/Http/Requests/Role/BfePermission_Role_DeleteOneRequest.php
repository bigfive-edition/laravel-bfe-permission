<?php

namespace BigFiveEdition\Permission\Http\Requests\Role;

use BigFiveEdition\Permission\Http\Requests\BaseFormRequest;

class BfePermission_Role_DeleteOneRequest extends BaseFormRequest
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

	public function id(): string
	{
		return $this->route('role');
	}

	protected function prepareForValidation()
	{
//		parent::prepareForValidation();
	}
}
