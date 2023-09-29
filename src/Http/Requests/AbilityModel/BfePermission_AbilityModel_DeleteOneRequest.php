<?php

namespace BigFiveEdition\Permission\Http\Requests\AbilityModel;

use BigFiveEdition\Permission\Http\Requests\BaseFormRequest;

class BfePermission_AbilityModel_DeleteOneRequest extends BaseFormRequest
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

	public function abilityId(): ?string
	{
		return $this->route('ability_id') ?? $this->input('ability_id');
	}

	public function modelType(): ?string
	{
		return $this->route('model_type') ?? $this->input('model_type');
	}

	public function modelId(): ?string
	{
		return $this->route('model_id') ?? $this->input('model_id');
	}

	public function id(): string
	{
		return $this->route('ability_model_id');
	}

	protected function prepareForValidation()
	{
		parent::prepareForValidation();
	}
}
