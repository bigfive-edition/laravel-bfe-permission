<?php

namespace BigFiveEdition\Permission\Http\Requests\Team;

use BigFiveEdition\Permission\Http\Requests\BaseListRequest;

class BfePermission_Team_GetListRequest extends BaseListRequest
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

	protected function prepareForValidation()
	{
		parent::prepareForValidation();
	}
}
