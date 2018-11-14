    <div class="form-group row">
        <label for="settings-resize" class="col-2 col-form-label">{{trans('content::field.resize.label')}}</label>
        <div class="col-8">
            {field type="enable" name="settings[resize]" value="$field->settings->resize ?? $type->settings->resize ?? 0"}

            <span class="form-help">{{trans_find('content::field.resize.help')}}</span>                  
        </div>
    </div>  
