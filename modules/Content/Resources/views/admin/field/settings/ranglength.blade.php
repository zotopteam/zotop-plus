    <div class="form-group row">
        <label for="nullable" class="col-2 col-form-label">{{trans('content::field.ranglength.label')}}</label>
        <div class="col-4">
            <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text">{{trans('content::field.ranglength.min')}}</span></div>
                {field type="number" name="settings[minlength]" value="$field->settings->minlength ?? 0" min="0"}
                <div class="input-group-prepend"><span class="input-group-text">{{trans('content::field.ranglength.max')}}</span></div>
                {field type="number" name="settings[maxlength]" value="$field->settings->maxlength ?? 255" min="1"}
                <div class="input-group-append"><span class="input-group-text">{{trans('content::field.ranglength.unit')}}</span></div>
            </div>                     
        </div>
    </div>  
