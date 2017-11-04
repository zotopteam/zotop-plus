<div class="container-fluid">
	<div class="form-group row">
        <label for="name" class="col-2 col-form-label">{{trans('region::module.parent.label')}}</label>
        <div class="col-10">
        	<input type="hidden" class="form-control" name="parent_id" value="{{$region->parent_id}}">
            <p class="form-control-static">{{$parent_region_title}}</p>
        </div>
    </div>

    <div class="form-group row">
        <label for="title" class="col-2 col-form-label required">{{trans('region::module.name.label')}}</label>
        <div class="col-10">
            {field type="text" name="title" required="required"}

            @if ($errors->has('title'))
            <span class="form-help text-error">{{ $errors->first('title') }}</span>
            @else
            <span class="form-help"></span>
            @endif
        </div>                      
    </div>
    
</div>