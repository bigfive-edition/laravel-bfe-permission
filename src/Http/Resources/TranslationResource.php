<?php

namespace BigFiveEdition\Permission\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TranslationResource extends JsonResource
{
	public function toArray($request)
	{
		return [
			'name' => $this->when(!is_null($this->name), $this->name),
			'description' => $this->when(!is_null($this->description), $this->description),
			'content' => $this->when(!is_null($this->content), $this->content),
			'label' => $this->when(!is_null($this->label), $this->label),
			'locale' => $this->when(!is_null($this->locale), $this->locale),
		];
	}
}
