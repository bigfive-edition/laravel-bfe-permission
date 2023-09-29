<?php

namespace BigFiveEdition\Permission\Middlewares;

use Closure;
use Illuminate\Support\Facades\App;

class LocaleMiddleware
{
	public function handle($request, Closure $next)
	{
		if ($request->header('Accept-Language')) {
			$locale = $request->header('Accept-Language');
			$partsLocale = explode(';', $locale);
			App::setLocale($partsLocale[0]); //i use only the first part cause is the language definition.
		}
		return $next($request);
	}
}
