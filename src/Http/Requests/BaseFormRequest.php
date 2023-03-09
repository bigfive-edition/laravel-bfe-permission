<?php

namespace BigFiveEdition\Permission\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

//use Waavi\Sanitizer\Laravel\SanitizesInput;

abstract class BaseFormRequest extends FormRequest
{
//	use SanitizesInput;

	abstract public function authorize();

	public function rules()
	{
		$pRules = [];//parent::rules();
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
