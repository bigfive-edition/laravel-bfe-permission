<?php

namespace BigFiveEdition\Permission\Http\Requests\Ability;

use BigFiveEdition\Permission\Http\Requests\BaseFormRequest;

class BfePermission_Ability_UpdateOneRequest extends BaseFormRequest
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
		return $this->route('ability');
	}

	protected function prepareForValidation()
	{
//		parent::prepareForValidation();
	}
}
