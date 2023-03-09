<?php


namespace BigFiveEdition\Permission\Http\Requests;

/**
 * @property int per_page
 * @property string order_by
 * @property string order
 * @property string search
 * @property mixed account
 */
class BaseListRequest extends BaseFormRequest
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

		if (!intval($this->input('page'))) {
			$this->merge([
				'page' => 1
			]);
		}
		if (!$this->input('per_page')) {
			$this->merge([
				'per_page' => 15
			]);
		}
		if (!$this->input('orderBy')) {
			$this->merge([
				'orderBy' => 'created_at'
			]);
		}
		if (!$this->input('sortedBy')) {
			$this->merge([
				'sortedBy' => 'desc'
			]);
		}
	}
}

