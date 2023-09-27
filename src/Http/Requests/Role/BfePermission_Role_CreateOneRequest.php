<?php

namespace BigFiveEdition\Permission\Http\Requests\Role;

use BigFiveEdition\Permission\Http\Requests\BaseFormRequest;

class BfePermission_Role_CreateOneRequest extends BaseFormRequest
{
	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		$pRules = parent::rules();
		$rules = [
			'slug' => 'required|unique:bfe_permission_roles|min:3',
			'name' => 'nullable|string',
			'translations' => 'nullable|array',
			'translations.*.name' => 'nullable|string',
		];
		return array_merge($pRules, $rules);
	}

	public function messages()
	{
		$pMessages = parent::messages();
		$messages = [
			'slug.required' => ['code' => 4241, 'field' => 'slug', 'description' => trans('backoffice::validation.required')],
			'slug.unique' => ['code' => 4242, 'field' => 'slug', 'description' => trans('backoffice::validation.unique')],
			'slug.min' => ['code' => 4243, 'field' => 'slug', 'description' => trans('backoffice::validation.min')],

			'name.required' => ['code' => 4241, 'field' => 'name', 'description' => trans('backoffice::validation.required')],
			'name.string' => ['code' => 4242, 'field' => 'name', 'description' => trans('backoffice::validation.string')],
			'name.min' => ['code' => 4243, 'field' => 'name', 'description' => trans('backoffice::validation.min')],

			'translations.required' => "'code' => 4241, 'field' => 'translations', 'description' =>" . trans('backoffice::validation.required'),
			'translations.array' => "'code' => 4244, 'field' => 'translations', 'description' =>" . trans('backoffice::validation.array'),
			'translations.*.*.required' => "'code' => 4241, 'field' => 'translations', 'description' =>" . trans('backoffice::validation.required'),
		];
		return array_merge($pMessages, $messages);
	}

	protected function prepareForValidation()
	{
//		parent::prepareForValidation();
	}
}
