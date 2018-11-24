<?php
use Modules\Content\Models\Model;

$models = Model::where('disabled', '0')->orderBy('sort', 'asc')->get();

return $models;
