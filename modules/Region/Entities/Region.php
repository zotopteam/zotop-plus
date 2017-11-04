<?php

namespace Modules\Region\Entities;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    public $timestamps = FALSE;
    protected $fillable = ['parent_id', 'title', 'sort'];

    public static function getParents($id)
    {
        if (!isset($parents)) $parents = [];
        $region = self::find($id);
        if ($region->parent_id) {
            $parent    = self::find($region->parent_id);
            $parents[] = $parent;
            self::getParents($parent->id);
        }
        return $parents;
    }

    public static function getChilds($id)
    {
        if (!isset($childs)) $childs = [];
        $region_childs = self::where('parent_id', $id)->get();
        if ($region_childs) {
            foreach ($region_childs as $region_child) {
                $childs[] = $region_child->id;
                self::getChilds($region_child->id);
            }
        }
        return $childs;
    }

}
