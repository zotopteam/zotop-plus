    <div class="form-group row">
        <label for="settings-required" class="col-2 col-form-label">{{trans('content::field.required.label')}}</label>
        <div class="col-8">
            {field type="bool" name="settings[required]" value="$field->settings->required ?? $type->settings->required ?? 0"}

            <span class="form-help">{{trans_find('content::field.required.help')}}</span>                      
        </div>
    </div>  
