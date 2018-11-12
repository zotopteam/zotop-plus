
    <div class="form-group row">
        <label for="unique" class="col-2 col-form-label">{{trans('content::field.unique.label')}}</label>
        <div class="col-8">
            {field type="bool" name="unique" value="$field->unique ?? 0"}

            @if ($errors->has('unique'))
            <span class="form-help text-error">{{ $errors->first('unique') }}</span>
            @else
            <span class="form-help">{{trans('content::field.unique.help')}}</span>                     
            @endif                       
        </div>
    </div>  
