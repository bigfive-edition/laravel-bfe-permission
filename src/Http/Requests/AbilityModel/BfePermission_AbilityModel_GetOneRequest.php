<?php

namespace BigFiveEdition\Permission\Http\Requests\AbilityModel;

use BigFiveEdition\Permission\Http\Requests\BaseFormRequest;

class BfePermission_AbilityModel_GetOneRequest extends BaseFormRequest
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

	public function abilityId(): string
	{
		return $this->route('ability_id');
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
