    <div class="form-group row">
        <label for="settings-placeholder" class="col-2 col-form-label">{{trans('content::field.placeholder.label')}}</label>
        <div class="col-8">
            {field type="text" name="settings[placeholder]" value="$field->settings->placeholder ?? $type->settings->placeholder ?? null" placeholder="placeholder"}
            <div class="form-help">{{trans('content::field.placeholder.help')}}</div>                 
        </div>
    </div>  
