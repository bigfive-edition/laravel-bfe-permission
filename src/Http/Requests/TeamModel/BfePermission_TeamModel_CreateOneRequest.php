<?php

namespace BigFiveEdition\Permission\Http\Requests\TeamModel;

use BigFiveEdition\Permission\Http\Requests\BaseFormRequest;

class BfePermission_TeamModel_CreateOneRequest extends BaseFormRequest
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

	public function teamId(): ?string
	{
		return $this->route('team_id') ?? $this->input('team_id');
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
