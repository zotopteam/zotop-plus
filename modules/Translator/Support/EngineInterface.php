<?php

namespace Modules\Translator\Support;

interface EngineInterface
{
	public function translate($text, $from, $to);
}
